<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\Saver;
use App\Models\Transaction;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    public function index(Request $request)
    {
        $transactions = Transaction::with(['saver', 'user'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('teller.transactions.index', compact('transactions'));
    }

    public function scanner()
    {
        return view('teller.transactions.scanner');
    }

    public function searchAccount(Request $request)
    {
        $request->validate([
            'query' => ['required', 'string'],
        ]);

        $query = trim($request->input('query'));

        $saver = Saver::where('account_number', $query)
            ->orWhere('student_id_number', $query)
            ->first();

        if (!$saver) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => "Nasabah dengan nomor rekening/NIS '{$query}' tidak ditemukan.",
                ], 404);
            }
            return back()->with('error', "Nasabah dengan nomor rekening/NIS '{$query}' tidak ditemukan.");
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => [
                    'id'                => $saver->id,
                    'account_number'    => $saver->account_number,
                    'student_id_number' => $saver->student_id_number,
                    'full_name'         => $saver->full_name,
                    'class_name'        => $saver->class_name,
                    'major'             => $saver->major,
                    'balance'           => $saver->balance,
                    'balance_formatted' => 'Rp ' . number_format($saver->balance, 0, ',', '.'),
                    'max_withdrawable'  => max(0, $saver->balance - 10000),
                    'action_url'        => route('teller.transactions.create', $saver),
                ],
            ]);
        }

        return redirect()->route('teller.transactions.create', $saver);
    }

    public function create(Request $request, Saver $saver)
    {
        $maxWithdrawable = max(0, $saver->balance - 10000);
        $recentTransactions = $saver->transactions()->take(5)->get();

        $todayApprovedRequest = \App\Models\WithdrawalRequest::where('saver_id', $saver->id)
            ->whereDate('pickup_date', today())
            ->where('status', 'approved')
            ->first();

        $prefilledAmount = $request->query('amount');

        return view('teller.transactions.create', compact('saver', 'maxWithdrawable', 'recentTransactions', 'todayApprovedRequest', 'prefilledAmount'));
    }

    public function deposit(Request $request, Saver $saver)
    {
        $validated = $request->validate([
            'amount'      => ['required', 'integer', 'min:10000'],
            'description' => ['nullable', 'string', 'max:255'],
        ], [
            'amount.min' => 'Nominal setoran minimal adalah Rp 10.000.',
        ]);

        try {
            $transaction = $this->transactionService->deposit(
                saver: $saver,
                amount: (int) $validated['amount'],
                tellerId: Auth::id(),
                description: $validated['description'] ?? 'Setoran Tunai di Loket'
            );

            return redirect()->route('teller.transactions.receipt', $transaction)
                ->with('success', 'Setoran tunai berhasil diproses! Saldo nasabah telah diperbarui.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function withdraw(Request $request, Saver $saver)
    {
        $hasToken = !empty($request->input('authorization_token'));

        $rules = [
            'amount'              => ['required', 'integer', 'min:10000'],
            'description'         => ['nullable', 'string', 'max:255'],
            'authorization_token' => ['nullable', 'string'],
        ];

        if (!$hasToken) {
            $rules['security_pin'] = ['required', 'digits:6'];
        }

        $validated = $request->validate($rules, [
            'amount.min'            => 'Nominal penarikan minimal adalah Rp 10.000.',
            'security_pin.digits'   => 'PIN keamanan nasabah harus berupa 6 digit angka.',
            'security_pin.required' => 'PIN keamanan wajib diisi atau gunakan otorisasi via HP nasabah.',
        ]);

        try {
            $transaction = $this->transactionService->withdraw(
                saver: $saver,
                amount: (int) $validated['amount'],
                pin: $validated['security_pin'] ?? null,
                tellerId: Auth::id(),
                description: $validated['description'] ?? 'Penarikan Tunai di Loket',
                authorizationToken: $validated['authorization_token'] ?? null
            );

            return redirect()->route('teller.transactions.receipt', $transaction)
                ->with('success', 'Penarikan tunai berhasil diproses! Silakan serahkan uang tunai dan struk kepada nasabah.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function requestPin(Request $request, Saver $saver)
    {
        $validated = $request->validate([
            'amount'      => ['required', 'integer', 'min:10000'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        $maxWithdrawable = max(0, $saver->balance - 10000);
        if ($validated['amount'] > $maxWithdrawable) {
            return response()->json([
                'success' => false,
                'message' => 'Nominal melebihi batas maksimal penarikan (Rp ' . number_format($maxWithdrawable, 0, ',', '.') . ').',
            ], 422);
        }

        // Expire ALL previous pending requests for this saver immediately
        \App\Models\PinAuthorizationRequest::where('saver_id', $saver->id)
            ->where('status', 'pending')
            ->update([
                'status'     => 'expired',
                'expires_at' => now(),
            ]);

        $token = \Illuminate\Support\Str::random(40);
        $authRequest = \App\Models\PinAuthorizationRequest::create([
            'saver_id'    => $saver->id,
            'teller_id'   => Auth::id(),
            'amount'      => $validated['amount'],
            'description' => $validated['description'] ?? 'Penarikan Tunai di Loket',
            'token'       => $token,
            'status'      => 'pending',
            'expires_at'  => now()->addSeconds(90),
        ]);

        return response()->json([
            'success'    => true,
            'message'    => 'Permintaan PIN telah dikirimkan ke HP nasabah.',
            'request_id' => $authRequest->id,
            'token'      => $token,
            'expires_at' => $authRequest->expires_at->toIso8601String(),
        ]);
    }

    public function checkPinStatus(Saver $saver, $requestId)
    {
        $authRequest = \App\Models\PinAuthorizationRequest::where('id', $requestId)
            ->where('saver_id', $saver->id)
            ->first();

        if (!$authRequest) {
            return response()->json(['success' => false, 'message' => 'Permintaan otorisasi tidak ditemukan.'], 404);
        }

        if ($authRequest->status === 'pending' && $authRequest->isExpired()) {
            $authRequest->update(['status' => 'expired']);
        }

        return response()->json([
            'success' => true,
            'status'  => $authRequest->status,
            'token'   => $authRequest->status === 'approved' ? $authRequest->token : null,
        ]);
    }

    public function cancelPinRequest(Saver $saver, $requestId)
    {
        $authRequest = \App\Models\PinAuthorizationRequest::where('id', $requestId)
            ->where('saver_id', $saver->id)
            ->first();

        if ($authRequest && $authRequest->status === 'pending') {
            $authRequest->update(['status' => 'rejected']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permintaan PIN telah dibatalkan.',
        ]);
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['saver', 'user']);

        return view('teller.transactions.receipt', compact('transaction'));
    }
}

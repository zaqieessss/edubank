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
            ->latest('transaction_date')
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

    public function create(Saver $saver)
    {
        $maxWithdrawable = max(0, $saver->balance - 10000);
        $recentTransactions = $saver->transactions()->take(5)->get();

        return view('teller.transactions.create', compact('saver', 'maxWithdrawable', 'recentTransactions'));
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
        $validated = $request->validate([
            'amount'       => ['required', 'integer', 'min:10000'],
            'security_pin' => ['required', 'digits:6'],
            'description'  => ['nullable', 'string', 'max:255'],
        ], [
            'amount.min'          => 'Nominal penarikan minimal adalah Rp 10.000.',
            'security_pin.digits' => 'PIN keamanan nasabah harus berupa 6 digit angka.',
        ]);

        try {
            $transaction = $this->transactionService->withdraw(
                saver: $saver,
                amount: (int) $validated['amount'],
                pin: $validated['security_pin'],
                tellerId: Auth::id(),
                description: $validated['description'] ?? 'Penarikan Tunai di Loket'
            );

            return redirect()->route('teller.transactions.receipt', $transaction)
                ->with('success', 'Penarikan tunai berhasil diproses! Silakan serahkan uang tunai dan struk kepada nasabah.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function receipt(Transaction $transaction)
    {
        $transaction->load(['saver', 'user']);

        return view('teller.transactions.receipt', compact('transaction'));
    }
}

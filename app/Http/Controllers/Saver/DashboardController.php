<?php

namespace App\Http\Controllers\Saver;

use App\Http\Controllers\Controller;
use App\Models\PinAuthorizationRequest;
use App\Models\Saver;
use App\Services\QrCodeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {}

    /**
     * Beranda (Home) - Mobile App Web UI
     */
    public function index()
    {
        $user = Auth::user();
        $saver = Saver::with(['member', 'account'])->where('user_id', $user->id)->firstOrFail();

        $qrCodeDataUri = $this->qrCodeService->generateDataUri($saver->account_number);

        $recentTransactions = $saver->transactions()
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->take(10)
            ->get();

        // Calculate holding balance & withdrawable limit
        $holdingBalance = 10000;
        $balance = (int) ($saver->account->balance ?? $saver->balance ?? 0);
        $maxWithdrawable = max(0, $balance - $holdingBalance);

        // Monthly stats for this saver
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyDeposits = (int) $saver->transactions()
            ->where('transaction_type', 'deposit')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');

        $monthlyWithdrawals = (int) $saver->transactions()
            ->whereIn('transaction_type', ['withdraw', 'withdrawal'])
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');

        $totalTransactionsCount = $saver->transactions()->count();

        return view('saver.dashboard', compact(
            'saver',
            'balance',
            'qrCodeDataUri',
            'recentTransactions',
            'holdingBalance',
            'maxWithdrawable',
            'monthlyDeposits',
            'monthlyWithdrawals',
            'totalTransactionsCount'
        ));
    }

    /**
     * Mutasi & Riwayat Transaksi - Mobile App Web UI
     */
    public function mutation(Request $request)
    {
        $user = Auth::user();
        $saver = Saver::with(['member', 'account'])->where('user_id', $user->id)->firstOrFail();
        $qrCodeDataUri = $this->qrCodeService->generateDataUri($saver->account_number);

        $query = $saver->transactions()
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc');

        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        if ($request->filled('type') && in_array($request->type, ['deposit', 'withdrawal', 'withdraw'], true)) {
            if ($request->type === 'withdrawal' || $request->type === 'withdraw') {
                $query->whereIn('transaction_type', ['withdraw', 'withdrawal']);
            } else {
                $query->where('transaction_type', $request->type);
            }
        }

        $transactions = $query->paginate(20)->withQueryString();

        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyDeposits = (int) $saver->transactions()
            ->where('transaction_type', 'deposit')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');

        $monthlyWithdrawals = (int) $saver->transactions()
            ->whereIn('transaction_type', ['withdraw', 'withdrawal'])
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');

        return view('saver.mutation', compact(
            'saver',
            'transactions',
            'qrCodeDataUri',
            'monthlyDeposits',
            'monthlyWithdrawals'
        ));
    }

    /**
     * Profil & Pengaturan Akun - Mobile App Web UI
     */
    public function profile()
    {
        $user = Auth::user();
        $saver = Saver::with(['member', 'account'])->where('user_id', $user->id)->firstOrFail();
        $qrCodeDataUri = $this->qrCodeService->generateDataUri($saver->account_number);

        $balance = (int) ($saver->account->balance ?? $saver->balance ?? 0);
        $holdingBalance = 10000;
        $maxWithdrawable = max(0, $balance - $holdingBalance);

        return view('saver.profile', compact(
            'saver',
            'user',
            'balance',
            'holdingBalance',
            'maxWithdrawable',
            'qrCodeDataUri'
        ));
    }

    /**
     * Update 6-Digit Transaction PIN
     */
    public function updatePin(Request $request)
    {
        $validated = $request->validate([
            'current_pin'          => ['required', 'digits:6'],
            'new_pin'              => ['required', 'digits:6', 'different:current_pin'],
            'new_pin_confirmation' => ['required', 'same:new_pin'],
        ], [
            'current_pin.required'        => 'PIN saat ini wajib diisi.',
            'current_pin.digits'          => 'PIN saat ini harus berupa 6 digit angka.',
            'new_pin.required'            => 'PIN baru wajib diisi.',
            'new_pin.digits'              => 'PIN baru harus berupa 6 digit angka.',
            'new_pin.different'           => 'PIN baru tidak boleh sama dengan PIN saat ini.',
            'new_pin_confirmation.required' => 'Konfirmasi PIN baru wajib diisi.',
            'new_pin_confirmation.same'   => 'Konfirmasi PIN baru tidak cocok.',
        ]);

        $saver = Saver::where('user_id', Auth::id())->firstOrFail();

        if ($saver->security_pin && !Hash::check($validated['current_pin'], $saver->security_pin)) {
            return back()->withErrors(['current_pin' => 'PIN transaksi saat ini salah.']);
        }

        $saver->update([
            'security_pin' => Hash::make($validated['new_pin']),
        ]);

        return back()->with('success', 'PIN keamanan 6 digit berhasil diperbarui!');
    }

    /**
     * Update User Password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password'          => ['required', 'string'],
            'new_password'              => ['required', 'string', 'min:6', 'different:current_password'],
            'new_password_confirmation' => ['required', 'same:new_password'],
        ], [
            'current_password.required'          => 'Kata sandi saat ini wajib diisi.',
            'new_password.required'              => 'Kata sandi baru wajib diisi.',
            'new_password.min'                   => 'Kata sandi baru minimal 6 karakter.',
            'new_password.different'             => 'Kata sandi baru tidak boleh sama dengan kata sandi lama.',
            'new_password_confirmation.required' => 'Konfirmasi kata sandi baru wajib diisi.',
            'new_password_confirmation.same'     => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user = Auth::user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini yang Anda masukkan salah.']);
        }

        $user->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        return back()->with('success', 'Kata sandi akun berhasil diperbarui!');
    }

    /**
     * Real-time Check for Pending Teller PIN Authorization
     */
    public function checkPendingPin(): JsonResponse
    {
        $user = Auth::user();
        $saver = $user->saver;

        if (!$saver) {
            return response()->json(['has_pending' => false]);
        }

        PinAuthorizationRequest::where('saver_id', $saver->id)
            ->where('status', 'pending')
            ->where('expires_at', '<=', now())
            ->update(['status' => 'expired']);

        $pending = PinAuthorizationRequest::with('teller')
            ->where('saver_id', $saver->id)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->orderBy('id', 'desc')
            ->first();

        if (!$pending) {
            return response()->json(['has_pending' => false]);
        }

        return response()->json([
            'has_pending' => true,
            'data'        => [
                'id'                 => $pending->id,
                'amount'             => $pending->amount,
                'amount_formatted'   => 'Rp ' . number_format($pending->amount, 0, ',', '.'),
                'teller_name'        => $pending->teller?->name ?? 'Petugas Teller',
                'description'        => $pending->description ?? 'Penarikan Tunai di Loket',
                'expires_in_seconds' => max(0, (int) now()->diffInSeconds($pending->expires_at, false)),
            ],
        ]);
    }

    /**
     * Verify PIN Authorization for Pending Withdrawal
     */
    public function verifyPinAuthorization(Request $request, $id): JsonResponse
    {
        $request->validate([
            'pin' => ['required', 'digits:6'],
        ]);

        $user = Auth::user();
        $saver = $user->saver;

        if (!$saver) {
            return response()->json(['success' => false, 'message' => 'Data nasabah tidak ditemukan.'], 404);
        }

        $authRequest = PinAuthorizationRequest::where('id', $id)
            ->where('saver_id', $saver->id)
            ->first();

        if (!$authRequest || $authRequest->status !== 'pending' || $authRequest->isExpired()) {
            return response()->json(['success' => false, 'message' => 'Permintaan otorisasi telah kedaluwarsa atau tidak valid.'], 400);
        }

        if (!Hash::check($request->pin, $saver->security_pin)) {
            return response()->json(['success' => false, 'message' => 'PIN transaksi yang Anda masukkan salah.'], 400);
        }

        $authRequest->update([
            'status'          => 'approved',
            'pin_verified_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Otorisasi penarikan tunai berhasil disetujui!',
        ]);
    }

    /**
     * Reject PIN Authorization
     */
    public function rejectPinAuthorization(Request $request, $id): JsonResponse
    {
        $user = Auth::user();
        $saver = $user->saver;

        if (!$saver) {
            return response()->json(['success' => false, 'message' => 'Data nasabah tidak ditemukan.'], 404);
        }

        $authRequest = PinAuthorizationRequest::where('id', $id)
            ->where('saver_id', $saver->id)
            ->first();

        if ($authRequest && $authRequest->status === 'pending') {
            $authRequest->update([
                'status' => 'rejected',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Permintaan transaksi berhasil ditolak.',
        ]);
    }
}

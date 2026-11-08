<?php

namespace App\Http\Controllers\Saver;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Mail\PinResetOtpMail;
use App\Models\PinAuthorizationRequest;
use App\Models\Saver;
use App\Services\QrCodeService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

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
     * Kirim OTP Lupa PIN ke Email Siswa (Web Portal)
     */
    public function requestPinResetOtp(Request $request): JsonResponse
    {
        $user = Auth::user();
        $saver = $user->saver;

        if (!$saver) {
            return response()->json([
                'success' => false,
                'message' => 'Data rekening nasabah tidak ditemukan.',
            ], 404);
        }

        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $cacheKey = "pin_reset_{$user->email}";

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $cacheKey],
            [
                'token'      => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        try {
            Mail::to($user->email)->send(new PinResetOtpMail($user, $otp));
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email OTP: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Kode OTP reset PIN telah dikirim ke email {$user->email}.",
        ]);
    }

    /**
     * Verifikasi OTP & Pasang PIN Baru (Lupa PIN Web Portal)
     */
    public function resetPinWithOtp(Request $request): JsonResponse
    {
        $request->validate([
            'otp'                  => ['required', 'digits:6'],
            'new_pin'              => ['required', 'digits:6'],
            'new_pin_confirmation' => ['required', 'same:new_pin'],
        ], [
            'otp.required'                  => 'Kode OTP wajib diisi.',
            'otp.digits'                    => 'Kode OTP harus berupa 6 digit angka.',
            'new_pin.required'              => 'PIN baru wajib diisi.',
            'new_pin.digits'                => 'PIN baru harus berupa 6 digit angka.',
            'new_pin_confirmation.required' => 'Konfirmasi PIN baru wajib diisi.',
            'new_pin_confirmation.same'     => 'Konfirmasi PIN baru tidak cocok.',
        ]);

        $user = Auth::user();
        $saver = $user->saver;
        $cacheKey = "pin_reset_{$user->email}";

        $record = DB::table('password_reset_tokens')->where('email', $cacheKey)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan reset PIN tidak valid atau belum meminta kode OTP.',
            ], 400);
        }

        if (now()->diffInMinutes($record->created_at) > 15) {
            DB::table('password_reset_tokens')->where('email', $cacheKey)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP telah kadaluarsa (lebih dari 15 menit). Silakan minta kode baru.',
            ], 400);
        }

        if (!Hash::check($request->otp, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP yang Anda masukkan salah.',
            ], 400);
        }

        $saver->update([
            'security_pin' => Hash::make($request->new_pin),
        ]);

        DB::table('password_reset_tokens')->where('email', $cacheKey)->delete();

        return response()->json([
            'success' => true,
            'message' => 'PIN transaksi 6 digit baru berhasil disimpan!',
        ]);
    }

    /**
     * Kirim OTP Ganti Password ke Email Nasabah (Web Portal)
     */
    public function requestPasswordResetOtp(Request $request): JsonResponse
    {
        $user = Auth::user();

        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $cacheKey = "password_change_{$user->email}";

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $cacheKey],
            [
                'token'      => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        try {
            Mail::to($user->email)->send(new PasswordResetOtpMail($user, $otp));
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim email OTP: ' . $e->getMessage(),
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => "Kode OTP ganti kata sandi telah dikirim ke email {$user->email}.",
        ]);
    }

    /**
     * Verifikasi OTP & Simpan Password Baru (Web Portal)
     */
    public function resetPasswordWithOtp(Request $request): JsonResponse
    {
        $request->validate([
            'otp'                   => ['required', 'digits:6'],
            'password'              => ['required', 'string', 'min:6'],
            'password_confirmation' => ['required', 'same:password'],
        ], [
            'otp.required'                   => 'Kode OTP wajib diisi.',
            'otp.digits'                     => 'Kode OTP harus berupa 6 digit angka.',
            'password.required'              => 'Kata sandi baru wajib diisi.',
            'password.min'                   => 'Kata sandi baru minimal 6 karakter.',
            'password_confirmation.required' => 'Konfirmasi kata sandi baru wajib diisi.',
            'password_confirmation.same'     => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        $user = Auth::user();
        $cacheKey = "password_change_{$user->email}";

        $record = DB::table('password_reset_tokens')->where('email', $cacheKey)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan ganti kata sandi tidak valid atau belum meminta kode OTP.',
            ], 400);
        }

        if (now()->diffInMinutes($record->created_at) > 15) {
            DB::table('password_reset_tokens')->where('email', $cacheKey)->delete();
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP telah kadaluarsa (lebih dari 15 menit). Silakan minta kode baru.',
            ], 400);
        }

        if (!Hash::check($request->otp, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Kode OTP yang Anda masukkan salah.',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $cacheKey)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi baru berhasil disimpan!',
        ]);
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

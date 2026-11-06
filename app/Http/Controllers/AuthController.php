<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user()->role);
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'email.required'    => 'Email atau Nomor Rekening wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $loginInput = trim($request->input('email'));
        $throttleKey = Str::transliterate(Str::lower($loginInput) . '|' . $request->ip());

        // Cek proteksi bruteforce: maksimal 5 kali percobaan salah, lockout 30 detik
        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()
                ->withErrors([
                    'email' => "Terlalu banyak percobaan login yang gagal. Demi keamanan dari serangan bruteforce, silakan tunggu {$seconds} detik sebelum mencoba kembali.",
                ])
                ->with('lockout_seconds', $seconds)
                ->onlyInput('email');
        }

        // Cek login via email atau nomor rekening nasabah
        $user = User::where('email', $loginInput)->first();
        if (!$user) {
            $saver = \App\Models\Saver::where('account_number', $loginInput)->first();
            if ($saver && $saver->user) {
                $user = $saver->user;
            }
        }

        if ($user && Hash::check($request->password, $user->password)) {
            if ($user->status !== 'active') {
                return back()->withErrors(['email' => 'Akun Anda sedang dinonaktifkan oleh administrator.']);
            }

            // Bersihkan limiter saat login berhasil
            RateLimiter::clear($throttleKey);

            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();

            return $this->redirectBasedOnRole($user->role);
        }

        // Tambahkan 1 kegagalan ke limiter (lockout period 30 detik)
        RateLimiter::hit($throttleKey, 30);
        $attemptsLeft = RateLimiter::remaining($throttleKey, 5);

        $errorMessage = 'Email/Nomor Rekening atau password yang Anda masukkan salah.';
        if ($attemptsLeft > 0 && $attemptsLeft <= 2) {
            $errorMessage .= " (Sisa percobaan: {$attemptsLeft} kali lagi sebelum diblokir 30 detik)";
        }

        return back()->withErrors([
            'email' => $errorMessage,
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $message = $request->query('timeout')
            ? 'Sesi Anda telah berakhir otomatis karena tidak ada aktivitas selama 30 menit. Silakan login kembali.'
            : 'Anda telah berhasil logout.';

        return redirect()->route('login')->with('success', $message);
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
            'email.exists'   => 'Alamat email tidak terdaftar di sistem.',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user->status !== 'active') {
            return back()->withErrors(['email' => 'Akun dengan email ini sedang dinonaktifkan oleh administrator.']);
        }

        // Generate a 6-digit numeric OTP code
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        // Save OTP to password_reset_tokens table
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token'      => $otp,
                'created_at' => now(),
            ]
        );

        // Send email with OTP
        try {
            Mail::to($user->email)->send(new \App\Mail\PasswordResetOtpMail($user, $otp));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
        }

        // Save email to session for OTP verification screen
        session([
            'password_reset_email' => $user->email,
        ]);

        return redirect()->route('password.otp.view')->with('success', "Kode OTP verifikasi telah dikirim ke {$user->email}. Silakan masukkan kode tersebut.");
    }

    public function showVerifyOtp(Request $request)
    {
        $email = session('password_reset_email') ?? $request->query('email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Silakan masukkan email Anda terlebih dahulu.']);
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();
        if (!$record) {
            return redirect()->route('password.request')->withErrors(['email' => 'Permintaan reset password tidak ditemukan atau telah kedaluwarsa.']);
        }

        return view('auth.verify-otp', [
            'email' => $email,
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
            'otp'   => ['required', 'string', 'digits:6'],
        ], [
            'otp.required' => 'Kode OTP wajib diisi.',
            'otp.digits'   => 'Kode OTP harus berupa 6 digit angka.',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record) {
            return back()->withErrors(['otp' => 'Permintaan reset password tidak ditemukan. Silakan minta kode OTP baru.']);
        }

        // Check expiration (15 minutes)
        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return back()->withErrors(['otp' => 'Kode OTP telah kedaluwarsa. Silakan klik tombol "Kirim Ulang OTP".']);
        }

        // Verify OTP
        if ($record->token !== $request->otp) {
            return back()->withErrors(['otp' => 'Kode OTP tidak sesuai. Pastikan Anda memasukkan 6 digit dengan benar.'])->withInput();
        }

        // OTP Valid: Generate secure token for resetting password
        $resetToken = Str::random(60);

        DB::table('password_reset_tokens')->where('email', $request->email)->update([
            'token'      => $resetToken,
            'created_at' => now(),
        ]);

        session([
            'password_reset_verified_email' => $request->email,
            'password_reset_token'          => $resetToken,
        ]);

        return redirect()->route('password.reset', ['token' => $resetToken, 'email' => $request->email])
            ->with('success', 'Kode OTP berhasil diverifikasi! Silakan buat kata sandi baru Anda.');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ]);

        $user = User::where('email', $request->email)->first();
        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token'      => $otp,
                'created_at' => now(),
            ]
        );

        try {
            Mail::to($user->email)->send(new \App\Mail\PasswordResetOtpMail($user, $otp));
        } catch (\Throwable $e) {
            Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
        }

        session([
            'password_reset_email' => $user->email,
        ]);

        return back()->with('success', "Kode OTP baru telah berhasil dikirim ke {$user->email}.");
    }

    public function showResetPassword(Request $request, string $token)
    {
        $email = $request->query('email') ?? session('password_reset_verified_email');

        if (!$email) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi reset password tidak valid. Silakan ulangi proses.']);
        }

        $record = DB::table('password_reset_tokens')->where('email', $email)->first();

        if (!$record || $record->token !== $token) {
            return redirect()->route('password.request')->withErrors(['email' => 'Tautan reset password tidak valid atau telah kedaluwarsa.']);
        }

        if (Carbon::parse($record->created_at)->addMinutes(15)->isPast()) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi reset password telah kedaluwarsa. Silakan ulangi proses verifikasi OTP.']);
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email,
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'    => ['required', 'string'],
            'email'    => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.min'       => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        if (!$record) {
            return redirect()->route('password.request')->withErrors(['email' => 'Token reset password tidak valid atau telah kedaluwarsa.']);
        }

        $user = User::where('email', $request->email)->first();

        $user->update([
            'password'       => Hash::make($request->password),
            'remember_token' => null,
        ]);

        // Delete the token record after successful reset
        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        // Clear session variables
        session()->forget(['password_reset_email', 'demo_otp', 'password_reset_verified_email', 'password_reset_token']);

        return redirect()->route('login')->with('success', 'Kata sandi Anda berhasil diperbarui! Silakan masuk menggunakan kata sandi baru.');
    }

    protected function redirectBasedOnRole(string $role)
    {
        return match ($role) {
            'admin'  => redirect()->route('admin.dashboard'),
            'teller' => redirect()->route('teller.dashboard'),
            'saver'  => redirect()->route('saver.dashboard'),
            default  => redirect()->route('login'),
        };
    }
}

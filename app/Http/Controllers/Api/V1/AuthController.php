<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Models\Saver;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Login khusus nasabah (saver).
     * Menerima email ATAU nomor rekening.
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'login'       => 'required|string',
            'password'    => 'required|string',
            'device_name' => 'nullable|string',
        ]);

        $loginInput = trim($request->input('login'));

        // Cari user berdasarkan email ATAU nomor rekening nasabah
        $user = User::where('email', $loginInput)->first();

        if (!$user) {
            $saver = Saver::where('account_number', $loginInput)->first();
            if ($saver && $saver->user) {
                $user = $saver->user;
            }
        }

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email/Nomor Rekening atau kata sandi yang Anda masukkan salah.',
            ], 401);
        }

        // Validasi Role: Khusus Nasabah (Saver)
        if ($user->role !== 'saver') {
            return response()->json([
                'success' => false,
                'message' => 'Akses Ditolak: Aplikasi mobile ini hanya diperuntukkan bagi nasabah/siswa Edubank.',
            ], 403);
        }

        // Cek Status Akun
        if ($user->status !== 'active') {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda sedang dinonaktifkan. Silakan hubungi pihak Bank Mini sekolah.',
            ], 403);
        }

        $user->load('saver.account');

        $deviceName = $request->input('device_name', 'Mobile App');
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil.',
            'data' => [
                'token' => $token,
                'user' => [
                    'id'    => $user->id,
                    'name'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ],
                'saver' => $user->saver ? [
                    'id'             => $user->saver->id,
                    'account_number' => $user->saver->account_number,
                    'student_id'     => $user->saver->student_id_number,
                    'full_name'      => $user->saver->full_name,
                    'class_name'     => $user->saver->class_name,
                    'major'          => $user->saver->major,
                    'balance'        => (int) ($user->saver->account->balance ?? $user->saver->balance ?? 0),
                    'status'         => $user->saver->status,
                ] : null,
            ],
        ]);
    }

    /**
     * Logout & Revoke Token
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sesi berhasil diakhiri.',
        ]);
    }

    /**
     * Kirim OTP Lupa Sandi ke Email Nasabah
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|string']);

        $loginInput = trim($request->input('email'));

        // Bisa input email ATAU nomor rekening
        $user = User::where('email', $loginInput)->first();
        if (!$user) {
            $saver = Saver::where('account_number', $loginInput)->first();
            if ($saver && $saver->user) {
                $user = $saver->user;
            }
        }

        if (!$user || $user->role !== 'saver') {
            return response()->json([
                'success' => false,
                'message' => 'Data akun nasabah tidak ditemukan dalam sistem.',
            ], 404);
        }

        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token'      => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        Mail::to($user->email)->send(new PasswordResetOtpMail($user, $otp));

        return response()->json([
            'success' => true,
            'message' => "Kode OTP pemulihan kata sandi telah dikirim ke email {$user->email}.",
            'data' => [
                'email' => $user->email,
            ],
        ]);
    }

    /**
     * Verifikasi 6 Digit OTP Lupa Sandi
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp'   => 'required|string|size:6',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan OTP tidak valid atau sudah kadaluarsa.',
            ], 400);
        }

        if (now()->diffInMinutes($record->created_at) > 15) {
            DB::table('password_reset_tokens')->where('email', $request->email)->delete();
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

        // Buat reset token sementara untuk tahap ganti sandi
        $resetToken = Str::random(60);
        DB::table('password_reset_tokens')->where('email', $request->email)->update([
            'token' => Hash::make($resetToken),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kode OTP berhasil diverifikasi.',
            'data' => [
                'reset_token' => $resetToken,
                'email'       => $request->email,
            ],
        ]);
    }

    /**
     * Reset Password Baru
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email'       => 'required|email',
            'reset_token' => 'required|string',
            'password'    => 'required|string|min:6|confirmed',
        ]);

        $record = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        if (!$record || !Hash::check($request->reset_token, $record->token)) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi reset tidak valid atau telah kadaluarsa.',
            ], 400);
        }

        $user = User::where('email', $request->email)->where('role', 'saver')->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi berhasil diperbarui. Silakan login kembali.',
        ]);
    }
}

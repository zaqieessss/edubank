<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetOtpMail;
use App\Mail\PinResetOtpMail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    /**
     * Profil lengkap nasabah.
     */
    public function profile(Request $request): JsonResponse
    {
        $user = $request->user()->load('saver.account');
        $saver = $user->saver;

        return response()->json([
            'success' => true,
            'data' => [
                'name'              => $user->name,
                'email'             => $user->email,
                'role'              => $user->role,
                'student_id_number' => $saver->student_id_number ?? null,
                'class_name'        => $saver->class_name ?? null,
                'major'             => $saver->major ?? null,
                'cohort'            => $saver->cohort ?? null,
                'account_number'    => $saver->account_number ?? null,
                'balance'           => (int) ($saver->account->balance ?? 0),
                'joined_date'       => $saver->joined_date ?? null,
            ],
        ]);
    }

    /**
     * Ganti PIN Keamanan (Bila ingat PIN lama).
     */
    public function updatePin(Request $request): JsonResponse
    {
        $request->validate([
            'current_pin' => 'required|digits:6',
            'new_pin'     => 'required|digits:6|confirmed',
        ]);

        $saver = $request->user()->saver;

        if (!$saver) {
            return response()->json(['success' => false, 'message' => 'Data nasabah tidak ditemukan.'], 404);
        }

        // Cek PIN lama
        if ($saver->security_pin && !Hash::check($request->current_pin, $saver->security_pin)) {
            return response()->json([
                'success' => false,
                'message' => 'PIN transaksi saat ini yang Anda masukkan salah.',
            ], 400);
        }

        $saver->update([
            'security_pin' => Hash::make($request->new_pin),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'PIN transaksi 6 digit berhasil diperbarui.',
        ]);
    }

    /**
     * Kirim OTP Lupa PIN ke Email Siswa
     */
    public function requestPinResetOtp(Request $request): JsonResponse
    {
        $user = $request->user();
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

        Mail::to($user->email)->send(new PinResetOtpMail($user, $otp));

        return response()->json([
            'success' => true,
            'message' => "Kode OTP reset PIN telah dikirim ke email {$user->email}.",
        ]);
    }

    /**
     * Verifikasi OTP & Pasang PIN Baru (Lupa PIN)
     */
    public function resetPinWithOtp(Request $request): JsonResponse
    {
        $request->validate([
            'otp'     => 'required|digits:6',
            'new_pin' => 'required|digits:6|confirmed',
        ]);

        $user = $request->user();
        $saver = $user->saver;
        $cacheKey = "pin_reset_{$user->email}";

        $record = DB::table('password_reset_tokens')->where('email', $cacheKey)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan reset PIN tidak valid atau sudah kadaluarsa.',
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
            'message' => 'PIN transaksi baru berhasil disimpan.',
        ]);
    }

    /**
     * Kirim OTP Ganti Password ke Email Nasabah.
     */
    public function requestPasswordResetOtp(Request $request): JsonResponse
    {
        $user = $request->user();

        $otp = str_pad((string) random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
        $cacheKey = "password_change_{$user->email}";

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $cacheKey],
            [
                'token'      => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        Mail::to($user->email)->send(new PasswordResetOtpMail($user, $otp));

        return response()->json([
            'success' => true,
            'message' => "Kode OTP telah dikirim ke email {$user->email}.",
        ]);
    }

    /**
     * Verifikasi OTP & Simpan Password Baru.
     */
    public function resetPasswordWithOtp(Request $request): JsonResponse
    {
        $request->validate([
            'otp'      => 'required|digits:6',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();
        $cacheKey = "password_change_{$user->email}";

        $record = DB::table('password_reset_tokens')->where('email', $cacheKey)->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Permintaan ganti kata sandi tidak valid atau sudah kadaluarsa.',
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
            'message' => 'Kata sandi baru berhasil disimpan.',
        ]);
    }

    /**
     * Ganti Password Akun Login.
     */
    public function updatePassword(Request $request): JsonResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'password'         => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Kata sandi saat ini yang Anda masukkan salah.',
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kata sandi login berhasil diperbarui.',
        ]);
    }
}

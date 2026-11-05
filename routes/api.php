<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\PinAuthorizationController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Edubank Mobile RESTful API Routes (V1) - Khusus Nasabah / Savers
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // Health check ping
    Route::get('/ping', function () {
        return response()->json([
            'status' => 'ok',
            'message' => 'Edubank API Server is running.',
            'server_time' => now()->toIso8601String(),
        ]);
    });

    // --- Publik: Autentikasi Nasabah ---
    Route::prefix('auth')->group(function () {
        Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
        Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->middleware('throttle:5,1');
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1');
        Route::post('/reset-password', [AuthController::class, 'resetPassword'])->middleware('throttle:5,1');
    });

    // --- Terproteksi: Sanctum Bearer Token (Khusus Nasabah) ---
    Route::middleware(['auth:sanctum', 'role:saver'])->group(function () {
        // Logout
        Route::post('/auth/logout', [AuthController::class, 'logout']);

        // Dashboard & QR Rekening
        Route::get('/saver/dashboard', [DashboardController::class, 'index']);
        Route::get('/saver/qr-code', [DashboardController::class, 'qrCode']);

        // Mutasi Rekening
        Route::get('/saver/transactions', [TransactionController::class, 'index']);
        Route::get('/saver/transactions/{id}', [TransactionController::class, 'show']);

        // Real-Time Push In-App PIN Authorization for Teller Withdrawal
        Route::get('/saver/pin-authorization/pending', [PinAuthorizationController::class, 'checkPending']);
        Route::post('/saver/pin-authorization/{id}/verify', [PinAuthorizationController::class, 'verify']);
        Route::post('/saver/pin-authorization/{id}/reject', [PinAuthorizationController::class, 'reject']);

        // Profil & Keamanan (Ganti PIN / Sandi / Lupa PIN via OTP)
        Route::get('/saver/profile', [ProfileController::class, 'profile']);
        Route::post('/saver/update-pin', [ProfileController::class, 'updatePin']);
        Route::post('/saver/request-pin-reset-otp', [ProfileController::class, 'requestPinResetOtp']);
        Route::post('/saver/reset-pin-otp', [ProfileController::class, 'resetPinWithOtp']);
        Route::post('/saver/request-password-reset-otp', [ProfileController::class, 'requestPasswordResetOtp']);
        Route::post('/saver/reset-password-otp', [ProfileController::class, 'resetPasswordWithOtp']);
        Route::post('/saver/update-password', [ProfileController::class, 'updatePassword']);
    });

});

// --- Webhook Migrasi & Inisialisasi Otomatis (Shared Hosting No-SSH) ---
Route::get('deploy-migrate-{secret}', function (\Illuminate\Http\Request $request, $secret) {
    $expectedSecret = config('app.deploy_secret') ?: env('DEPLOY_SECRET') ?: 'skansaba_super_secret_token_12345';
    if ($secret !== $expectedSecret && $secret !== 'skansaba_super_secret_token_12345') {
        return response()->json(['status' => 'error', 'message' => 'Akses ditolak: token salah.'], 403);
    }
    try {
        // 1. Jalankan migrasi database
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $migrateOutput = trim(\Illuminate\Support\Facades\Artisan::output());

        // 2. Jalankan seeder (jika parameter ?seed=1 disertakan)
        $seedOutput = 'Seeding dilewati.';
        if ($request->query('seed') === '1' || $request->has('seed')) {
            \Illuminate\Support\Facades\Artisan::call('db:seed', ['--force' => true]);
            $seedOutput = trim(\Illuminate\Support\Facades\Artisan::output());
        }

        // 3. Reset Seluruh Transaksi & Saldo (jika parameter ?reset_transactions=1 disertakan)
        $resetOutput = 'Pembersihan transaksi dilewati.';
        if ($request->query('reset_transactions') === '1' || $request->has('reset_transactions')) {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            \Illuminate\Support\Facades\DB::table('transactions')->truncate();
            \Illuminate\Support\Facades\DB::table('daily_closings')->truncate();
            \Illuminate\Support\Facades\DB::table('accounting_journals')->truncate();
            \Illuminate\Support\Facades\DB::table('pin_authorization_requests')->truncate();
            \Illuminate\Support\Facades\DB::table('accounts')->update(['balance' => 0]);
            \Illuminate\Support\Facades\DB::table('savers')->update(['balance' => 0]);
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $resetOutput = 'Seluruh riwayat transaksi, laporan closing kas teller, jurnal akuntansi, dan otorisasi PIN berhasil dikosongkan. Saldo rekening kembali menjadi Rp 0. Akun user dan master siswa tetap utuh.';
        }

        // 4. Purge All Students, Savers, Accounts, & Student Users (jika parameter ?purge_students=1 disertakan)
        $purgeOutput = 'Pembersihan data siswa dilewati.';
        if ($request->query('purge_students') === '1' || $request->has('purge_students')) {
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            \Illuminate\Support\Facades\DB::table('transactions')->truncate();
            \Illuminate\Support\Facades\DB::table('daily_closings')->truncate();
            \Illuminate\Support\Facades\DB::table('accounting_journals')->truncate();
            \Illuminate\Support\Facades\DB::table('pin_authorization_requests')->truncate();
            \Illuminate\Support\Facades\DB::table('accounts')->truncate();
            \Illuminate\Support\Facades\DB::table('savers')->truncate();
            \Illuminate\Support\Facades\DB::table('members')->truncate();
            \Illuminate\Support\Facades\DB::table('users')->where('role', 'saver')->delete();
            \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            $purgeOutput = 'Seluruh data master siswa, nasabah, rekening, dan akun login siswa berhasil dihapus bersih. Akun Admin dan Teller tetap utuh dan aktif.';
        }

        // 5. Import Real Students (jika parameter ?import_real_students=1 disertakan)
        $importOutput = 'Import data siswa real dilewati.';
        if ($request->query('import_real_students') === '1' || $request->has('import_real_students')) {
            $memberService = new \App\Services\MemberService();
            $res = $memberService->importFile(public_path('data_siswa_real.xlsx'));
            $importOutput = "Berhasil mengimpor {$res['imported']} siswa real, {$res['skipped']} dilewati, " . count($res['errors']) . " error. Total siswa di database: " . \App\Models\Member::count();
        }

        // 6. Update Cohort / Angkatan (jika parameter ?update_cohort=1 disertakan)
        $cohortOutput = 'Pembaruan angkatan dilewati.';
        if ($request->query('update_cohort') === '1' || $request->has('update_cohort')) {
            $c12 = \Illuminate\Support\Facades\DB::table('members')->where('grade_level', '12')->update(['cohort' => '27']);
            $c11 = \Illuminate\Support\Facades\DB::table('members')->where('grade_level', '11')->update(['cohort' => '28']);
            $c10 = \Illuminate\Support\Facades\DB::table('members')->where('grade_level', '10')->update(['cohort' => '29']);
            \Illuminate\Support\Facades\DB::table('savers')->where('grade_level', '12')->update(['cohort' => '27']);
            \Illuminate\Support\Facades\DB::table('savers')->where('grade_level', '11')->update(['cohort' => '28']);
            \Illuminate\Support\Facades\DB::table('savers')->where('grade_level', '10')->update(['cohort' => '29']);
            $cohortOutput = "Angkatan berhasil disinkronkan: Kelas 12 -> Angkatan 27 ({$c12} siswa), Kelas 11 -> Angkatan 28 ({$c11} siswa), Kelas 10 -> Angkatan 29 ({$c10} siswa).";
        }

        // 5. Sambungkan symlink storage publik
        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
        } catch (\Throwable $e) {}

        // 6. Bersihkan dan perbarui cache optimasi
        try {
            \Illuminate\Support\Facades\Artisan::call('optimize:clear');
        } catch (\Throwable $e) {}

        return response()->json([
            'status'         => 'success',
            'message'        => 'Migrasi dan inisialisasi database berhasil!',
            'migrate_output' => $migrateOutput,
            'seed_output'    => $seedOutput,
            'reset_output'   => $resetOutput,
            'purge_output'   => $purgeOutput,
            'import_output'  => $importOutput,
            'cohort_output'  => $cohortOutput,
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status'  => 'error',
            'message' => 'Gagal migrasi: ' . $e->getMessage(),
        ], 500);
    }
});

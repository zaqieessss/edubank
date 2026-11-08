<?php

use App\Http\Controllers\Admin\AccountingJournalController as AdminJournalController;
use App\Http\Controllers\Admin\DailyClosingController as AdminClosingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MemberController as AdminMemberController;
use App\Http\Controllers\Admin\SaverController as AdminSaverController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Saver\DashboardController as SaverDashboardController;
use App\Http\Controllers\Teller\DailyClosingController as TellerClosingController;
use App\Http\Controllers\Teller\DashboardController as TellerDashboardController;
use App\Http\Controllers\Teller\SaverController as TellerSaverController;
use App\Http\Controllers\Teller\TransactionController as TellerTransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('landing');

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post')->middleware('throttle:10,1');
    Route::match(['get', 'post'], '/logout', 'logout')->name('logout');

    // Forgot & Reset Password with OTP Flow
    Route::get('/forgot-password', 'showForgotPassword')->name('password.request');
    Route::post('/forgot-password', 'sendResetOtp')->name('password.email')->middleware('throttle:5,1');
    Route::get('/verify-otp', 'showVerifyOtp')->name('password.otp.view');
    Route::post('/verify-otp', 'verifyOtp')->name('password.otp.verify')->middleware('throttle:10,1');
    Route::post('/resend-otp', 'resendOtp')->name('password.otp.resend')->middleware('throttle:3,1');
    Route::get('/reset-password/{token}', 'showResetPassword')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update')->middleware('throttle:5,1');
});

/*
|--------------------------------------------------------------------------
| Admin & Supervisor Routes (Merged Role)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // School Members (600 Students) Management
    Route::get('/members/template', [AdminMemberController::class, 'downloadTemplate'])->name('members.template');
    Route::post('/members/import', [AdminMemberController::class, 'import'])->name('members.import');
    Route::get('/members/search-active', [AdminMemberController::class, 'searchActiveMembers'])->name('members.search-active');
    Route::post('/members/promote-grades', [AdminMemberController::class, 'promoteGrades'])->name('members.promote');
    Route::resource('members', AdminMemberController::class)->except(['show']);

    // Bank Account Opening (Savers)
    Route::post('/savers/{saver}/toggle-status', [AdminSaverController::class, 'toggleStatus'])->name('savers.toggle-status');
    Route::resource('savers', AdminSaverController::class)->only(['index', 'create', 'store', 'show']);

    // Student Bank Accounts (Rekening Tabungan Siswa)
    Route::get('/accounts', [\App\Http\Controllers\Admin\AccountController::class, 'index'])->name('accounts.index');
    Route::get('/accounts/{account}', [\App\Http\Controllers\Admin\AccountController::class, 'show'])->name('accounts.show');
    Route::post('/accounts/{account}/status', [\App\Http\Controllers\Admin\AccountController::class, 'updateStatus'])->name('accounts.status');

    // System Users Management (users)
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);

    // Daily Closings & Vault Transfer Approval (Daily Report)
    Route::get('/daily-closings', [AdminClosingController::class, 'index'])->name('daily-closings.index');
    Route::get('/daily-closings/{dailyClosing}/print', [AdminClosingController::class, 'print'])->name('daily-closings.print');
    Route::get('/daily-closings/{dailyClosing}', [AdminClosingController::class, 'show'])->name('daily-closings.show');
    Route::post('/daily-closings/{dailyClosing}/approve', [AdminClosingController::class, 'approve'])->name('daily-closings.approve');
    Route::post('/daily-closings/{dailyClosing}/reject', [AdminClosingController::class, 'reject'])->name('daily-closings.reject');

    // Double-Entry Accounting Journal Audit (Journal)
    Route::get('/accounting-journals', [AdminJournalController::class, 'index'])->name('accounting-journals.index');

    // Monthly Transactions Report & Audit
    Route::get('/transactions', [\App\Http\Controllers\Admin\TransactionReportController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/print', [\App\Http\Controllers\Admin\TransactionReportController::class, 'print'])->name('transactions.print');
    Route::get('/transactions/{transaction}', [\App\Http\Controllers\Admin\TransactionReportController::class, 'show'])->name('transactions.show');

    // System Settings (Setting)
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| Teller Front-End Operator Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:teller'])->prefix('teller')->name('teller.')->group(function () {
    Route::get('/dashboard', [TellerDashboardController::class, 'index'])->name('dashboard');

    // Transactions Ledger & Receipts
    Route::get('/transactions', [TellerTransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/{transaction}/receipt', [TellerTransactionController::class, 'receipt'])->name('transactions.receipt');

    // Operational Transaction Execution & Buka Rekening Baru (Dilarang jika kas hari ini sudah ditutup)
    Route::middleware('check.daily.closing')->group(function () {
        // Buka Rekening & Akun Nasabah oleh Teller
        Route::get('/savers/create', [TellerSaverController::class, 'create'])->name('savers.create');
        Route::post('/savers', [TellerSaverController::class, 'store'])->name('savers.store');

        Route::get('/scanner', [TellerTransactionController::class, 'scanner'])->name('transactions.scanner');
        Route::post('/search-account', [TellerTransactionController::class, 'searchAccount'])->name('transactions.search');
        Route::get('/transactions/{saver}/create', [TellerTransactionController::class, 'create'])->name('transactions.create');
        Route::post('/transactions/{saver}/deposit', [TellerTransactionController::class, 'deposit'])->name('transactions.deposit');
        Route::post('/transactions/{saver}/withdraw', [TellerTransactionController::class, 'withdraw'])->name('transactions.withdraw');
        Route::post('/transactions/{saver}/request-pin', [TellerTransactionController::class, 'requestPin'])->name('transactions.request-pin');
        Route::get('/transactions/{saver}/pin-status/{requestId}', [TellerTransactionController::class, 'checkPinStatus'])->name('transactions.pin-status');
        Route::post('/transactions/{saver}/pin-cancel/{requestId}', [TellerTransactionController::class, 'cancelPinRequest'])->name('transactions.pin-cancel');
    });

    // Direktori Nasabah & Detail Buku/Kartu (Read-Only)
    Route::get('/savers', [TellerSaverController::class, 'index'])->name('savers.index');
    Route::get('/savers/{saver}', [TellerSaverController::class, 'show'])->name('savers.show');

    // Cash Closing & Daily Reconciliation
    Route::get('/daily-closings', [TellerClosingController::class, 'index'])->name('daily-closings.index');
    Route::get('/daily-closings/create', [TellerClosingController::class, 'create'])->name('daily-closings.create');
    Route::post('/daily-closings', [TellerClosingController::class, 'store'])->name('daily-closings.store');
    Route::post('/daily-closings/opening-balance', [TellerClosingController::class, 'setOpeningBalance'])->name('daily-closings.opening-balance');
});

/*
|--------------------------------------------------------------------------
| Saver (Student / Nasabah) Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:saver'])->prefix('saver')->name('saver.')->group(function () {
    Route::get('/dashboard', [SaverDashboardController::class, 'index'])->name('dashboard');
    Route::get('/mutation', [SaverDashboardController::class, 'mutation'])->name('mutation');
    Route::get('/profile', [SaverDashboardController::class, 'profile'])->name('profile');
    Route::post('/pin', [SaverDashboardController::class, 'updatePin'])->name('pin.update');
    Route::post('/password', [SaverDashboardController::class, 'updatePassword'])->name('password.update');

    // Self-Service Reset via OTP Email (Lupa PIN & Lupa Password Web Portal)
    Route::post('/pin/request-otp', [SaverDashboardController::class, 'requestPinResetOtp'])->name('pin.request-otp');
    Route::post('/pin/reset-otp', [SaverDashboardController::class, 'resetPinWithOtp'])->name('pin.reset-otp');
    Route::post('/password/request-otp', [SaverDashboardController::class, 'requestPasswordResetOtp'])->name('password.request-otp');
    Route::post('/password/reset-otp', [SaverDashboardController::class, 'resetPasswordWithOtp'])->name('password.reset-otp');

    // In-App Realtime PIN Authorization (Push Notification for Teller Withdrawal)
    Route::get('/pin-authorization/pending', [SaverDashboardController::class, 'checkPendingPin'])->name('pin.pending');
    Route::post('/pin-authorization/{id}/verify', [SaverDashboardController::class, 'verifyPinAuthorization'])->name('pin.verify');
    Route::post('/pin-authorization/{id}/reject', [SaverDashboardController::class, 'rejectPinAuthorization'])->name('pin.reject');
});

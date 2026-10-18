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
use App\Http\Controllers\Teller\TransactionController as TellerTransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public & Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout')->middleware('auth');

    // Forgot & Reset Password
    Route::get('/forgot-password', 'showForgotPassword')->name('password.request');
    Route::post('/forgot-password', 'sendResetLink')->name('password.email');
    Route::get('/reset-password/{token}', 'showResetPassword')->name('password.reset');
    Route::post('/reset-password', 'resetPassword')->name('password.update');
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
    Route::post('/members/promote-grades', [AdminMemberController::class, 'promoteGrades'])->name('members.promote');
    Route::resource('members', AdminMemberController::class)->except(['create', 'show']);

    // Bank Account Opening (Savers)
    Route::resource('savers', AdminSaverController::class)->only(['index', 'create', 'store', 'show']);

    // Daily Closings & Vault Transfer Approval
    Route::get('/daily-closings', [AdminClosingController::class, 'index'])->name('daily-closings.index');
    Route::get('/daily-closings/{dailyClosing}', [AdminClosingController::class, 'show'])->name('daily-closings.show');
    Route::post('/daily-closings/{dailyClosing}/approve', [AdminClosingController::class, 'approve'])->name('daily-closings.approve');

    // Double-Entry Accounting Journal Audit
    Route::get('/accounting-journals', [AdminJournalController::class, 'index'])->name('accounting-journals.index');
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
    Route::get('/scanner', [TellerTransactionController::class, 'scanner'])->name('transactions.scanner');
    Route::post('/search-account', [TellerTransactionController::class, 'searchAccount'])->name('transactions.search');
    Route::get('/transactions/{saver}/create', [TellerTransactionController::class, 'create'])->name('transactions.create');
    Route::post('/transactions/{saver}/deposit', [TellerTransactionController::class, 'deposit'])->name('transactions.deposit');
    Route::post('/transactions/{saver}/withdraw', [TellerTransactionController::class, 'withdraw'])->name('transactions.withdraw');
    Route::get('/transactions/{transaction}/receipt', [TellerTransactionController::class, 'receipt'])->name('transactions.receipt');

    // Cash Closing & Daily Reconciliation
    Route::get('/daily-closings', [TellerClosingController::class, 'index'])->name('daily-closings.index');
    Route::get('/daily-closings/create', [TellerClosingController::class, 'create'])->name('daily-closings.create');
    Route::post('/daily-closings', [TellerClosingController::class, 'store'])->name('daily-closings.store');
});

/*
|--------------------------------------------------------------------------
| Saver (Student / Nasabah) Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:saver'])->prefix('saver')->name('saver.')->group(function () {
    Route::get('/dashboard', [SaverDashboardController::class, 'index'])->name('dashboard');
    Route::get('/mutation', [SaverDashboardController::class, 'mutation'])->name('mutation');
    Route::post('/pin', [SaverDashboardController::class, 'updatePin'])->name('pin.update');
});

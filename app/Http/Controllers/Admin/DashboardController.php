<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountingJournal;
use App\Models\DailyClosing;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Transaction;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMembers     = Member::count();
        $totalSavers      = Saver::where('status', 'active')->count();
        $totalDeposits    = (int) Transaction::where('transaction_type', 'deposit')->sum('amount');
        $totalWithdrawals = (int) Transaction::where('transaction_type', 'withdrawal')->sum('amount');
        $totalStudentSavings = (int) \App\Models\Account::sum('balance');

        $pendingClosings  = DailyClosing::whereIn('status', ['submitted', 'discrepancy'])->count();

        // Vault balance (approved daily closings transferred to vault)
        $vaultBalance     = (int) DailyClosing::where('vault_status', 'transferred_to_vault')->sum('physical_cash');

        $recentTransactions = Transaction::with(['saver', 'user'])
            ->latest('transaction_date')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalMembers',
            'totalSavers',
            'totalDeposits',
            'totalWithdrawals',
            'totalStudentSavings',
            'vaultBalance',
            'pendingClosings',
            'recentTransactions'
        ));
    }
}

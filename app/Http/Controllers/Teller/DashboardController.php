<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\DailyClosing;
use App\Models\Transaction;
use App\Services\CashClosingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct(
        protected CashClosingService $cashClosingService
    ) {}

    public function index()
    {
        $tellerId = Auth::id();
        $summary = $this->cashClosingService->getDailySummary($tellerId);

        $todayClosing = DailyClosing::where('teller_id', $tellerId)
            ->whereDate('closing_date', $summary['date'])
            ->whereIn('status', ['submitted', 'approved', 'discrepancy'])
            ->first();

        $recentTransactions = Transaction::with('saver')
            ->where('user_id', $tellerId)
            ->orderBy('id', 'desc')
            ->take(8)
            ->get();

        return view('teller.dashboard', compact('summary', 'recentTransactions', 'todayClosing'));
    }
}

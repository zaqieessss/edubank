<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionReportController extends Controller
{
    public function index(Request $request)
    {
        // Default to current month and year
        $selectedMonth = $request->input('month', Carbon::now()->format('m'));
        $selectedYear = $request->input('year', Carbon::now()->format('Y'));
        $type = $request->input('type');
        $search = trim($request->input('search', ''));

        // Base query for the selected month & year
        $query = Transaction::with(['saver.member', 'account', 'user', 'journals'])
            ->whereYear('transaction_date', $selectedYear)
            ->whereMonth('transaction_date', $selectedMonth);

        if ($type && in_array($type, ['deposit', 'withdrawal'])) {
            $query->where('transaction_type', $type);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('transaction_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('saver', function ($s) use ($search) {
                      $s->where('full_name', 'like', "%{$search}%")
                        ->orWhere('account_number', 'like', "%{$search}%")
                        ->orWhere('student_id_number', 'like', "%{$search}%");
                  });
            });
        }

        // Clone query for monthly statistics cards
        $monthlyBaseQuery = Transaction::whereYear('transaction_date', $selectedYear)
            ->whereMonth('transaction_date', $selectedMonth);

        $totalTransactions = (clone $monthlyBaseQuery)->count();
        $totalDepositAmount = (int) (clone $monthlyBaseQuery)->where('transaction_type', 'deposit')->sum('amount');
        $totalWithdrawalAmount = (int) (clone $monthlyBaseQuery)->where('transaction_type', 'withdrawal')->sum('amount');
        $netCashFlow = $totalDepositAmount - $totalWithdrawalAmount;

        $depositCount = (clone $monthlyBaseQuery)->where('transaction_type', 'deposit')->count();
        $withdrawalCount = (clone $monthlyBaseQuery)->where('transaction_type', 'withdrawal')->count();

        // Paginated list (ordered newest first)
        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        // Carbon date instance for localized month title
        $periodDate = Carbon::createFromDate((int)$selectedYear, (int)$selectedMonth, 1);

        // Available years for dropdown (cross-db compatible with SQLite & MySQL)
        $availableYears = Transaction::select('transaction_date')
            ->whereNotNull('transaction_date')
            ->pluck('transaction_date')
            ->map(fn($date) => $date ? Carbon::parse($date)->year : null)
            ->filter()
            ->unique()
            ->values()
            ->sortDesc()
            ->toArray();

        if (empty($availableYears)) {
            $availableYears = [Carbon::now()->year];
        }

        return view('admin.transactions.index', compact(
            'transactions',
            'selectedMonth',
            'selectedYear',
            'periodDate',
            'totalTransactions',
            'totalDepositAmount',
            'totalWithdrawalAmount',
            'netCashFlow',
            'depositCount',
            'withdrawalCount',
            'availableYears'
        ));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['saver.member', 'account', 'user', 'journals']);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function print(Request $request)
    {
        $selectedMonth = $request->input('month', Carbon::now()->format('m'));
        $selectedYear = $request->input('year', Carbon::now()->format('Y'));
        $type = $request->input('type');

        $query = Transaction::with(['saver.member', 'account', 'user'])
            ->whereYear('transaction_date', $selectedYear)
            ->whereMonth('transaction_date', $selectedMonth);

        if ($type && in_array($type, ['deposit', 'withdrawal'])) {
            $query->where('transaction_type', $type);
        }

        $transactions = $query->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $monthlyBaseQuery = Transaction::whereYear('transaction_date', $selectedYear)
            ->whereMonth('transaction_date', $selectedMonth);

        $totalTransactions = (clone $monthlyBaseQuery)->count();
        $totalDepositAmount = (int) (clone $monthlyBaseQuery)->where('transaction_type', 'deposit')->sum('amount');
        $totalWithdrawalAmount = (int) (clone $monthlyBaseQuery)->where('transaction_type', 'withdrawal')->sum('amount');
        $netCashFlow = $totalDepositAmount - $totalWithdrawalAmount;

        $periodDate = Carbon::createFromDate((int)$selectedYear, (int)$selectedMonth, 1);

        return view('admin.transactions.print', compact(
            'transactions',
            'selectedMonth',
            'selectedYear',
            'periodDate',
            'totalTransactions',
            'totalDepositAmount',
            'totalWithdrawalAmount',
            'netCashFlow',
            'type'
        ));
    }
}

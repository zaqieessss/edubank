<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountingJournal;
use Illuminate\Http\Request;

class AccountingJournalController extends Controller
{
    public function index(Request $request)
    {
        $query = AccountingJournal::with('transaction.saver');

        if ($request->filled('date')) {
            $query->whereDate('entry_date', $request->date);
        }

        if ($request->filled('account_code')) {
            $query->where('account_code', $request->account_code);
        }

        $journals = $query->latest('entry_date')->paginate(20)->withQueryString();

        $totalDebits = (int) AccountingJournal::where('position', 'debit')->sum('amount');
        $totalCredits = (int) AccountingJournal::where('position', 'credit')->sum('amount');
        $isBalanced = ($totalDebits === $totalCredits);
        $totalEntries = AccountingJournal::count();

        // Uang Masuk (Kas Debet) & Uang Keluar (Kas Kredit)
        $totalIn = (int) AccountingJournal::where('account_code', '101')->where('position', 'debit')->sum('amount');
        $totalOut = (int) AccountingJournal::where('account_code', '101')->where('position', 'credit')->sum('amount');

        return view('admin.accounting-journals.index', compact(
            'journals',
            'totalDebits',
            'totalCredits',
            'isBalanced',
            'totalEntries',
            'totalIn',
            'totalOut'
        ));
    }
}

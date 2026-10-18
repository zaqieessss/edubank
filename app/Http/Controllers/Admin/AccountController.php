<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Services\QrCodeService;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {}

    public function index(Request $request)
    {
        $query = Account::with(['saver.member', 'saver.user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                  ->orWhereHas('saver', function ($sq) use ($search) {
                      $sq->where('full_name', 'like', "%{$search}%")
                         ->orWhere('student_id_number', 'like', "%{$search}%")
                         ->orWhere('class_name', 'like', "%{$search}%");
                  });
            });
        }

        $accounts = $query->orderBy('account_number', 'desc')->paginate(15)->withQueryString();

        return view('admin.accounts.index', compact('accounts'));
    }

    public function show(Account $account)
    {
        $account->load(['saver.member', 'saver.user', 'transactions.user']);

        $qrCodeDataUri = $this->qrCodeService->generateDataUri($account->account_number);

        return view('admin.accounts.show', compact('account', 'qrCodeDataUri'));
    }

    public function updateStatus(Request $request, Account $account)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:active,dormant,frozen,closed'],
            'notes'  => ['nullable', 'string', 'max:255'],
        ]);

        $account->update($validated);

        return back()->with('success', "Status rekening {$account->account_number} berhasil diubah menjadi {$account->status}.");
    }
}

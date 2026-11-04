<?php

namespace App\Http\Controllers\Saver;

use App\Http\Controllers\Controller;
use App\Models\Saver;
use App\Services\QrCodeService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardController extends Controller
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {}

    public function index()
    {
        $user = Auth::user();
        $saver = Saver::with(['member'])->where('user_id', $user->id)->firstOrFail();

        $qrCodeDataUri = $this->qrCodeService->generateDataUri($saver->account_number);

        $recentTransactions = $saver->transactions()->take(10)->get();

        // Calculate holding balance & withdrawable limit
        $holdingBalance = 10000;
        $maxWithdrawable = max(0, $saver->balance - $holdingBalance);

        // Monthly stats for this saver
        $currentMonth = now()->month;
        $currentYear = now()->year;

        $monthlyDeposits = (int) $saver->transactions()
            ->where('transaction_type', 'deposit')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');

        $monthlyWithdrawals = (int) $saver->transactions()
            ->where('transaction_type', 'withdrawal')
            ->whereYear('transaction_date', $currentYear)
            ->whereMonth('transaction_date', $currentMonth)
            ->sum('amount');

        $totalTransactionsCount = $saver->transactions()->count();

        return view('saver.dashboard', compact(
            'saver',
            'qrCodeDataUri',
            'recentTransactions',
            'holdingBalance',
            'maxWithdrawable',
            'monthlyDeposits',
            'monthlyWithdrawals',
            'totalTransactionsCount'
        ));
    }

    public function mutation(Request $request)
    {
        $user = Auth::user();
        $saver = Saver::where('user_id', $user->id)->firstOrFail();

        $query = $saver->transactions();

        if ($request->filled('start_date')) {
            $query->whereDate('transaction_date', '>=', $request->start_date);
        }

        if ($request->filled('end_date')) {
            $query->whereDate('transaction_date', '<=', $request->end_date);
        }

        if ($request->filled('type')) {
            $query->where('transaction_type', $request->type);
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('saver.mutation', compact('saver', 'transactions'));
    }

    public function updatePin(Request $request)
    {
        $validated = $request->validate([
            'current_pin'          => ['required', 'digits:6'],
            'new_pin'              => ['required', 'digits:6', 'different:current_pin'],
            'new_pin_confirmation' => ['required', 'same:new_pin'],
        ], [
            'current_pin.digits'          => 'PIN saat ini harus berupa 6 digit angka.',
            'new_pin.digits'              => 'PIN baru harus berupa 6 digit angka.',
            'new_pin.different'           => 'PIN baru tidak boleh sama dengan PIN saat ini.',
            'new_pin_confirmation.same'   => 'Konfirmasi PIN baru tidak cocok.',
        ]);

        $saver = Saver::where('user_id', Auth::id())->firstOrFail();

        if (!Hash::check($validated['current_pin'], $saver->security_pin)) {
            return back()->withErrors(['current_pin' => 'PIN saat ini yang Anda masukkan salah.']);
        }

        $saver->update([
            'security_pin' => Hash::make($validated['new_pin']),
        ]);

        return back()->with('success', 'PIN keamanan berhasil diperbarui!');
    }
}

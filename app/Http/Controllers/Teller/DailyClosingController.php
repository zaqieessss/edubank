<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\DailyClosing;
use App\Services\CashClosingService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DailyClosingController extends Controller
{
    public function __construct(
        protected CashClosingService $cashClosingService
    ) {}

    public function index()
    {
        $tellerId = Auth::id();
        $closings = DailyClosing::where('teller_id', $tellerId)
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('teller.daily-closings.index', compact('closings'));
    }

    public function create()
    {
        $tellerId = Auth::id();
        $summary = $this->cashClosingService->getDailySummary($tellerId);

        $todayClosing = DailyClosing::where('teller_id', $tellerId)
            ->whereDate('closing_date', $summary['date'])
            ->first();

        return view('teller.daily-closings.create', compact('summary', 'todayClosing'));
    }

    public function setOpeningBalance(Request $request)
    {
        $validated = $request->validate([
            'opening_balance' => ['required', 'integer', 'min:0'],
        ], [
            'opening_balance.required' => 'Nominal modal awal kas wajib diisi.',
            'opening_balance.min'      => 'Modal awal tidak boleh minus.',
        ]);

        try {
            $tellerId = Auth::id();
            $this->cashClosingService->setOpeningBalance($tellerId, (int) $validated['opening_balance']);

            return back()->with('success', 'Modal awal kas pagi sebesar Rp ' . number_format($validated['opening_balance'], 0, ',', '.') . ' berhasil dicatat.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'opening_balance' => ['nullable', 'integer', 'min:0'],
            'physical_cash'   => ['required', 'integer', 'min:0'],
            'notes'           => ['nullable', 'string', 'max:500'],
        ], [
            'physical_cash.required' => 'Nominal uang fisik di laci kas wajib diisi.',
            'physical_cash.min'      => 'Nominal uang fisik tidak boleh minus.',
            'opening_balance.min'    => 'Modal awal tidak boleh minus.',
        ]);

        try {
            $tellerId = Auth::id();
            $openingBalance = isset($validated['opening_balance']) ? (int) $validated['opening_balance'] : null;

            $closing = $this->cashClosingService->submitClosing(
                tellerId: $tellerId,
                physicalCash: (int) $validated['physical_cash'],
                openingBalance: $openingBalance,
                notes: $validated['notes'] ?? null
            );

            if ($closing->difference === 0) {
                return redirect()->route('teller.daily-closings.index')
                    ->with('success', 'Rekonsiliasi kas harian seimbang! Laporan telah diajukan ke Supervisor untuk verifikasi brankas.');
            } else {
                $diffText = number_format(abs($closing->difference), 0, ',', '.');
                $type = $closing->difference > 0 ? 'LEBIH' : 'KURANG';
                return redirect()->route('teller.daily-closings.index')
                    ->with('warning', "Perhatian: Terdeteksi selisih uang fisik {$type} sebesar Rp {$diffText}! Laporan ditandai selisih dan menunggu investigasi supervisor.");
            }
        } catch (Exception $e) {
            return back()->withInput()->with('error', $e->getMessage());
        }
    }
}

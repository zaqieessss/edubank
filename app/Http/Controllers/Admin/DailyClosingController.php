<?php

namespace App\Http\Controllers\Admin;

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

    public function index(Request $request)
    {
        $query = DailyClosing::with(['teller', 'admin']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $closings = $query->orderBy('id', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.daily-closings.index', compact('closings'));
    }

    public function show(DailyClosing $dailyClosing)
    {
        $dailyClosing->load(['teller', 'admin']);

        // Ambil seluruh transaksi hari itu yang diproses oleh teller closing bersangkutan
        $transactions = \App\Models\Transaction::with('saver')
            ->where('user_id', $dailyClosing->teller_id)
            ->whereDate('transaction_date', $dailyClosing->closing_date)
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.daily-closings.show', compact('dailyClosing', 'transactions'));
    }

    public function print(DailyClosing $dailyClosing)
    {
        $dailyClosing->load(['teller', 'admin']);

        $transactions = \App\Models\Transaction::with('saver')
            ->where('user_id', $dailyClosing->teller_id)
            ->whereDate('transaction_date', $dailyClosing->closing_date)
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.daily-closings.print', compact('dailyClosing', 'transactions'));
    }

    public function approve(DailyClosing $dailyClosing)
    {
        try {
            $this->cashClosingService->approveClosing($dailyClosing, Auth::id());

            return back()->with('success', 'Laporan penutupan kas berhasil diverifikasi dan disetujui! Dana kas fisik telah dialihkan ke Brankas Utama Bank Mini.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function reject(Request $request, DailyClosing $dailyClosing)
    {
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ], [
            'rejection_reason.required' => 'Alasan penolakan laporan closing wajib diisi agar teller mengetahui koreksi yang diperlukan.',
        ]);

        try {
            $this->cashClosingService->rejectClosing($dailyClosing, Auth::id(), $validated['rejection_reason']);

            return back()->with('success', 'Laporan penutupan kas berhasil ditolak. Catatan alasan penolakan telah diteruskan ke petugas teller.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

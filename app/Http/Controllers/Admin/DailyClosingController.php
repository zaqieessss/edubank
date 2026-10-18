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
        $closings = DailyClosing::with(['teller', 'admin'])
            ->orderBy('closing_date', 'desc')
            ->paginate(15);

        return view('admin.daily-closings.index', compact('closings'));
    }

    public function show(DailyClosing $dailyClosing)
    {
        $dailyClosing->load(['teller', 'admin']);

        return view('admin.daily-closings.show', compact('dailyClosing'));
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
}

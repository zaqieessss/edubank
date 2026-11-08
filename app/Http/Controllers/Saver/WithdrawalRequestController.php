<?php

namespace App\Http\Controllers\Saver;

use App\Http\Controllers\Controller;
use App\Models\Saver;
use App\Models\WithdrawalRequest;
use App\Services\TransactionService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalRequestController extends Controller
{
    /**
     * Tampilkan daftar permohonan penarikan H-1 nasabah
     */
    public function index()
    {
        $user = Auth::user();
        $saver = Saver::where('user_id', $user->id)->firstOrFail();

        $requests = WithdrawalRequest::where('saver_id', $saver->id)
            ->with(['approver', 'transaction'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $holdingBalance = TransactionService::MINIMUM_HOLDING_BALANCE;
        $balance = (int) ($saver->account->balance ?? $saver->balance ?? 0);
        $maxWithdrawable = max(0, $balance - $holdingBalance);

        return view('saver.withdrawal-requests.index', compact('saver', 'requests', 'balance', 'maxWithdrawable', 'holdingBalance'));
    }

    /**
     * Simpan pengajuan penarikan dana terencana (H-1) baru
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $saver = Saver::where('user_id', $user->id)->firstOrFail();

        $holdingBalance = TransactionService::MINIMUM_HOLDING_BALANCE;
        $balance = (int) ($saver->account->balance ?? $saver->balance ?? 0);
        $maxWithdrawable = max(0, $balance - $holdingBalance);

        $request->validate([
            'amount' => ['required', 'integer', 'min:1000001', "max:{$maxWithdrawable}"],
            'pickup_date' => ['required', 'date', 'after_or_equal:tomorrow'],
            'reason' => ['required', 'string', 'min:5', 'max:255'],
        ], [
            'amount.min' => 'Pengajuan penarikan H-1 dikhususkan untuk nominal penarikan di atas Rp 1.000.000.',
            'amount.max' => 'Nominal melebihi saldo tabungan yang dapat ditarik (tersisa saldo mengendap Rp ' . number_format($holdingBalance, 0, ',', '.') . ').',
            'pickup_date.after_or_equal' => 'Tanggal pengambilan dana minimal H-1 (mulai besok).',
            'reason.required' => 'Mohon sertakan alasan penarikan dana.',
        ]);

        // Cek apakah sudah ada pengajuan pending untuk tanggal yang sama
        $existing = WithdrawalRequest::where('saver_id', $saver->id)
            ->whereDate('pickup_date', $request->pickup_date)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return back()->with('error', 'Anda sudah memiliki permohonan penarikan untuk tanggal ' . date('d/m/Y', strtotime($request->pickup_date)) . ' dengan status ' . strtoupper($existing->status) . '.');
        }

        WithdrawalRequest::create([
            'saver_id' => $saver->id,
            'amount' => $request->amount,
            'pickup_date' => $request->pickup_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return redirect()->route('saver.withdrawal-requests.index')->with('success', 'Permohonan penarikan dana H-1 berhasil diajukan dan sedang menunggu verifikasi Admin.');
    }

    /**
     * Batalkan pengajuan penarikan dana
     */
    public function cancel(int $id)
    {
        $user = Auth::user();
        $saver = Saver::where('user_id', $user->id)->firstOrFail();

        $withdrawalRequest = WithdrawalRequest::where('id', $id)
            ->where('saver_id', $saver->id)
            ->where('status', 'pending')
            ->firstOrFail();

        $withdrawalRequest->update(['status' => 'cancelled']);

        return back()->with('success', 'Permohonan penarikan berhasil dibatalkan.');
    }
}

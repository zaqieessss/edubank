<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WithdrawalRequestController extends Controller
{
    /**
     * Tampilkan seluruh permohonan penarikan dana terencana (H-1)
     */
    public function index(Request $request)
    {
        $status = $request->query('status', 'all');
        $search = $request->query('search');
        $date = $request->query('date');

        $query = WithdrawalRequest::with(['saver.member', 'saver.account', 'approver', 'transaction'])
            ->orderByRaw("CASE WHEN status = 'pending' THEN 1 WHEN status = 'approved' THEN 2 ELSE 3 END")
            ->orderBy('pickup_date', 'asc')
            ->orderBy('created_at', 'desc');

        if ($status !== 'all' && !empty($status)) {
            $query->where('status', $status);
        }

        if (!empty($date)) {
            $query->whereDate('pickup_date', $date);
        }

        if (!empty($search)) {
            $query->whereHas('saver', function ($q) use ($search) {
                $q->where('account_number', 'like', "%{$search}%")
                  ->orWhere('full_name', 'like', "%{$search}%")
                  ->orWhere('class_name', 'like', "%{$search}%");
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => WithdrawalRequest::count(),
            'pending' => WithdrawalRequest::where('status', 'pending')->count(),
            'approved_today' => WithdrawalRequest::whereDate('pickup_date', today())->where('status', 'approved')->count(),
            'completed' => WithdrawalRequest::where('status', 'completed')->count(),
            'planned_amount_today' => (int) WithdrawalRequest::whereDate('pickup_date', today())->where('status', 'approved')->sum('amount'),
        ];

        return view('admin.withdrawal-requests.index', compact('requests', 'status', 'search', 'date', 'stats'));
    }

    /**
     * Setujui permohonan penarikan dana H-1
     */
    public function approve(Request $request, WithdrawalRequest $withdrawalRequest)
    {
        if ($withdrawalRequest->status !== 'pending') {
            return back()->with('error', 'Hanya permohonan berstatus PENDING yang dapat disetujui.');
        }

        // Cek saldo nasabah terkini apakah masih mencukupi
        $currentBalance = (int) ($withdrawalRequest->saver->balance ?? 0);
        if (($currentBalance - $withdrawalRequest->amount) < 10000) {
            return back()->with('error', 'Persetujuan gagal! Saldo nasabah saat ini (Rp ' . number_format($currentBalance, 0, ',', '.') . ') tidak mencukupi untuk penarikan sebesar Rp ' . number_format($withdrawalRequest->amount, 0, ',', '.') . ' dengan saldo mengendap Rp 10.000.');
        }

        $withdrawalRequest->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes ?? 'Disetujui oleh Pembina Bank Mini untuk pencairan kas.',
        ]);

        return back()->with('success', 'Permohonan penarikan Rp ' . number_format($withdrawalRequest->amount, 0, ',', '.') . ' milik ' . $withdrawalRequest->saver->full_name . ' berhasil disetujui.');
    }

    /**
     * Tolak permohonan penarikan dana H-1
     */
    public function reject(Request $request, WithdrawalRequest $withdrawalRequest)
    {
        if ($withdrawalRequest->status !== 'pending') {
            return back()->with('error', 'Hanya permohonan berstatus PENDING yang dapat ditolak.');
        }

        $request->validate([
            'admin_notes' => ['required', 'string', 'min:3', 'max:255'],
        ], [
            'admin_notes.required' => 'Wajib memberikan alasan penolakan permohonan.',
        ]);

        $withdrawalRequest->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'admin_notes' => $request->admin_notes,
        ]);

        return back()->with('success', 'Permohonan penarikan berhasil ditolak dengan catatan: ' . $request->admin_notes);
    }
}

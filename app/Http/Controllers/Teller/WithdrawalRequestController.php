<?php

namespace App\Http\Controllers\Teller;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;

class WithdrawalRequestController extends Controller
{
    /**
     * Tampilkan daftar penarikan terencana (H-1) untuk operasional loket teller
     */
    public function index(Request $request)
    {
        $date = $request->query('date', today()->toDateString());
        $status = $request->query('status', 'approved');

        $query = WithdrawalRequest::with(['saver.member', 'saver.account', 'approver', 'transaction'])
            ->whereDate('pickup_date', $date)
            ->orderByRaw("CASE WHEN status = 'approved' THEN 1 WHEN status = 'pending' THEN 2 ELSE 3 END")
            ->orderBy('created_at', 'desc');

        if ($status !== 'all' && !empty($status)) {
            $query->where('status', $status);
        }

        $requests = $query->paginate(15)->withQueryString();

        $stats = [
            'today_approved_count' => WithdrawalRequest::whereDate('pickup_date', $date)->where('status', 'approved')->count(),
            'today_approved_amount' => (int) WithdrawalRequest::whereDate('pickup_date', $date)->where('status', 'approved')->sum('amount'),
            'today_completed_count' => WithdrawalRequest::whereDate('pickup_date', $date)->where('status', 'completed')->count(),
            'today_completed_amount' => (int) WithdrawalRequest::whereDate('pickup_date', $date)->where('status', 'completed')->sum('amount'),
        ];

        return view('teller.withdrawal-requests.index', compact('requests', 'date', 'status', 'stats'));
    }
}

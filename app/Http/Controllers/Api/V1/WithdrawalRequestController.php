<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WithdrawalRequest;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WithdrawalRequestController extends Controller
{
    /**
     * Daftar riwayat pengajuan penarikan dana terencana nasabah
     */
    public function index(Request $request): JsonResponse
    {
        $saver = $request->user()->saver;

        if (!$saver) {
            return response()->json(['success' => false, 'message' => 'Data nasabah tidak ditemukan.'], 404);
        }

        $requests = WithdrawalRequest::where('saver_id', $saver->id)
            ->with(['approver:id,name', 'transaction:id,transaction_code'])
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('per_page', 20));

        $holdingBalance = TransactionService::MINIMUM_HOLDING_BALANCE;
        $balance = (int) ($saver->account->balance ?? $saver->balance ?? 0);
        $maxWithdrawable = max(0, $balance - $holdingBalance);

        $formatted = collect($requests->items())->map(function ($item) {
            return [
                'id' => $item->id,
                'amount' => (int) $item->amount,
                'formatted_amount' => 'Rp ' . number_format($item->amount, 0, ',', '.'),
                'pickup_date' => $item->pickup_date ? $item->pickup_date->toDateString() : null,
                'formatted_pickup_date' => $item->pickup_date ? $item->pickup_date->translatedFormat('d F Y') : null,
                'reason' => $item->reason,
                'status' => $item->status,
                'status_label' => match($item->status) {
                    'pending' => 'Menunggu Verifikasi',
                    'approved' => 'Disetujui',
                    'rejected' => 'Ditolak',
                    'completed' => 'Selesai Dicairkan',
                    'cancelled' => 'Dibatalkan',
                    default => ucfirst($item->status)
                },
                'admin_notes' => $item->admin_notes,
                'approved_by' => $item->approver->name ?? null,
                'approved_at' => $item->approved_at ? Carbon::parse($item->approved_at)->translatedFormat('d M Y, H:i') : null,
                'transaction_code' => $item->transaction->transaction_code ?? null,
                'created_at' => $item->created_at ? $item->created_at->translatedFormat('d M Y, H:i') : null,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $formatted,
            'balance_info' => [
                'current_balance' => $balance,
                'max_withdrawable' => $maxWithdrawable,
                'holding_balance' => $holdingBalance,
                'daily_unplanned_limit' => TransactionService::MAX_DAILY_UNPLANNED_WITHDRAWAL,
            ],
            'meta' => [
                'current_page' => $requests->currentPage(),
                'last_page' => $requests->lastPage(),
                'per_page' => $requests->perPage(),
                'total' => $requests->total(),
            ],
        ]);
    }

    /**
     * Buat permohonan penarikan dana terencana baru
     */
    public function store(Request $request): JsonResponse
    {
        $saver = $request->user()->saver;

        if (!$saver) {
            return response()->json(['success' => false, 'message' => 'Data nasabah tidak ditemukan.'], 404);
        }

        $holdingBalance = TransactionService::MINIMUM_HOLDING_BALANCE;
        $balance = (int) ($saver->account->balance ?? $saver->balance ?? 0);
        $maxWithdrawable = max(0, $balance - $holdingBalance);

        $validator = Validator::make($request->all(), [
            'amount' => ['required', 'integer', 'min:1000001', "max:{$maxWithdrawable}"],
            'pickup_date' => ['required', 'date', 'after_or_equal:tomorrow'],
            'reason' => ['required', 'string', 'min:5', 'max:255'],
        ], [
            'amount.min' => 'Pengajuan penarikan H-1 dikhususkan untuk nominal penarikan di atas Rp 1.000.000.',
            'amount.max' => 'Nominal penarikan melebihi saldo tabungan yang dapat ditarik.',
            'pickup_date.after_or_equal' => 'Tanggal pengambilan dana minimal H-1 (mulai besok).',
            'reason.required' => 'Mohon isi alasan penarikan dana.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors(),
            ], 422);
        }

        // Cek pengajuan aktif pada tanggal yang sama
        $existing = WithdrawalRequest::where('saver_id', $saver->id)
            ->whereDate('pickup_date', $request->pickup_date)
            ->whereIn('status', ['pending', 'approved'])
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah memiliki permohonan penarikan untuk tanggal ' . date('d/m/Y', strtotime($request->pickup_date)) . ' dengan status ' . strtoupper($existing->status) . '.',
            ], 422);
        }

        $withdrawalRequest = WithdrawalRequest::create([
            'saver_id' => $saver->id,
            'amount' => $request->amount,
            'pickup_date' => $request->pickup_date,
            'reason' => $request->reason,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan penarikan dana berhasil diajukan dan sedang menunggu verifikasi Pembina Bank.',
            'data' => [
                'id' => $withdrawalRequest->id,
                'amount' => (int) $withdrawalRequest->amount,
                'pickup_date' => $withdrawalRequest->pickup_date->toDateString(),
                'status' => $withdrawalRequest->status,
            ],
        ], 201);
    }

    /**
     * Batalkan permohonan penarikan (hanya jika masih pending)
     */
    public function cancel(Request $request, int $id): JsonResponse
    {
        $saver = $request->user()->saver;

        $withdrawalRequest = WithdrawalRequest::where('id', $id)
            ->where('saver_id', $saver->id)
            ->where('status', 'pending')
            ->first();

        if (!$withdrawalRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Permohonan tidak ditemukan atau sudah tidak dapat dibatalkan.',
            ], 404);
        }

        $withdrawalRequest->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => 'Permohonan penarikan berhasil dibatalkan.',
        ]);
    }
}

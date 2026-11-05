<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Dashboard ringkasan data nasabah & mutasi terbaru.
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user()->load(['saver.account']);
        $saver = $user->saver;

        if (!$saver) {
            return response()->json([
                'success' => false,
                'message' => 'Data nasabah tidak ditemukan untuk akun ini.',
            ], 404);
        }

        $minHolding = (int) Setting::where('key', 'min_holding_balance')->value('value') ?: 10000;
        $currentBalance = (int) ($saver->account->balance ?? $saver->balance ?? 0);
        $availableBalance = max(0, $currentBalance - $minHolding);

        // Hitung total setoran & penarikan bulan ini (1 s/d akhir bulan)
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $monthlyIn = (int) $saver->transactions()
            ->where('transaction_type', 'deposit')
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $monthlyOut = (int) $saver->transactions()
            ->whereIn('transaction_type', ['withdraw', 'withdrawal'])
            ->whereBetween('transaction_date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // Hitung total uang masuk (setoran) & uang keluar (penarikan) pada minggu ini (Senin - Minggu)
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $weeklyIn = (int) $saver->transactions()
            ->where('transaction_type', 'deposit')
            ->whereBetween('transaction_date', [$startOfWeek, $endOfWeek])
            ->sum('amount');

        $weeklyOut = (int) $saver->transactions()
            ->whereIn('transaction_type', ['withdraw', 'withdrawal'])
            ->whereBetween('transaction_date', [$startOfWeek, $endOfWeek])
            ->sum('amount');

        // Ambil 5 transaksi mutasi terakhir
        $recentTransactions = $saver->transactions()
            ->with('user:id,name')
            ->orderBy('transaction_date', 'desc')
            ->orderBy('id', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($trx) {
                return [
                    'id'               => $trx->id,
                    'transaction_code' => $trx->transaction_code,
                    'type'             => ($trx->transaction_type === 'withdrawal') ? 'withdraw' : $trx->transaction_type,
                    'amount'           => (int) $trx->amount,
                    'balance_after'    => (int) $trx->balance_after,
                    'description'      => $trx->description,
                    'teller_name'      => $trx->user->name ?? 'Teller Bank Mini',
                    'date'             => $trx->transaction_date ? $trx->transaction_date->format('Y-m-d H:i:s') : null,
                    'formatted_date'   => $trx->transaction_date ? $trx->transaction_date->translatedFormat('d M Y, H:i') : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'student' => [
                    'name'              => $saver->full_name,
                    'student_id_number' => $saver->student_id_number,
                    'class_name'        => $saver->class_name,
                    'major'             => $saver->major,
                    'cohort'            => $saver->cohort,
                    'status'            => $saver->status,
                ],
                'account' => [
                    'account_number'    => $saver->account_number,
                    'balance'           => $currentBalance,
                    'min_holding'       => $minHolding,
                    'available_balance' => $availableBalance,
                    'account_type'      => 'Tabungan Siswa Edubank',
                ],
                'monthly_stats' => [
                    'total_in'  => $monthlyIn,
                    'total_out' => $monthlyOut,
                ],
                'weekly_stats' => [
                    'total_in'  => $weeklyIn,
                    'total_out' => $weeklyOut,
                ],
                'recent_transactions' => $recentTransactions,
            ],
        ]);
    }

    /**
     * Payload data QR Code untuk ditunjukkan ke loket.
     */
    public function qrCode(Request $request): JsonResponse
    {
        $user = $request->user()->load('saver');
        $saver = $user->saver;

        if (!$saver) {
            return response()->json([
                'success' => false,
                'message' => 'Data nasabah tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'account_number'    => $saver->account_number,
                'qr_data'           => $saver->account_number,
                'full_name'         => $saver->full_name,
                'student_id_number' => $saver->student_id_number,
                'class_name'        => $saver->class_name,
            ],
        ]);
    }
}
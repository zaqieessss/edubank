<?php

namespace App\Services;

use App\Models\DailyClosing;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;

class CashClosingService
{
    /**
     * Calculate current day's cash summary for a teller.
     */
    public function getDailySummary(int $tellerId, ?string $date = null): array
    {
        $targetDate = $date ?? now()->toDateString();

        // 1. Get Opening Balance (Yesterday's approved system balance or 0)
        $previousClosing = DailyClosing::where('teller_id', $tellerId)
            ->where('closing_date', '<', $targetDate)
            ->where('status', 'approved')
            ->orderBy('closing_date', 'desc')
            ->first();

        $openingBalance = $previousClosing ? $previousClosing->system_balance : 0;

        // 2. Sum deposits processed by this teller today
        $totalDeposits = (int) Transaction::where('user_id', $tellerId)
            ->whereDate('transaction_date', $targetDate)
            ->where('transaction_type', 'deposit')
            ->sum('amount');

        // 3. Sum withdrawals processed by this teller today
        $totalWithdrawals = (int) Transaction::where('user_id', $tellerId)
            ->whereDate('transaction_date', $targetDate)
            ->where('transaction_type', 'withdrawal')
            ->sum('amount');

        // 4. Formula: Opening + Deposits - Withdrawals
        $systemBalance = $openingBalance + $totalDeposits - $totalWithdrawals;

        // 5. Total count of transactions
        $depositCount = Transaction::where('user_id', $tellerId)
            ->whereDate('transaction_date', $targetDate)
            ->where('transaction_type', 'deposit')
            ->count();

        $withdrawalCount = Transaction::where('user_id', $tellerId)
            ->whereDate('transaction_date', $targetDate)
            ->where('transaction_type', 'withdrawal')
            ->count();

        return [
            'date'              => $targetDate,
            'opening_balance'   => $openingBalance,
            'total_deposits'    => $totalDeposits,
            'total_withdrawals' => $totalWithdrawals,
            'system_balance'    => $systemBalance,
            'deposit_count'     => $depositCount,
            'withdrawal_count'  => $withdrawalCount,
        ];
    }

    /**
     * Submit teller cash closing for daily reconciliation.
     *
     * @param int $tellerId
     * @param int $physicalCash
     * @param string|null $notes
     * @return DailyClosing
     */
    public function submitClosing(int $tellerId, int $physicalCash, ?string $notes = null): DailyClosing
    {
        $summary = $this->getDailySummary($tellerId);

        $difference = $physicalCash - $summary['system_balance'];
        $status = ($difference === 0) ? 'submitted' : 'discrepancy';

        return DB::transaction(function () use ($tellerId, $summary, $physicalCash, $difference, $status, $notes) {
            $closing = DailyClosing::updateOrCreate(
                [
                    'teller_id'    => $tellerId,
                    'closing_date' => $summary['date'],
                ],
                [
                    'opening_balance'   => $summary['opening_balance'],
                    'total_deposits'    => $summary['total_deposits'],
                    'total_withdrawals' => $summary['total_withdrawals'],
                    'system_balance'    => $summary['system_balance'],
                    'physical_cash'     => $physicalCash,
                    'difference'        => $difference,
                    'status'            => $status,
                    'vault_status'      => 'with_teller',
                    'notes'             => $notes,
                ]
            );

            return $closing;
        });
    }

    /**
     * Admin/Supervisor verifies and approves closing, transferring cash to vault.
     *
     * @param DailyClosing $closing
     * @param int $adminId
     * @return DailyClosing
     * @throws Exception
     */
    public function approveClosing(DailyClosing $closing, int $adminId): DailyClosing
    {
        if ($closing->difference !== 0) {
            throw new Exception("Laporan belum seimbang (selisih: Rp " . number_format($closing->difference, 0, ',', '.') . "). Selesaikan dispute terlebih dahulu sebelum approval.");
        }

        return DB::transaction(function () use ($closing, $adminId) {
            $closing->update([
                'admin_id'     => $adminId,
                'status'       => 'approved',
                'vault_status' => 'transferred_to_vault',
            ]);

            return $closing;
        });
    }
}

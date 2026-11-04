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

        // 1. Get Opening Balance: cek apakah teller sudah input modal hari ini
        $todayClosing = DailyClosing::where('teller_id', $tellerId)
            ->whereDate('closing_date', $targetDate)
            ->first();

        if ($todayClosing) {
            $openingBalance = (int) $todayClosing->opening_balance;
            $hasSetOpening = true;
        } else {
            // Cek saldo akhir kemarin yang approved, atau 0
            $previousClosing = DailyClosing::where('teller_id', $tellerId)
                ->where('closing_date', '<', $targetDate)
                ->where('status', 'approved')
                ->orderBy('closing_date', 'desc')
                ->first();

            $openingBalance = $previousClosing ? (int) $previousClosing->system_balance : 0;
            $hasSetOpening = false;
        }

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

        // 4. Formula: Modal Awal + Setoran Masuk - Penarikan Keluar
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
            'has_set_opening'   => $hasSetOpening,
            'total_deposits'    => $totalDeposits,
            'total_withdrawals' => $totalWithdrawals,
            'system_balance'    => $systemBalance,
            'deposit_count'     => $depositCount,
            'withdrawal_count'  => $withdrawalCount,
            'transaction_count' => $depositCount + $withdrawalCount,
            'closing_cash'      => $systemBalance,
        ];
    }

    /**
     * Input or update opening cash capital given by supervisor for today.
     */
    public function setOpeningBalance(int $tellerId, int $openingBalance, ?string $date = null): DailyClosing
    {
        $targetDate = $date ?? now()->toDateString();

        $closing = DailyClosing::firstOrNew([
            'teller_id'    => $tellerId,
            'closing_date' => $targetDate,
        ]);

        if (in_array($closing->status, ['submitted', 'approved'], true)) {
            throw new Exception('Buku kas hari ini sudah diajukan/ditutup dan tidak dapat mengubah modal awal.');
        }

        $totalDeposits = (int) Transaction::where('user_id', $tellerId)
            ->whereDate('transaction_date', $targetDate)
            ->where('transaction_type', 'deposit')
            ->sum('amount');

        $totalWithdrawals = (int) Transaction::where('user_id', $tellerId)
            ->whereDate('transaction_date', $targetDate)
            ->where('transaction_type', 'withdrawal')
            ->sum('amount');

        $systemBalance = $openingBalance + $totalDeposits - $totalWithdrawals;

        $closing->opening_balance   = $openingBalance;
        $closing->total_deposits    = $totalDeposits;
        $closing->total_withdrawals = $totalWithdrawals;
        $closing->system_balance    = $systemBalance;
        if (!$closing->exists) {
            $closing->status       = 'draft';
            $closing->vault_status = 'with_teller';
        }
        $closing->save();

        return $closing;
    }

    /**
     * Submit teller cash closing for daily reconciliation.
     *
     * @param int $tellerId
     * @param int $physicalCash
     * @param int|null $openingBalance
     * @param string|null $notes
     * @return DailyClosing
     */
    public function submitClosing(int $tellerId, int $physicalCash, ?int $openingBalance = null, ?string $notes = null): DailyClosing
    {
        $targetDate = now()->toDateString();

        if ($openingBalance !== null) {
            $this->setOpeningBalance($tellerId, $openingBalance, $targetDate);
        }

        $summary = $this->getDailySummary($tellerId, $targetDate);

        $difference = $physicalCash - $summary['system_balance'];
        $status = ($difference === 0) ? 'submitted' : 'discrepancy';

        return DB::transaction(function () use ($tellerId, $summary, $physicalCash, $difference, $status, $notes) {
            $closing = DailyClosing::where('teller_id', $tellerId)
                ->whereDate('closing_date', $summary['date'])
                ->first();

            $data = [
                'opening_balance'   => $summary['opening_balance'],
                'total_deposits'    => $summary['total_deposits'],
                'total_withdrawals' => $summary['total_withdrawals'],
                'system_balance'    => $summary['system_balance'],
                'physical_cash'     => $physicalCash,
                'difference'        => $difference,
                'status'            => $status,
                'vault_status'      => 'with_teller',
                'notes'             => $notes,
                'rejection_reason'  => null,
            ];

            if ($closing) {
                $closing->update($data);
            } else {
                $closing = DailyClosing::create(array_merge([
                    'teller_id'    => $tellerId,
                    'closing_date' => $summary['date'],
                ], $data));
            }

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

    /**
     * Admin/Supervisor rejects closing with a reason.
     *
     * @param DailyClosing $closing
     * @param int $adminId
     * @param string $reason
     * @return DailyClosing
     */
    public function rejectClosing(DailyClosing $closing, int $adminId, string $reason): DailyClosing
    {
        return DB::transaction(function () use ($closing, $adminId, $reason) {
            $closing->update([
                'admin_id'         => $adminId,
                'status'           => 'rejected',
                'rejection_reason' => $reason,
                'vault_status'     => 'with_teller',
            ]);

            return $closing;
        });
    }
}

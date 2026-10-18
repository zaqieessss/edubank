<?php

namespace App\Services;

use App\Models\AccountingJournal;
use App\Models\Saver;
use App\Models\Transaction;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransactionService
{
    /**
     * Minimum balance threshold required by Bank Mini regulations (Rp 10.000).
     */
    public const MINIMUM_HOLDING_BALANCE = 10000;

    /**
     * Minimum transaction amount for deposit and withdrawal (Rp 10.000).
     */
    public const MINIMUM_TRANSACTION_AMOUNT = 10000;

    /**
     * Process cash deposit at teller counter.
     *
     * @param Saver $saver
     * @param int $amount
     * @param int $tellerId
     * @param string|null $description
     * @return Transaction
     * @throws Exception
     */
    public function deposit(
        Saver $saver,
        int $amount,
        int $tellerId,
        ?string $description = null
    ): Transaction {
        if ($amount < self::MINIMUM_TRANSACTION_AMOUNT) {
            throw new Exception('Nominal setoran tunai minimal adalah Rp ' . number_format(self::MINIMUM_TRANSACTION_AMOUNT, 0, ',', '.') . '.');
        }

        return DB::transaction(function () use ($saver, $amount, $tellerId, $description) {
            // Pessimistic locking to prevent race conditions
            $lockedSaver = Saver::where('id', $saver->id)->lockForUpdate()->firstOrFail();

            $balanceBefore = $lockedSaver->balance;
            $balanceAfter  = $balanceBefore + $amount;

            // 1. Update Saver Balance
            $lockedSaver->update(['balance' => $balanceAfter]);

            // 1b. Update Account Record if exists
            $account = \App\Models\Account::where('saver_id', $lockedSaver->id)->first();
            if ($account) {
                $account->update(['balance' => $balanceAfter]);
            }

            // 2. Record Transaction
            $transactionCode = 'TRX-' . date('Ymd') . '-' . str_pad((string) (Transaction::count() + 1), 4, '0', STR_PAD_LEFT);
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'saver_id'         => $lockedSaver->id,
                'account_id'       => $account?->id,
                'user_id'          => $tellerId,
                'transaction_type' => 'deposit',
                'amount'           => $amount,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $balanceAfter,
                'description'      => $description ?? 'Setoran Tunai di Loket Teller',
                'transaction_date' => now(),
            ]);

            // 3. Double-Entry Accounting Journal
            // Debit: Kas Teller (101)
            AccountingJournal::create([
                'transaction_id' => $transaction->id,
                'account_code' => '101',
                'account_name' => 'Cash at Teller',
                'position' => 'debit',
                'amount' => $amount,
                'entry_date' => now(),
            ]);

            // Credit: Simpanan Nasabah (201)
            AccountingJournal::create([
                'transaction_id' => $transaction->id,
                'account_code' => '201',
                'account_name' => 'Student Savings Liability',
                'position' => 'credit',
                'amount' => $amount,
                'entry_date' => now(),
            ]);

            return $transaction;
        });
    }

    /**
     * Process cash withdrawal at teller counter with PIN verification & minimum holding balance check.
     *
     * @param Saver $saver
     * @param int $amount
     * @param string $pin 6-digit PIN input by customer
     * @param int $tellerId
     * @param string|null $description
     * @return Transaction
     * @throws Exception
     */
    public function withdraw(
        Saver $saver,
        int $amount,
        string $pin,
        int $tellerId,
        ?string $description = null
    ): Transaction {
        if ($amount < self::MINIMUM_TRANSACTION_AMOUNT) {
            throw new Exception('Nominal penarikan tunai minimal adalah Rp ' . number_format(self::MINIMUM_TRANSACTION_AMOUNT, 0, ',', '.') . '.');
        }

        // Verify Customer 6-digit Security PIN
        if (!Hash::check($pin, $saver->security_pin)) {
            throw new Exception('PIN keamanan nasabah salah! Transaksi penarikan dibatalkan.');
        }

        return DB::transaction(function () use ($saver, $amount, $tellerId, $description) {
            // Pessimistic locking
            $lockedSaver = Saver::where('id', $saver->id)->lockForUpdate()->firstOrFail();

            $balanceBefore = $lockedSaver->balance;

            // Check Minimum Holding Balance Rule (Rp 10.000)
            if (($balanceBefore - $amount) < self::MINIMUM_HOLDING_BALANCE) {
                $maxWithdrawable = max(0, $balanceBefore - self::MINIMUM_HOLDING_BALANCE);
                throw new Exception(
                    "Penarikan ditolak! Saldo mengendap minimal adalah Rp " . number_format(self::MINIMUM_HOLDING_BALANCE, 0, ',', '.') .
                    ". Saldo saat ini: Rp " . number_format($balanceBefore, 0, ',', '.') .
                    ". Maksimal yang dapat ditarik: Rp " . number_format($maxWithdrawable, 0, ',', '.') . "."
                );
            }

            $balanceAfter = $balanceBefore - $amount;

            // 1. Update Saver Balance
            $lockedSaver->update(['balance' => $balanceAfter]);

            // 1b. Update Account Record if exists
            $account = \App\Models\Account::where('saver_id', $lockedSaver->id)->first();
            if ($account) {
                $account->update(['balance' => $balanceAfter]);
            }

            // 2. Record Transaction
            $transactionCode = 'TRX-' . date('Ymd') . '-' . str_pad((string) (Transaction::count() + 1), 4, '0', STR_PAD_LEFT);
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'saver_id'         => $lockedSaver->id,
                'account_id'       => $account?->id,
                'user_id'          => $tellerId,
                'transaction_type' => 'withdrawal',
                'amount'           => $amount,
                'balance_before'   => $balanceBefore,
                'balance_after'    => $balanceAfter,
                'description'      => $description ?? 'Penarikan Tunai di Loket Teller',
                'transaction_date' => now(),
            ]);

            // 3. Double-Entry Accounting Journal
            // Debit: Simpanan Nasabah (201)
            AccountingJournal::create([
                'transaction_id' => $transaction->id,
                'account_code' => '201',
                'account_name' => 'Student Savings Liability',
                'position' => 'debit',
                'amount' => $amount,
                'entry_date' => now(),
            ]);

            // Credit: Kas Teller (101)
            AccountingJournal::create([
                'transaction_id' => $transaction->id,
                'account_code' => '101',
                'account_name' => 'Cash at Teller',
                'position' => 'credit',
                'amount' => $amount,
                'entry_date' => now(),
            ]);

            return $transaction;
        });
    }

    /**
     * Optional: Execute Deposit directly via MySQL Stored Procedure (sp_process_deposit).
     */
    public function depositViaStoredProcedure(
        int $saverId,
        int $tellerId,
        int $amount,
        ?string $description = null
    ): int {
        DB::statement('CALL sp_process_deposit(?, ?, ?, ?, @out_trx_id)', [
            $saverId,
            $tellerId,
            $amount,
            $description ?? 'Setoran Tunai via Stored Procedure',
        ]);

        $result = DB::select('SELECT @out_trx_id as transaction_id');
        return (int) $result[0]->transaction_id;
    }

    /**
     * Optional: Execute Withdrawal directly via MySQL Stored Procedure (sp_process_withdrawal).
     */
    public function withdrawViaStoredProcedure(
        int $saverId,
        int $tellerId,
        int $amount,
        string $pin,
        ?string $description = null
    ): int {
        $saver = Saver::findOrFail($saverId);

        if (!Hash::check($pin, $saver->security_pin)) {
            throw new Exception('PIN keamanan nasabah salah! Transaksi penarikan dibatalkan.');
        }

        DB::statement('CALL sp_process_withdrawal(?, ?, ?, ?, @out_trx_id)', [
            $saverId,
            $tellerId,
            $amount,
            $description ?? 'Penarikan Tunai via Stored Procedure',
        ]);

        $result = DB::select('SELECT @out_trx_id as transaction_id');
        return (int) $result[0]->transaction_id;
    }
}

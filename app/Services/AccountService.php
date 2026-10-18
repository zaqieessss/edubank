<?php

namespace App\Services;

use App\Models\AccountingJournal;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountService
{
    public function __construct(
        protected QrCodeService $qrCodeService
    ) {}

    /**
     * Generate unique account number based on Major, Cohort, and sequential counter.
     * Format: [MAJOR]-[COHORT]-[0001] (e.g. RPL-2026-0001)
     */
    public function generateAccountNumber(string $major, string $cohort): string
    {
        $prefix = strtoupper($major) . '-' . $cohort . '-';

        $lastSaver = Saver::where('account_number', 'LIKE', $prefix . '%')
            ->orderBy('account_number', 'desc')
            ->first();

        if ($lastSaver) {
            $lastSequence = (int) substr($lastSaver->account_number, strlen($prefix));
            $nextSequence = $lastSequence + 1;
        } else {
            $nextSequence = 1;
        }

        return $prefix . str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Open a new bank account for a registered member.
     *
     * @param Member $member The student member
     * @param int $initialDeposit Minimum Rp 10.000
     * @param string $pin 6-digit security PIN
     * @param int $processedByUserId Admin/Teller user ID
     * @return Saver
     * @throws Exception
     */
    public function openAccount(
        Member $member,
        int $initialDeposit = 10000,
        string $pin = '123456',
        int $processedByUserId = 1
    ): Saver {
        if ($member->registration_status === 'registered') {
            throw new Exception("Siswa {$member->full_name} sudah memiliki rekening tabungan aktif.");
        }

        if ($initialDeposit < 10000) {
            throw new Exception('Setoran awal buka rekening minimum sebesar Rp 10.000 (batas saldo mengendap).');
        }

        if (strlen($pin) !== 6 || !ctype_digit($pin)) {
            throw new Exception('PIN keamanan harus berupa 6 digit angka.');
        }

        return DB::transaction(function () use ($member, $initialDeposit, $pin, $processedByUserId) {
            // 1. Generate Account Number
            $accountNumber = $this->generateAccountNumber($member->major, $member->cohort);

            // 2. Create User login account for student (if not exists)
            $user = User::where('email', $member->email)->first();
            if (!$user) {
                $user = User::create([
                    'name' => $member->full_name,
                    'email' => $member->email,
                    'password' => Hash::make($pin), // Default password matches initial PIN
                    'role' => 'saver',
                    'status' => 'active',
                ]);
            } else {
                $user->update(['role' => 'saver']);
            }

            // 3. Generate and save QR Code
            $qrCodePath = $this->qrCodeService->saveToFile($accountNumber, $accountNumber);

            // 4. Create Saver Record
            $saver = Saver::create([
                'member_id' => $member->id,
                'user_id' => $user->id,
                'account_number' => $accountNumber,
                'student_id_number' => $member->student_id_number,
                'full_name' => $member->full_name,
                'grade_level' => $member->grade_level,
                'class_name' => $member->class_name,
                'major' => $member->major,
                'cohort' => $member->cohort,
                'security_pin' => Hash::make($pin),
                'balance' => $initialDeposit,
                'qr_code_path' => $qrCodePath,
                'joined_date' => now()->toDateString(),
                'status' => 'active',
            ]);

            // 4b. Create Account Record
            $account = \App\Models\Account::create([
                'saver_id'        => $saver->id,
                'account_number'  => $accountNumber,
                'account_type'    => 'student_savings',
                'balance'         => $initialDeposit,
                'minimum_balance' => 10000,
                'status'          => 'active',
                'opened_date'     => now()->toDateString(),
                'qr_code_path'    => $qrCodePath,
            ]);

            // 5. Update Member Status
            $member->update(['registration_status' => 'registered']);

            // 6. Record Initial Deposit Transaction
            $transactionCode = 'TRX-' . date('Ymd') . '-' . str_pad((string) (Transaction::count() + 1), 4, '0', STR_PAD_LEFT);
            $transaction = Transaction::create([
                'transaction_code' => $transactionCode,
                'saver_id'         => $saver->id,
                'account_id'       => $account->id,
                'user_id'          => $processedByUserId,
                'transaction_type' => 'deposit',
                'amount'           => $initialDeposit,
                'balance_before'   => 0,
                'balance_after'    => $initialDeposit,
                'description'      => 'Setoran Awal Buka Rekening Baru',
                'transaction_date' => now(),
            ]);

            // 7. Record Double-Entry Accounting Journal
            // Debit: Kas Teller (101)
            AccountingJournal::create([
                'transaction_id' => $transaction->id,
                'account_code' => '101',
                'account_name' => 'Cash at Teller',
                'position' => 'debit',
                'amount' => $initialDeposit,
                'entry_date' => now(),
            ]);

            // Credit: Simpanan Nasabah (201)
            AccountingJournal::create([
                'transaction_id' => $transaction->id,
                'account_code' => '201',
                'account_name' => 'Student Savings Liability',
                'position' => 'credit',
                'amount' => $initialDeposit,
                'entry_date' => now(),
            ]);

            return $saver;
        });
    }
}

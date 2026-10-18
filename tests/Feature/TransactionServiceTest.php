<?php

namespace Tests\Feature;

use App\Models\AccountingJournal;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Transaction;
use App\Models\User;
use App\Services\TransactionService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class TransactionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TransactionService $service;
    protected User $admin;
    protected User $teller;
    protected User $studentUser;
    protected Member $member;
    protected Saver $saver;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(TransactionService::class);

        $this->admin = User::create([
            'name'     => 'Admin Test',
            'email'    => 'admin@test.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        $this->teller = User::create([
            'name'     => 'Teller Test',
            'email'    => 'teller@test.com',
            'password' => Hash::make('password'),
            'role'     => 'teller',
        ]);

        $this->studentUser = User::create([
            'name'     => 'Student Test',
            'email'    => 'student@test.com',
            'password' => Hash::make('password'),
            'role'     => 'saver',
        ]);

        $this->member = Member::create([
            'student_id_number'   => '99001',
            'full_name'           => 'Student Test',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 1',
            'major'               => 'RPL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 'student@test.com',
            'registration_status' => 'registered',
        ]);

        $this->saver = Saver::create([
            'member_id'         => $this->member->id,
            'user_id'           => $this->studentUser->id,
            'account_number'    => 'RPL-2026-0001',
            'student_id_number' => $this->member->student_id_number,
            'full_name'         => $this->member->full_name,
            'grade_level'       => '10',
            'class_name'        => 'X RPL 1',
            'major'             => 'RPL',
            'cohort'            => '2026',
            'security_pin'      => Hash::make('123456'),
            'balance'           => 50000, // Initial balance Rp 50.000
            'joined_date'       => now()->toDateString(),
            'status'            => 'active',
        ]);
    }

    public function test_deposit_increases_balance_and_creates_balanced_double_entry_journal(): void
    {
        $depositAmount = 25000;

        $transaction = $this->service->deposit(
            saver: $this->saver,
            amount: $depositAmount,
            tellerId: $this->teller->id,
            description: 'Setoran Uji Coba'
        );

        $this->saver->refresh();

        // 1. Check balance is increased
        $this->assertEquals(75000, $this->saver->balance);
        $this->assertEquals(50000, $transaction->balance_before);
        $this->assertEquals(75000, $transaction->balance_after);

        // 2. Check 2 rows of journals created (Debit 101 Kas, Credit 201 Simpanan)
        $journals = AccountingJournal::where('transaction_id', $transaction->id)->get();
        $this->assertCount(2, $journals);

        $debitJournal = $journals->firstWhere('position', 'debit');
        $creditJournal = $journals->firstWhere('position', 'credit');

        $this->assertEquals('101', $debitJournal->account_code);
        $this->assertEquals($depositAmount, $debitJournal->amount);

        $this->assertEquals('201', $creditJournal->account_code);
        $this->assertEquals($depositAmount, $creditJournal->amount);
    }

    public function test_withdrawal_verifies_pin_and_decreases_balance(): void
    {
        $withdrawAmount = 20000;

        $transaction = $this->service->withdraw(
            saver: $this->saver,
            amount: $withdrawAmount,
            pin: '123456',
            tellerId: $this->teller->id,
            description: 'Tarik Tunai Uji Coba'
        );

        $this->saver->refresh();

        // 1. Check balance is decreased
        $this->assertEquals(30000, $this->saver->balance);
        $this->assertEquals(50000, $transaction->balance_before);
        $this->assertEquals(30000, $transaction->balance_after);

        // 2. Check double-entry journals (Debit 201 Simpanan, Credit 101 Kas)
        $journals = AccountingJournal::where('transaction_id', $transaction->id)->get();
        $this->assertCount(2, $journals);

        $debitJournal = $journals->firstWhere('position', 'debit');
        $creditJournal = $journals->firstWhere('position', 'credit');

        $this->assertEquals('201', $debitJournal->account_code);
        $this->assertEquals($withdrawAmount, $debitJournal->amount);

        $this->assertEquals('101', $creditJournal->account_code);
        $this->assertEquals($withdrawAmount, $creditJournal->amount);
    }

    public function test_transaction_fails_if_amount_less_than_10000(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Nominal setoran tunai minimal adalah Rp 10.000');

        $this->service->deposit(
            saver: $this->saver,
            amount: 5000, // Less than minimum 10.000
            tellerId: $this->teller->id
        );
    }

    public function test_withdrawal_fails_with_invalid_pin(): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('PIN keamanan nasabah salah');

        $this->service->withdraw(
            saver: $this->saver,
            amount: 10000,
            pin: '000000', // Wrong PIN
            tellerId: $this->teller->id
        );
    }

    public function test_withdrawal_rejects_violating_minimum_holding_balance_of_10000(): void
    {
        // Current balance is 50.000. If student withdraws 45.000, remaining balance would be 5.000 (< 10.000).
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Saldo mengendap minimal adalah Rp 10.000');

        $this->service->withdraw(
            saver: $this->saver,
            amount: 45000,
            pin: '123456',
            tellerId: $this->teller->id
        );
    }

    public function test_withdrawal_allows_exact_holding_balance_remaining(): void
    {
        // Current balance is 50.000. Withdrawing exactly 40.000 leaves exactly 10.000.
        $transaction = $this->service->withdraw(
            saver: $this->saver,
            amount: 40000,
            pin: '123456',
            tellerId: $this->teller->id
        );

        $this->saver->refresh();
        $this->assertEquals(10000, $this->saver->balance);
        $this->assertEquals(10000, $transaction->balance_after);
    }
}

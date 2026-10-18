<?php

namespace Tests\Feature;

use App\Models\AccountingJournal;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Transaction;
use App\Models\User;
use App\Services\AccountService;
use App\Services\MemberService;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountAndMemberServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AccountService $accountService;
    protected MemberService $memberService;
    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->accountService = app(AccountService::class);
        $this->memberService = app(MemberService::class);

        $this->admin = User::create([
            'name'     => 'Admin Test',
            'email'    => 'admin@test.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
    }

    public function test_open_account_creates_saver_user_transaction_and_double_entry_journal(): void
    {
        $member = Member::create([
            'student_id_number'   => '14001',
            'full_name'           => 'Randi Kurniawan',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 1',
            'major'               => 'RPL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 'randi@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $saver = $this->accountService->openAccount(
            member: $member,
            initialDeposit: 15000,
            pin: '654321',
            processedByUserId: $this->admin->id
        );

        $member->refresh();

        // 1. Check account number format: RPL-2026-0001
        $this->assertEquals('RPL-2026-0001', $saver->account_number);
        $this->assertEquals(15000, $saver->balance);
        $this->assertEquals('registered', $member->registration_status);

        // 2. Check user account was created
        $user = User::where('email', 'randi@siswa.test')->first();
        $this->assertNotNull($user);
        $this->assertEquals('saver', $user->role);

        // 3. Check initial transaction
        $transaction = Transaction::where('saver_id', $saver->id)->first();
        $this->assertNotNull($transaction);
        $this->assertEquals('deposit', $transaction->transaction_type);
        $this->assertEquals(15000, $transaction->amount);

        // 4. Check double-entry accounting journals
        $journals = AccountingJournal::where('transaction_id', $transaction->id)->get();
        $this->assertCount(2, $journals);

        $debit = $journals->firstWhere('position', 'debit');
        $credit = $journals->firstWhere('position', 'credit');

        $this->assertEquals('101', $debit->account_code);
        $this->assertEquals(15000, $debit->amount);

        $this->assertEquals('201', $credit->account_code);
        $this->assertEquals(15000, $credit->amount);
    }

    public function test_open_account_fails_if_initial_deposit_less_than_10000(): void
    {
        $member = Member::create([
            'student_id_number'   => '14002',
            'full_name'           => 'Rina Astuti',
            'grade_level'         => '10',
            'class_name'          => 'X AKL 1',
            'major'               => 'AKL',
            'cohort'              => '2026',
            'gender'              => 'female',
            'email'               => 'rina@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Setoran awal buka rekening minimum sebesar Rp 10.000');

        $this->accountService->openAccount(
            member: $member,
            initialDeposit: 5000, // Violates minimum Rp 10.000
            pin: '123456',
            processedByUserId: $this->admin->id
        );
    }

    public function test_mass_grade_promotion_advances_all_students_and_syncs_savers(): void
    {
        $m10 = Member::create([
            'student_id_number'   => '15010',
            'full_name'           => 'Siswa Kelas 10',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 1',
            'major'               => 'RPL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 's10@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $m11 = Member::create([
            'student_id_number'   => '15011',
            'full_name'           => 'Siswa Kelas 11',
            'grade_level'         => '11',
            'class_name'          => 'XI TKJ 2',
            'major'               => 'TKJ',
            'cohort'              => '2025',
            'gender'              => 'male',
            'email'               => 's11@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $m12 = Member::create([
            'student_id_number'   => '15012',
            'full_name'           => 'Siswa Kelas 12',
            'grade_level'         => '12',
            'class_name'          => 'XII AKL 1',
            'major'               => 'AKL',
            'cohort'              => '2024',
            'gender'              => 'female',
            'email'               => 's12@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $result = $this->memberService->promoteGrades();

        $this->assertEquals(1, $result['promoted_to_11']);
        $this->assertEquals(1, $result['promoted_to_12']);
        $this->assertEquals(1, $result['promoted_to_alumni']);

        $m10->refresh();
        $m11->refresh();
        $m12->refresh();

        // 10 becomes 11, class name updated to XI RPL 1
        $this->assertEquals('11', $m10->grade_level);
        $this->assertEquals('XI RPL 1', $m10->class_name);

        // 11 becomes 12, class name updated to XII TKJ 2
        $this->assertEquals('12', $m11->grade_level);
        $this->assertEquals('XII TKJ 2', $m11->class_name);

        // 12 becomes alumni
        $this->assertEquals('alumni', $m12->grade_level);
    }
}

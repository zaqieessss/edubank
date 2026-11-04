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
            'status'   => 'active',
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
        $this->assertTrue(Hash::check('654321', $user->password));

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

    public function test_open_account_with_custom_password(): void
    {
        $member = Member::create([
            'student_id_number'   => '14005',
            'full_name'           => 'Fajar Pratama',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 2',
            'major'               => 'RPL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 'fajar@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $saver = $this->accountService->openAccount(
            member: $member,
            initialDeposit: 20000,
            pin: '123456',
            processedByUserId: $this->admin->id,
            password: 'SiswaPassword2026'
        );

        $user = User::where('email', 'fajar@siswa.test')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('SiswaPassword2026', $user->password));
    }

    public function test_admin_can_open_account_via_controller_with_password(): void
    {
        $member = Member::create([
            'student_id_number'   => '14006',
            'full_name'           => 'Dewi Sartika',
            'grade_level'         => '10',
            'class_name'          => 'X AKL 2',
            'major'               => 'AKL',
            'cohort'              => '2026',
            'gender'              => 'female',
            'email'               => 'dewi@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.savers.store'), [
            'member_id'             => $member->id,
            'initial_deposit'       => 25000,
            'security_pin'          => '654321',
            'password'              => 'dewiPassword123',
            'password_confirmation' => 'dewiPassword123',
        ]);

        $saver = Saver::where('member_id', $member->id)->first();
        $this->assertNotNull($saver);
        $response->assertRedirect(route('admin.savers.show', $saver));

        $user = User::where('email', 'dewi@siswa.test')->first();
        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('dewiPassword123', $user->password));
    }

    public function test_admin_open_account_fails_if_password_mismatches_confirmation(): void
    {
        $member = Member::create([
            'student_id_number'   => '14007',
            'full_name'           => 'Doni Firmansyah',
            'grade_level'         => '10',
            'class_name'          => 'X AKL 2',
            'major'               => 'AKL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 'doni@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $response = $this->actingAs($this->admin)->post(route('admin.savers.store'), [
            'member_id'             => $member->id,
            'initial_deposit'       => 25000,
            'security_pin'          => '654321',
            'password'              => 'password123',
            'password_confirmation' => 'passwordBeda',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertNull(Saver::where('member_id', $member->id)->first());
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

    public function test_mass_grade_promotion_respects_excluded_students_who_stay_in_grade(): void
    {
        $m10Passing = Member::create([
            'student_id_number'   => '16010',
            'full_name'           => 'Siswa Naik Kelas',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 1',
            'major'               => 'RPL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 'naik@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $m10Retained = Member::create([
            'student_id_number'   => '16011',
            'full_name'           => 'Siswa Tinggal Kelas',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 1',
            'major'               => 'RPL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 'tinggal@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $result = $this->memberService->promoteGrades([$m10Retained->id]);

        $this->assertEquals(1, $result['promoted_to_11']);
        $this->assertEquals(1, $result['excluded_count']);

        $m10Passing->refresh();
        $m10Retained->refresh();

        // The passing student advanced to 11
        $this->assertEquals('11', $m10Passing->grade_level);
        $this->assertEquals('XI RPL 1', $m10Passing->class_name);

        // The retained student remained in grade 10 and original class
        $this->assertEquals('10', $m10Retained->grade_level);
        $this->assertEquals('X RPL 1', $m10Retained->class_name);
    }

    public function test_admin_can_search_active_students_and_promote_with_exclusions(): void
    {
        $student = Member::create([
            'student_id_number'   => '16099',
            'full_name'           => 'Budi Prakoso',
            'grade_level'         => '11',
            'class_name'          => 'XI TKJ 1',
            'major'               => 'TKJ',
            'cohort'              => '2025',
            'gender'              => 'male',
            'email'               => 'budi@siswa.test',
            'registration_status' => 'unregistered',
        ]);

        $this->actingAs($this->admin);

        // Test search endpoint
        $searchResponse = $this->getJson(route('admin.members.search-active', ['q' => 'Budi']));
        $searchResponse->assertStatus(200);
        $searchResponse->assertJsonFragment([
            'id'                => $student->id,
            'full_name'         => 'Budi Prakoso',
            'student_id_number' => '16099',
        ]);

        // Promote with Budi excluded
        $promoteResponse = $this->post(route('admin.members.promote'), [
            'excluded_member_ids' => [$student->id],
        ]);

        $promoteResponse->assertRedirect();
        $promoteResponse->assertSessionHas('success');

        $student->refresh();
        $this->assertEquals('11', $student->grade_level);
        $this->assertEquals('XI TKJ 1', $student->class_name);
    }
}

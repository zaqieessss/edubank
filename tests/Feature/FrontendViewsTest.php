<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Member;
use App\Models\Saver;
use App\Models\Transaction;
use App\Models\User;
use Database\Seeders\SettingSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendViewsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(SettingSeeder::class);
    }

    public function test_auth_pages_render_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);

        $response = $this->get('/forgot-password');
        $response->assertStatus(200);

        \Illuminate\Support\Facades\DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => 'admin@test.com'],
            ['token' => 'sample-token', 'created_at' => now()]
        );

        $response = $this->get('/verify-otp?email=admin@test.com');
        $response->assertStatus(200);

        $response = $this->get('/reset-password/sample-token?email=admin@test.com');
        $response->assertStatus(200);
    }

    public function test_admin_views_render_successfully(): void
    {
        $admin = User::factory()->create([
            'role'   => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($admin);

        $testMember = Member::create([
            'student_id_number'   => '99988',
            'full_name'           => 'Test Member Route',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 1',
            'major'               => 'RPL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 'testroute@test.com',
            'registration_status' => 'unregistered',
        ]);

        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.members.index'))->assertStatus(200);
        $this->get(route('admin.members.create'))->assertStatus(200);
        $this->get(route('admin.members.edit', $testMember->id))->assertStatus(200);
        $this->get(route('admin.savers.index'))->assertStatus(200);
        $this->get(route('admin.savers.create'))->assertStatus(200);
        $this->get(route('admin.accounts.index'))->assertStatus(200);
        $this->get(route('admin.users.index'))->assertStatus(200);
        $this->get(route('admin.users.create'))->assertStatus(200);
        $this->get(route('admin.users.edit', $admin->id))->assertStatus(200);
        $this->get(route('admin.daily-closings.index'))->assertStatus(200);
        $this->get(route('admin.accounting-journals.index'))->assertStatus(200);
        $this->get(route('admin.transactions.index'))->assertStatus(200);
        $this->get(route('admin.settings.index'))->assertStatus(200);
    }

    public function test_admin_can_view_saver_detail_and_toggle_status(): void
    {
        $admin = User::factory()->create([
            'role'   => 'admin',
            'status' => 'active',
        ]);

        $saverUser = User::factory()->create([
            'role'   => 'saver',
            'status' => 'active',
        ]);

        $member = Member::create([
            'student_id_number'   => '10099',
            'full_name'           => 'Budi Santoso',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 1',
            'major'               => 'RPL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 'budi@siswa.test',
            'registration_status' => 'registered',
        ]);

        $saver = Saver::create([
            'member_id'         => $member->id,
            'user_id'           => $saverUser->id,
            'account_number'    => 'RPL-2026-0099',
            'student_id_number' => '10099',
            'full_name'         => 'Budi Santoso',
            'grade_level'       => '10',
            'class_name'        => 'X RPL 1',
            'major'             => 'RPL',
            'cohort'            => '2026',
            'security_pin'      => bcrypt('123456'),
            'qr_code_path'      => 'qr_codes/RPL-2026-0099.png',
            'joined_date'       => now()->toDateString(),
            'status'            => 'active',
        ]);

        $account = Account::create([
            'saver_id'       => $saver->id,
            'account_number' => 'RPL-2026-0099',
            'balance'        => 50000,
            'status'         => 'active',
            'opened_date'    => now()->toDateString(),
        ]);

        $this->actingAs($admin);

        // Can view detail page
        $response = $this->get(route('admin.savers.show', $saver->id));
        $response->assertStatus(200);
        $response->assertSee('Budi Santoso');
        $response->assertSee('RPL-2026-0099');

        // Toggle status: Active -> Inactive
        $toggleRes = $this->post(route('admin.savers.toggle-status', $saver->id));
        $toggleRes->assertRedirect();
        $this->assertEquals('inactive', $saver->fresh()->status);
        $this->assertEquals('frozen', $account->fresh()->status);

        // Toggle status: Inactive -> Active
        $toggleRes2 = $this->post(route('admin.savers.toggle-status', $saver->id));
        $toggleRes2->assertRedirect();
        $this->assertEquals('active', $saver->fresh()->status);
        $this->assertEquals('active', $account->fresh()->status);
    }

    public function test_teller_views_render_successfully(): void
    {
        $teller = User::factory()->create([
            'role'   => 'teller',
            'status' => 'active',
        ]);

        $this->actingAs($teller);

        $this->get(route('teller.dashboard'))->assertStatus(200);
        $this->get(route('teller.transactions.index'))->assertStatus(200);
        $this->get(route('teller.transactions.scanner'))->assertStatus(200);
        $this->get(route('teller.daily-closings.index'))->assertStatus(200);
        $this->get(route('teller.daily-closings.create'))->assertStatus(200);
    }

    public function test_saver_views_render_successfully(): void
    {
        $member = Member::create([
            'student_id_number'   => '2026001',
            'full_name'           => 'Ahmad Fajar',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 1',
            'major'               => 'RPL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 'ahmad.fajar@siswa.edubank.test',
            'registration_status' => 'registered',
        ]);

        $saverUser = User::factory()->create([
            'email'  => 'ahmad.fajar@siswa.edubank.test',
            'role'   => 'saver',
            'status' => 'active',
        ]);

        $saver = Saver::create([
            'member_id'         => $member->id,
            'user_id'           => $saverUser->id,
            'account_number'    => 'RPL-2026-0001',
            'student_id_number' => '2026001',
            'full_name'         => 'Ahmad Fajar',
            'grade_level'       => '10',
            'class_name'        => 'X RPL 1',
            'major'             => 'RPL',
            'cohort'            => '2026',
            'security_pin'      => bcrypt('123456'),
            'qr_code_path'      => 'qr_codes/RPL-2026-0001.png',
            'joined_date'       => now()->toDateString(),
        ]);

        Account::create([
            'saver_id'       => $saver->id,
            'account_number' => 'RPL-2026-0001',
            'balance'        => 50000,
            'status'         => 'active',
            'opened_date'    => now()->toDateString(),
        ]);

        $this->actingAs($saverUser);

        $this->get(route('saver.dashboard'))->assertStatus(200);
        $this->get(route('saver.mutation'))->assertStatus(200);
    }

    public function test_daily_closing_flow_with_approval_and_rejection(): void
    {
        $admin = User::factory()->create([
            'role'   => 'admin',
            'status' => 'active',
        ]);

        $teller = User::factory()->create([
            'role'   => 'teller',
            'status' => 'active',
        ]);

        $closing = \App\Models\DailyClosing::create([
            'teller_id'         => $teller->id,
            'closing_date'      => now()->toDateString(),
            'opening_balance'   => 0,
            'total_deposits'    => 50000,
            'total_withdrawals' => 0,
            'system_balance'    => 50000,
            'physical_cash'     => 50000,
            'difference'        => 0,
            'status'            => 'submitted',
            'vault_status'      => 'with_teller',
            'notes'             => 'Catatan closing teller',
        ]);

        // 1. Admin can view show page
        $this->actingAs($admin);
        $response = $this->get(route('admin.daily-closings.show', $closing->id));
        $response->assertStatus(200);
        $response->assertSee('Tolak Laporan');
        $response->assertSee('Verifikasi & Masukkan ke Brankas', false);

        // 2. Admin rejects closing with reason
        $rejectResponse = $this->post(route('admin.daily-closings.reject', $closing->id), [
            'rejection_reason' => 'Uang fisik kurang Rp 10.000, mohon hitung ulang.',
        ]);
        $rejectResponse->assertRedirect();
        $closing->refresh();
        $this->assertEquals('rejected', $closing->status);
        $this->assertEquals('Uang fisik kurang Rp 10.000, mohon hitung ulang.', $closing->rejection_reason);

        // 3. Teller can view rejection in index and create page
        $this->actingAs($teller);
        $tellerIndex = $this->get(route('teller.daily-closings.index'));
        $tellerIndex->assertStatus(200);
        $tellerIndex->assertSee('Ditolak Admin');
        $tellerIndex->assertSee('Uang fisik kurang Rp 10.000, mohon hitung ulang.');

        $tellerCreate = $this->get(route('teller.daily-closings.create'));
        $tellerCreate->assertStatus(200);
        $tellerCreate->assertSee('Laporan Tutup Kas Anda Ditolak oleh Admin');
        $tellerCreate->assertSee('Uang fisik kurang Rp 10.000, mohon hitung ulang.');

        // 4. Teller can re-submit
        $resubmit = $this->post(route('teller.daily-closings.store'), [
            'physical_cash' => 0,
            'notes'         => 'Sudah dihitung ulang pas Rp 0.',
        ]);
        $resubmit->assertRedirect(route('teller.daily-closings.index'));
        $closing->refresh();
        $this->assertEquals('submitted', $closing->status);
        $this->assertNull($closing->rejection_reason);

        // 5. Admin approves closing
        $this->actingAs($admin);
        $approveResponse = $this->post(route('admin.daily-closings.approve', $closing->id));
        $approveResponse->assertRedirect();
        $closing->refresh();
        $this->assertEquals('approved', $closing->status);
        $this->assertEquals('transferred_to_vault', $closing->vault_status);
    }

    public function test_admin_can_view_monthly_transactions_report_and_detail(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'status' => 'active']);
        $teller = User::factory()->create(['role' => 'teller', 'status' => 'active']);
        $saverUser = User::factory()->create(['role' => 'saver', 'status' => 'active']);

        $member = Member::create([
            'student_id_number'   => '10088',
            'full_name'           => 'Siswa Transaksi',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 1',
            'major'               => 'RPL',
            'cohort'              => '2026',
            'gender'              => 'male',
            'email'               => 'siswatrx@test.com',
            'registration_status' => 'registered',
        ]);

        $saver = Saver::create([
            'user_id'           => $saverUser->id,
            'member_id'         => $member->id,
            'account_number'    => 'RPL-2026-0088',
            'student_id_number' => '10088',
            'full_name'         => 'Siswa Transaksi',
            'class_name'        => 'X RPL 1',
            'major'             => 'RPL',
            'cohort'            => '2026',
            'security_pin'      => '123456',
            'joined_date'       => now(),
            'balance'           => 100000,
            'status'            => 'active',
        ]);

        $account = \App\Models\Account::create([
            'saver_id'       => $saver->id,
            'account_number' => $saver->account_number,
            'account_type'   => 'regular_savings',
            'balance'        => 100000,
            'status'         => 'active',
            'opened_date'    => now(),
        ]);

        $transaction = Transaction::create([
            'transaction_code' => 'TRX-TEST-0001',
            'saver_id'         => $saver->id,
            'account_id'       => $account->id,
            'user_id'          => $teller->id,
            'transaction_type' => 'deposit',
            'amount'           => 50000,
            'balance_before'   => 50000,
            'balance_after'    => 100000,
            'description'      => 'Setoran Tunai Percobaan',
            'transaction_date' => now(),
        ]);

        $this->actingAs($admin);

        // 1. Check monthly report view
        $indexResponse = $this->get(route('admin.transactions.index', [
            'month' => now()->format('m'),
            'year'  => now()->format('Y'),
        ]));
        $indexResponse->assertStatus(200);
        $indexResponse->assertSee('TRX-TEST-0001');
        $indexResponse->assertSee('Siswa Transaksi');
        $indexResponse->assertSee('Setoran');

        // 2. Check detail view
        $showResponse = $this->get(route('admin.transactions.show', $transaction->id));
        $showResponse->assertStatus(200);
        $showResponse->assertSee('TRX-TEST-0001');
        $showResponse->assertSee('Siswa Transaksi');
        $showResponse->assertSee('Rp 50.000');

        // 3. Check printable PDF view
        $printResponse = $this->get(route('admin.transactions.print', [
            'month' => now()->format('m'),
            'year'  => now()->format('Y'),
        ]));
        $printResponse->assertStatus(200);
        $printResponse->assertSee('LAPORAN MUTASI KAS TRANSAKSI TABUNGAN SISWA');
        $printResponse->assertSee('TRX-TEST-0001');
        $printResponse->assertSee('Siswa Transaksi');
    }
}

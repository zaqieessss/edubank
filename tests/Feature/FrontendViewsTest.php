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

        $this->get(route('admin.dashboard'))->assertStatus(200);
        $this->get(route('admin.members.index'))->assertStatus(200);
        $this->get(route('admin.savers.index'))->assertStatus(200);
        $this->get(route('admin.savers.create'))->assertStatus(200);
        $this->get(route('admin.accounts.index'))->assertStatus(200);
        $this->get(route('admin.users.index'))->assertStatus(200);
        $this->get(route('admin.daily-closings.index'))->assertStatus(200);
        $this->get(route('admin.accounting-journals.index'))->assertStatus(200);
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
}

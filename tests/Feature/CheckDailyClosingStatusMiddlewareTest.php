<?php

namespace Tests\Feature;

use App\Models\DailyClosing;
use App\Models\Member;
use App\Models\Saver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckDailyClosingStatusMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_teller_cannot_access_scanner_or_transaction_after_daily_closing()
    {
        $teller = User::factory()->create([
            'role'   => 'teller',
            'status' => 'active',
        ]);

        $saverUser = User::factory()->create([
            'role'   => 'saver',
            'status' => 'active',
        ]);

        $member = Member::create([
            'student_id_number'   => 'NIS889900',
            'full_name'           => 'Rina Nasabah',
            'grade_level'         => '10',
            'class_name'          => 'X AKL 1',
            'major'               => 'Akuntansi',
            'cohort'              => 2026,
            'gender'              => 'female',
            'email'               => 'rina@edubank.test',
            'registration_status' => 'registered',
        ]);

        $saver = Saver::create([
            'member_id'         => $member->id,
            'user_id'           => $saverUser->id,
            'account_number'    => '1099887766',
            'student_id_number' => 'NIS889900',
            'full_name'         => 'Rina Nasabah',
            'class_name'        => 'X AKL 1',
            'major'             => 'Akuntansi',
            'cohort'            => 2026,
            'joined_date'       => now(),
            'security_pin'      => \Illuminate\Support\Facades\Hash::make('123456'),
            'balance'           => 100000,
            'status'            => 'active',
        ]);

        // 1. Belum tutup buku -> Akses normal
        $response = $this->actingAs($teller)->get(route('teller.transactions.scanner'));
        $response->assertStatus(200);

        // 2. Teller melakukan closing kas hari ini
        DailyClosing::create([
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
        ]);

        // 3. Buka scanner lagi -> dialihkan ke dashboard dengan session alert
        $responseAfterClosing = $this->actingAs($teller)->get(route('teller.transactions.scanner'));
        $responseAfterClosing->assertRedirect(route('teller.dashboard'));
        $responseAfterClosing->assertSessionHas('daily_closed_error');

        // 4. Paksa akses form create transaksi -> dialihkan ke dashboard
        $createResponse = $this->actingAs($teller)->get(route('teller.transactions.create', $saver));
        $createResponse->assertRedirect(route('teller.dashboard'));
        $createResponse->assertSessionHas('daily_closed_error');

        // 5. Coba via AJAX/JSON search account -> return status 403 Forbidden
        $jsonResponse = $this->actingAs($teller)->postJson(route('teller.transactions.search'), [
            'query' => $saver->account_number,
        ]);
        $jsonResponse->assertStatus(403);
        $jsonResponse->assertJson([
            'success' => false,
            'closed'  => true,
        ]);
    }
}

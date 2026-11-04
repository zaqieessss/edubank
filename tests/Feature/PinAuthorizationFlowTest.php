<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\PinAuthorizationRequest;
use App\Models\Saver;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PinAuthorizationFlowTest extends TestCase
{
    use RefreshDatabase;

    protected User $teller;
    protected User $saverUser;
    protected Saver $saver;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup Teller User
        $this->teller = User::factory()->create([
            'role'   => 'teller',
            'status' => 'active',
        ]);

        $this->saverUser = User::factory()->create([
            'role'   => 'saver',
            'status' => 'active',
            'email'  => 'budi@edubank.test',
        ]);

        $member = \App\Models\Member::create([
            'student_id_number'   => 'NIS998877',
            'full_name'           => 'Budi Siswa Teladan',
            'grade_level'         => '10',
            'class_name'          => 'X RPL 1',
            'major'               => 'Rekayasa Perangkat Lunak',
            'cohort'              => 2026,
            'gender'              => 'male',
            'email'               => 'budi@edubank.test',
            'registration_status' => 'registered',
        ]);

        // 2. Setup Saver & Connected User
        $this->saver = Saver::create([
            'member_id'         => $member->id,
            'user_id'           => $this->saverUser->id,
            'account_number'    => '1012345678',
            'student_id_number' => 'NIS998877',
            'full_name'         => 'Budi Siswa Teladan',
            'class_name'        => 'X RPL 1',
            'major'             => 'Rekayasa Perangkat Lunak',
            'cohort'            => 2026,
            'joined_date'       => now(),
            'balance'           => 100000,
            'security_pin'      => Hash::make('123456'),
        ]);

        Account::create([
            'saver_id'       => $this->saver->id,
            'account_number' => $this->saver->account_number,
            'balance'        => 100000,
            'opened_date'    => now(),
            'status'         => 'active',
        ]);

        $this->saverUser->update(['saver_id' => $this->saver->id]);
    }

    public function test_teller_can_request_mobile_pin(): void
    {
        $this->actingAs($this->teller);

        $response = $this->postJson(route('teller.transactions.request-pin', $this->saver->id), [
            'amount'      => 50000,
            'description' => 'Tarik di Loket 1',
        ]);

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertDatabaseHas('pin_authorization_requests', [
            'saver_id'  => $this->saver->id,
            'teller_id' => $this->teller->id,
            'amount'    => 50000,
            'status'    => 'pending',
        ]);
    }

    public function test_saver_can_detect_and_approve_pending_request_from_mobile(): void
    {
        // 1. Create a pending request
        $authRequest = PinAuthorizationRequest::create([
            'saver_id'   => $this->saver->id,
            'teller_id'  => $this->teller->id,
            'amount'     => 50000,
            'token'      => 'TEST_TOKEN_12345',
            'status'     => 'pending',
            'expires_at' => now()->addMinutes(2),
        ]);

        // 2. Saver polls pending
        Sanctum::actingAs($this->saverUser);
        $pollResponse = $this->getJson('/api/v1/saver/pin-authorization/pending');
        $pollResponse->assertOk()
            ->assertJson([
                'success'     => true,
                'has_pending' => true,
                'data'        => [
                    'id'     => $authRequest->id,
                    'amount' => 50000,
                ],
            ]);

        // 3. Saver approves with correct PIN
        $verifyResponse = $this->postJson("/api/v1/saver/pin-authorization/{$authRequest->id}/verify", [
            'pin' => '123456',
        ]);

        $verifyResponse->assertOk()
            ->assertJson([
                'success' => true,
            ]);

        $this->assertEquals('approved', $authRequest->fresh()->status);
        $this->assertNotNull($authRequest->fresh()->pin_verified_at);
    }

    public function test_saver_fails_with_wrong_pin(): void
    {
        $authRequest = PinAuthorizationRequest::create([
            'saver_id'   => $this->saver->id,
            'teller_id'  => $this->teller->id,
            'amount'     => 50000,
            'token'      => 'TEST_TOKEN_WRONG',
            'status'     => 'pending',
            'expires_at' => now()->addMinutes(2),
        ]);

        Sanctum::actingAs($this->saverUser);
        $response = $this->postJson("/api/v1/saver/pin-authorization/{$authRequest->id}/verify", [
            'pin' => '999999', // Wrong PIN
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);

        $this->assertEquals('pending', $authRequest->fresh()->status);
    }

    public function test_teller_can_complete_withdrawal_using_approved_token_without_raw_pin(): void
    {
        $authRequest = PinAuthorizationRequest::create([
            'saver_id'        => $this->saver->id,
            'teller_id'       => $this->teller->id,
            'amount'          => 40000,
            'token'           => 'APPROVED_TOKEN_XYZ',
            'status'          => 'approved',
            'pin_verified_at' => now(),
            'expires_at'      => now()->addMinutes(2),
        ]);

        $this->actingAs($this->teller);

        // Teller submits withdrawal with authorization_token only
        $response = $this->post(route('teller.transactions.withdraw', $this->saver->id), [
            'amount'              => 40000,
            'authorization_token' => 'APPROVED_TOKEN_XYZ',
            'description'         => 'Penarikan loket via mobile auth',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Saver balance updated: 100.000 - 40.000 = 60.000
        $this->assertEquals(60000, $this->saver->fresh()->balance);
        $this->assertEquals('completed', $authRequest->fresh()->status);

        $this->assertDatabaseHas('transactions', [
            'saver_id'         => $this->saver->id,
            'transaction_type' => 'withdrawal',
            'amount'           => 40000,
        ]);
    }
}

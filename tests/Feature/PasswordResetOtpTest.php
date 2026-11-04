<?php

namespace Tests\Feature;

use App\Mail\PasswordResetOtpMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PasswordResetOtpTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name'     => 'Nasabah Test',
            'email'    => 'nasabah@edubank.test',
            'password' => Hash::make('oldpassword'),
            'role'     => 'saver',
            'status'   => 'active',
        ]);
    }

    public function test_forgot_password_page_loads_with_login_design(): void
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
        $response->assertSee('Lupa Kata Sandi?');
        $response->assertSee('Bank Mini SMKN 1 Bantul');
    }

    public function test_user_can_request_otp_via_email(): void
    {
        Mail::fake();

        $response = $this->post(route('password.email'), [
            'email' => 'nasabah@edubank.test',
        ]);

        $response->assertRedirect(route('password.otp.view'));
        $response->assertSessionHas('success');
        $response->assertSessionHas('password_reset_email', 'nasabah@edubank.test');

        Mail::assertSent(PasswordResetOtpMail::class, function ($mail) {
            return $mail->hasTo('nasabah@edubank.test') && strlen($mail->otp) === 6;
        });

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => 'nasabah@edubank.test',
        ]);
    }

    public function test_request_otp_fails_for_unregistered_email(): void
    {
        $response = $this->post(route('password.email'), [
            'email' => 'unknown@edubank.test',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_user_can_verify_correct_otp_and_redirects_to_password_reset(): void
    {
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => 'nasabah@edubank.test'],
            ['token' => '123456', 'created_at' => now()]
        );

        session(['password_reset_email' => 'nasabah@edubank.test']);

        $viewResponse = $this->get(route('password.otp.view'));
        $viewResponse->assertStatus(200);
        $viewResponse->assertSee('Verifikasi Kode OTP');

        $verifyResponse = $this->post(route('password.otp.verify'), [
            'email' => 'nasabah@edubank.test',
            'otp'   => '123456',
        ]);

        $verifyResponse->assertRedirect();
        $verifyResponse->assertSessionHas('success');

        // Verify token in DB was updated to a long secure token
        $record = DB::table('password_reset_tokens')->where('email', 'nasabah@edubank.test')->first();
        $this->assertNotEquals('123456', $record->token);
        $this->assertEquals(60, strlen($record->token));
    }

    public function test_verify_otp_fails_with_incorrect_code(): void
    {
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => 'nasabah@edubank.test'],
            ['token' => '123456', 'created_at' => now()]
        );

        $verifyResponse = $this->post(route('password.otp.verify'), [
            'email' => 'nasabah@edubank.test',
            'otp'   => '999999',
        ]);

        $verifyResponse->assertSessionHasErrors(['otp']);
    }

    public function test_user_can_resend_otp(): void
    {
        Mail::fake();

        $response = $this->post(route('password.otp.resend'), [
            'email' => 'nasabah@edubank.test',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Mail::assertSent(PasswordResetOtpMail::class);
    }

    public function test_user_can_reset_password_with_valid_token_and_login_with_new_password(): void
    {
        $token = 'valid-secure-token-12345678901234567890123456789012345678901234567890';
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => 'nasabah@edubank.test'],
            ['token' => $token, 'created_at' => now()]
        );

        $pageResponse = $this->get(route('password.reset', ['token' => $token, 'email' => 'nasabah@edubank.test']));
        $pageResponse->assertStatus(200);
        $pageResponse->assertSee('Buat Kata Sandi Baru');

        $resetResponse = $this->post(route('password.update'), [
            'token'                 => $token,
            'email'                 => 'nasabah@edubank.test',
            'password'              => 'newsecurepassword123',
            'password_confirmation' => 'newsecurepassword123',
        ]);

        $resetResponse->assertRedirect(route('login'));
        $resetResponse->assertSessionHas('success');

        // Token record should be deleted
        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => 'nasabah@edubank.test',
        ]);

        // Verify user can login with new password
        $loginResponse = $this->post(route('login.post'), [
            'email'    => 'nasabah@edubank.test',
            'password' => 'newsecurepassword123',
        ]);

        $loginResponse->assertRedirect(route('saver.dashboard'));
        $this->assertAuthenticatedAs($this->user);
    }

    public function test_authenticated_saver_can_request_password_reset_otp_from_mobile(): void
    {
        Mail::fake();

        $token = $this->user->createToken('test-mobile')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ])->postJson('/api/v1/saver/request-password-reset-otp');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        Mail::assertSent(PasswordResetOtpMail::class, function ($mail) {
            return $mail->hasTo($this->user->email) && strlen($mail->otp) === 6;
        });

        $this->assertDatabaseHas('password_reset_tokens', [
            'email' => "password_change_{$this->user->email}",
        ]);
    }

    public function test_authenticated_saver_can_reset_password_with_valid_otp(): void
    {
        $token = $this->user->createToken('test-mobile')->plainTextToken;
        $otp = '654321';
        $cacheKey = "password_change_{$this->user->email}";

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $cacheKey],
            [
                'token'      => Hash::make($otp),
                'created_at' => now(),
            ]
        );

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept'        => 'application/json',
        ])->postJson('/api/v1/saver/reset-password-otp', [
            'otp'                   => $otp,
            'password'              => 'brandnewpassword123',
            'password_confirmation' => 'brandnewpassword123',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ]);

        $this->user->refresh();
        $this->assertTrue(Hash::check('brandnewpassword123', $this->user->password));

        $this->assertDatabaseMissing('password_reset_tokens', [
            'email' => $cacheKey,
        ]);
    }
}

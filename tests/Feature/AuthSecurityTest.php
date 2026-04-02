<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_otp_regenerates_the_session_before_authenticating(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $user->forceFill([
            'is_verified' => false,
            'email_verified_at' => null,
        ])->save();

        Cache::put('otp_'.$user->email, '123456', now()->addMinutes(2));

        $this->withSession(['email' => $user->email])
            ->withCookie(config('session.cookie'), 'fixed-session-id')
            ->post(route('verify.otp'), [
                'code' => ['1', '2', '3', '4', '5', '6'],
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionMissing('email');

        $this->assertAuthenticatedAs($user);
        $this->assertNotSame(
            'fixed-session-id',
            app('session')->getId(),
            'OTP verification should rotate the session identifier.'
        );
    }

    public function test_forgot_password_does_not_reveal_whether_an_email_exists(): void
    {
        $response = $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => 'missing@example.com',
            ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('info', 'If the email exists in our system, we sent a 6-digit OTP to your inbox.');
        $response->assertSessionMissing('error');
    }

    public function test_login_page_includes_security_headers(): void
    {
        $this->withoutVite();

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->assertHeader(
            'Content-Security-Policy',
            "default-src 'self'; base-uri 'self'; frame-ancestors 'self'; form-action 'self'; img-src 'self' data: https:; font-src 'self' data: https:; style-src 'self' 'unsafe-inline' https:; script-src 'self' 'unsafe-inline' https:; connect-src 'self' https:; object-src 'none'; upgrade-insecure-requests"
        );
    }
}<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_otp_regenerates_the_session_before_authenticating(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $user->forceFill([
            'is_verified' => false,
            'email_verified_at' => null,
        ])->save();

        Cache::put('otp_'.$user->email, '123456', now()->addMinutes(2));

        $this->withSession(['email' => $user->email])
            ->withCookie(config('session.cookie'), 'fixed-session-id')
            ->post(route('verify.otp'), [
                'code' => ['1', '2', '3', '4', '5', '6'],
            ])
            ->assertRedirect(route('dashboard'))
            ->assertSessionMissing('email');

        $this->assertAuthenticatedAs($user);
        $this->assertNotSame(
            'fixed-session-id',
            app('session')->getId(),
            'OTP verification should rotate the session identifier.'
        );
    }

    public function test_forgot_password_does_not_reveal_whether_an_email_exists(): void
    {
        $response = $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => 'missing@example.com',
            ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas('info', 'If the email exists in our system, we sent a 6-digit OTP to your inbox.');
        $response->assertSessionMissing('error');
    }

    public function test_login_page_includes_security_headers(): void
    {
        $this->withoutVite();

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->assertHeader(
            'Content-Security-Policy',
            "default-src 'self'; base-uri 'self'; frame-ancestors 'self'; form-action 'self'; img-src 'self' data: https:; font-src 'self' data: https:; style-src 'self' 'unsafe-inline' https:; script-src 'self' 'unsafe-inline' https:; connect-src 'self' https:; object-src 'none'; upgrade-insecure-requests"
        );
    }
}

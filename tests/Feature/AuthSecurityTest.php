<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AuthSecurityTest extends TestCase
{
    use RefreshDatabase;

    public function test_verify_otp_redirects_to_login_without_auto_authenticating(): void
    {
        $user = User::factory()->create([
            'email' => 'customer@example.com',
        ]);

        $user->forceFill([
            'is_verified' => false,
            'email_verified_at' => null,
        ])->save();

        Cache::put('otp_' . $user->email, '123456', now()->addMinutes(2));

        $this->withSession(['email' => $user->email])
            ->withCookie(config('session.cookie'), 'fixed-session-id')
            ->post(route('verify.otp'), [
                'code' => ['1', '2', '3', '4', '5', '6'],
            ])
            ->assertRedirect(route('login'))
            ->assertSessionMissing('email')
            ->assertSessionHas('toast_type', 'success');

        $this->assertGuest();
        $this->assertTrue((bool) $user->fresh()?->is_verified);
    }

    public function test_forgot_password_does_not_reveal_whether_an_email_exists(): void
    {
        $response = $this->from(route('password.request'))
            ->post(route('password.email'), [
                'email' => 'missing@example.com',
            ]);

        $response->assertRedirect(route('password.request'));
        $response->assertSessionHas(
            'info',
            'If the email exists in our system, we sent a 6-digit OTP to your inbox.'
        );
        $response->assertSessionMissing('error');
    }

    public function test_login_page_includes_security_headers(): void
    {
        $this->withoutVite();

        $response = $this->get(route('login'));

        $response->assertOk();
        $response->assertHeader('X-Frame-Options', 'SAMEORIGIN');
        $response->assertHeader('X-Content-Type-Options', 'nosniff');
        $response->assertHeader('X-XSS-Protection', '1; mode=block');
        $response->assertHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->assertHeader('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
        $response->assertHeader('Content-Security-Policy', $this->expectedCspForCurrentEnvironment());
    }

    private function expectedCspForCurrentEnvironment(): string
    {
        if (app()->environment('local')) {
            return "default-src * 'unsafe-inline' 'unsafe-eval' data: blob: http: https: ws: wss:;";
        }

        $appOrigin = '';
        $appUrl = (string) config('app.url', '');
        if ($appUrl !== '') {
            $scheme = parse_url($appUrl, PHP_URL_SCHEME);
            $host = parse_url($appUrl, PHP_URL_HOST);
            $isLoopbackHost = in_array($host, ['localhost', '127.0.0.1', '::1'], true);
            if ($scheme && $host && ! $isLoopbackHost) {
                $appOrigin = "{$scheme}://{$host}";
            }
        }

        $formAction = "form-action 'self'";
        if ($appOrigin !== '') {
            $formAction .= " {$appOrigin}";
        }

        return implode('; ', [
            "default-src 'self'",
            "base-uri 'self'",
            "frame-ancestors 'self'",
            "frame-src 'self' https://www.google.com https://www.gstatic.com https://recaptcha.google.com",
            $formAction,
            "img-src 'self' data: https:",
            "font-src 'self' data: https:",
            "style-src 'self' 'unsafe-inline' https:",
            "script-src 'self' 'unsafe-inline' https:",
            "connect-src 'self' https:",
            "object-src 'none'",
            "upgrade-insecure-requests",
        ]);
    }
}

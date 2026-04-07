<?php

namespace App\Providers;

use Illuminate\Queue\Events\JobFailed;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('otp-view', function (Request $request) {
            $email = strtolower((string) $request->session()->get('email', 'guest'));
            return Limit::perMinute(60)->by($email . '|' . $request->ip());
        });

        RateLimiter::for('otp-verify', function (Request $request) {
            $email = strtolower((string) $request->session()->get('email', 'guest'));
            return Limit::perMinute(20)->by($email . '|' . $request->ip());
        });

        RateLimiter::for('otp-resend', function (Request $request) {
            $email = strtolower((string) $request->session()->get('email', 'guest'));
            return Limit::perMinute(10)->by($email . '|' . $request->ip());
        });

        RateLimiter::for('auth-login', function (Request $request) {
            $email = strtolower((string) $request->input('email', 'guest'));
            return Limit::perMinute(10)->by($email . '|' . $request->ip());
        });

        RateLimiter::for('auth-register', function (Request $request) {
            $email = strtolower((string) $request->input('email', 'guest'));
            return Limit::perMinutes(5, 10)->by($email . '|' . $request->ip());
        });

        RateLimiter::for('password-otp-send', function (Request $request) {
            $email = strtolower((string) $request->input('email', 'guest'));
            return Limit::perMinute(8)->by($email . '|' . $request->ip());
        });

        RateLimiter::for('password-otp-verify', function (Request $request) {
            $email = strtolower((string) $request->session()->get('password_reset_email', 'guest'));
            return Limit::perMinute(20)->by($email . '|' . $request->ip());
        });

        RateLimiter::for('password-otp-resend', function (Request $request) {
            $email = strtolower((string) $request->session()->get('password_reset_email', 'guest'));
            return Limit::perMinute(8)->by($email . '|' . $request->ip());
        });

        RateLimiter::for('password-update', function (Request $request) {
            $email = strtolower((string) $request->session()->get('password_reset_email', 'guest'));
            return Limit::perMinute(8)->by($email . '|' . $request->ip());
        });

        Queue::failing(function (JobFailed $event): void {
            $payload = $event->job->payload();
            $displayName = data_get($payload, 'displayName') ?: $event->job->resolveName();

            Log::channel('queue')->error('Queue job failed', [
                'connection' => $event->connectionName,
                'queue' => $event->job->getQueue(),
                'job_id' => $event->job->getJobId(),
                'job_name' => $displayName,
                'exception' => $event->exception->getMessage(),
            ]);
        });
    }
}

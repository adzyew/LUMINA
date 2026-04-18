<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class ProviderCallbackController extends Controller
{
    private function splitName(string $fullName): array
    {
        $normalized = trim(preg_replace('/\s+/u', ' ', $fullName) ?? '');
        if ($normalized === '') {
            return ['first_name' => null, 'last_name' => null];
        }

        $parts = preg_split('/\s+/', $normalized, 2) ?: [];

        return [
            'first_name' => $parts[0] ?? null,
            'last_name' => $parts[1] ?? null,
        ];
    }

    public function __invoke(string $provider)
    {
        if (!in_array($provider, ['google', 'facebook', 'github'])) {
            return redirect()->route('login')->withErrors(['provider' => 'Invalid provider']);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['provider' => 'Authentication failed. Please try again.']);
        }

        $socialEmail = (string) ($socialUser->getEmail() ?? '');
        $socialName = (string) ($socialUser->getName() ?? '');
        $splitName = $this->splitName($socialName);
        $providerId = (string) ($socialUser->getId() ?? '');
        $providerToken = null;
        $providerRefreshToken = null;

        if ($socialEmail === '') {
            return redirect()->route('login')->withErrors(['provider' => 'Unable to read email from provider account.']);
        }

        // 1. Find existing user first to enforce archived-account policy
        $existingUser = User::where('email', $socialEmail)->first();
        if ($existingUser && $existingUser->archived_at) {
            return redirect()->route('login')->withErrors([
                'email' => 'This account has been archived. Contact admin.',
            ]);
        }

        // 2. Create or update OAuth details after archive check passes.
        // Important: do not overwrite user-edited names on every social login.
        $oauthPayload = [
            'provider_id'            => $providerId,
            'provider_name'          => $provider,
            'provider_token'         => $providerToken,
            'provider_refresh_token' => $providerRefreshToken,
        ];

        if (!$existingUser) {
            // First-time social signup: seed names from provider.
            $createPayload = array_merge($oauthPayload, [
                'email' => $socialEmail,
                'name'  => $socialName,
            ]);

            if (Schema::hasColumn('users', 'first_name')) {
                $createPayload['first_name'] = $splitName['first_name'];
            }
            if (Schema::hasColumn('users', 'last_name')) {
                $createPayload['last_name'] = $splitName['last_name'];
            }

            $user = User::create($createPayload);
        } else {
            // Existing account: update only OAuth linkage and fill missing name fields.
            $updates = $oauthPayload;

            if (!$existingUser->name && $socialName !== '') {
                $updates['name'] = $socialName;
            }

            if (Schema::hasColumn('users', 'first_name') && !$existingUser->first_name && !empty($splitName['first_name'])) {
                $updates['first_name'] = $splitName['first_name'];
            }
            if (Schema::hasColumn('users', 'last_name') && !$existingUser->last_name && !empty($splitName['last_name'])) {
                $updates['last_name'] = $splitName['last_name'];
            }

            $existingUser->update($updates);
            $user = $existingUser->fresh();
        }

        if ($user->is_verified) {
            Auth::login($user);
            request()->session()->regenerate();
            session(['otp_verified' => true]); // Satisfy the custom middleware

            // Redirect based on role
            if ($user->is_admin || $user->hasRole('admin')) {
                return redirect()->route('admin.admin_dashboard'); 
            }
            if ($user->can('inventory.view') || $user->can('sales.view') || $user->can('returns.manage') || $user->can('deliveries.manage') || $user->can('reviews.moderate')) {
                return redirect()->route('admin.staff.dashboard'); 
            }

            return redirect()->route('dashboard');
        }

        // 3. Keep user as guest until OTP is verified
        session(['email' => $user->email]);


        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(2);
        $resendAvailableAt = now()->addMinutes(2);


        Cache::put('otp_' . $user->email, $otp, $expiresAt);
        Cache::put('otp_expires_' . $user->email, $expiresAt, $expiresAt);
        Cache::put('otp_resend_available_at_' . $user->email, $resendAvailableAt, $resendAvailableAt);

        try {
            Mail::to($user->email)->send(new TestMail($otp));
        } catch (\Exception $e) {
            Log::error('OAuth OTP email failed: ' . $e->getMessage());
        }

        return redirect()->route('verify-sms'); 
    }
}

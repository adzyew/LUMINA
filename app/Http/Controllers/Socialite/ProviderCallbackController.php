<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Mail\TestMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class ProviderCallbackController extends Controller
{
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

        // 1. Find or create the user
        $user = User::updateOrCreate(
            ['email' => $socialUser->email],
            [
                'name'                   => $socialUser->name,
                'provider_id'            => $socialUser->id,
                'provider_name'          => $provider,
                'provider_token'         => $socialUser->token,
                'provider_refresh_token' => $socialUser->refreshToken,
            ]
        );

        if ($user->is_verified) {
            Auth::login($user);
            session(['otp_verified' => true]); // Satisfy the custom middleware

            // Redirect based on role
            if ($user->is_admin || $user->hasRole('admin')) {
                return redirect()->route('admin.admin_dashboard'); 
            }
            if ($user->can('inventory.view') || $user->can('sales.view') || $user->can('deliveries.manage')) {
                return redirect()->route('admin.staff.dashboard'); 
            }

            return redirect()->route('dashboard');
        }

        // 3. Keep user as guest until OTP is verified
        session(['email' => $user->email]);


        $otp = rand(100000, 999999);
        $expiresAt = now()->addMinutes(5);


        Cache::put('otp_' . $user->email, $otp, $expiresAt);
        Cache::put('otp_expires_' . $user->email, $expiresAt, $expiresAt);

        Mail::to($user->email)->send(new TestMail($otp)); 

        return redirect()->route('verify-sms'); 
    }
}
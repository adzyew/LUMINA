<?php

namespace App\Http\Controllers\Socialite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;


class ProviderController extends Controller
{
     public function __invoke(Request $request, $provider)
    {
        if(!in_array($provider, ['google', 'facebook', 'github'])) {
            return redirect()->route('login')->withErrors(['provider' => 'Invalid provider']);
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\Exception $e) {
            return redirect()->route('login')->withErrors(['provider' => 'Something went wrong. Please try again.']);
        }
    }
}

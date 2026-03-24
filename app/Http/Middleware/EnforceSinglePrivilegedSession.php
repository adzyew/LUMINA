<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class EnforceSinglePrivilegedSession
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (!$user instanceof User || !$user->isPrivilegedStaff()) {
            return $next($request);
        }

        $sessionId = $request->session()->getId();
        $cacheKey = 'active_privileged_session_' . $user->id;
        $activeSessionId = Cache::get($cacheKey);
        $ttl = now()->addMinutes((int) config('session.lifetime', 120));

        if (is_string($activeSessionId) && $activeSessionId !== '' && $activeSessionId !== $sessionId) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'This account is already logged in on another device or browser.',
            ]);
        }

        Cache::put($cacheKey, $sessionId, $ttl);

        return $next($request);
    }
}

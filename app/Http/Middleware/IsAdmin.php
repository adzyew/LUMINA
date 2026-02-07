<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;


class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        /** @var \App\Models\User|null $user */
            $user = Auth::user(); // <--- Changed from auth()->user()

            if ($user && $user->is_admin) {
                return $next($request);
            }

            return redirect('/')->with('error', 'You do not have admin access.');
    }
}

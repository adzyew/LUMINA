<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfStaffOrAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->hasRole('admin') || ($user->is_admin ?? false)) {
                return redirect()->route('admin.admin_dashboard')
                    ->with('error', 'Admin accounts cannot access the customer area.');
            }

            if (
                $user->hasRole('inventory_manager') ||
                $user->hasRole('sales_staff') ||
                $user->hasRole('delivery_staff') ||
                $user->hasRole('feedback_moderator') ||
                $user->hasRole('staff') ||
                $user->can('inventory.view') ||
                $user->can('sales.view') ||
                $user->can('returns.manage') ||
                $user->can('deliveries.manage') ||
                $user->can('reviews.moderate')
            ) {
                return redirect()->route('admin.staff.dashboard')
                    ->with('error', 'Staff accounts cannot access the customer area.');
            }
        }

        return $next($request);
    }
}

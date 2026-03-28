<?php

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserManagementController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\FeedbackModerationController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\Admin\StaffDashboardController;

Route::middleware(['auth', \App\Http\Middleware\PreventArchivedUser::class])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('analytics', [AnalyticsController::class, 'index'])
            ->middleware('permission:sales.view')
            ->name('analytics.index');
        Route::get('analytics/export', [AnalyticsController::class, 'exportOrders'])
            ->middleware('permission:sales.view')
            ->name('analytics.export');

        // Staff dashboards (inventory, sales, delivery)
        Route::get('staff/dashboard', [StaffDashboardController::class, 'index'])
            ->middleware('role_or_permission:admin|inventory.view|sales.view|deliveries.manage|reviews.moderate')
            ->name('staff.dashboard');
        Route::get('inventory/dashboard', [StaffDashboardController::class, 'inventoryDashboard'])
            ->middleware('permission:inventory.view')
            ->name('inventory.dashboard');
        Route::get('sales/dashboard', [StaffDashboardController::class, 'salesDashboard'])
            ->middleware('permission:sales.view')
            ->name('sales.dashboard');
        Route::get('delivery/dashboard', [StaffDashboardController::class, 'deliveryDashboard'])
            ->middleware('permission:deliveries.manage')
            ->name('delivery.dashboard');

        // Admin/staff self profile
        Route::get('profile', [ProfileController::class, 'show'])
            ->middleware('role_or_permission:admin|inventory_manager|sales_staff|delivery_staff|feedback_moderator|inventory.view|sales.view|deliveries.manage|reviews.moderate')
            ->name('profile.show');
        Route::get('profile/edit', [ProfileController::class, 'edit'])
            ->middleware('role_or_permission:admin|inventory_manager|sales_staff|delivery_staff|feedback_moderator|inventory.view|sales.view|deliveries.manage|reviews.moderate')
            ->name('profile.edit');
        Route::put('profile', [ProfileController::class, 'update'])
            ->middleware('role_or_permission:admin|inventory_manager|sales_staff|delivery_staff|feedback_moderator|inventory.view|sales.view|deliveries.manage|reviews.moderate')
            ->name('profile.update');

        Route::get('feedback', [FeedbackModerationController::class, 'index'])
            ->middleware('permission:reviews.moderate')
            ->name('feedback.index');
        Route::patch('feedback/{review}/approve', [FeedbackModerationController::class, 'approve'])
            ->middleware('permission:reviews.moderate')
            ->name('feedback.approve');
        Route::patch('feedback/{review}/reject', [FeedbackModerationController::class, 'reject'])
            ->middleware('permission:reviews.moderate')
            ->name('feedback.reject');
        Route::patch('feedback/{review}/remove', [FeedbackModerationController::class, 'remove'])
            ->middleware('permission:reviews.moderate')
            ->name('feedback.remove');
        Route::patch('feedback/{review}/flag', [FeedbackModerationController::class, 'flag'])
            ->middleware('permission:reviews.moderate')
            ->name('feedback.flag');

        Route::get('/dashboard', function () {
            $completedStatuses = ['confirmed', 'processing', 'shipped', 'delivered'];
            $totalOrders = Order::count();
            $totalProducts = Product::count();
            $totalUsers = User::count();
            $totalRevenue = Order::whereIn('status', $completedStatuses)->sum('total_price');
            $thisMonthRevenue = Order::whereIn('status', $completedStatuses)->whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->sum('total_price');
            $lastMonthRevenue = Order::whereIn('status', $completedStatuses)->whereMonth('created_at', now()->subMonth()->month)->whereYear('created_at', now()->subMonth()->year)->sum('total_price');
            $revenueChange = $lastMonthRevenue > 0 ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;
            $ordersByStatus = Order::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status')->toArray();
            $recentOrders = Order::with('user')->latest()->take(5)->get();

            $adminUsers = User::role('admin')->count();
            $staffUsers = User::whereHas('roles', function ($query) {
                $query->whereNotIn('name', ['admin', 'customer']);
            })->count();
            $customerUsers = User::whereDoesntHave('roles')->count();

            $inventoryStatuses = [
                'total' => $totalProducts,
                'low_stock' => Product::whereBetween('stock_quantity', [1, 5])->count(),
                'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            ];
            $inventoryStatuses['in_stock'] = max(0, $inventoryStatuses['total'] - $inventoryStatuses['out_of_stock']);

            $salesStatuses = [
                'pending' => $ordersByStatus['pending'] ?? 0,
                'confirmed' => $ordersByStatus['confirmed'] ?? 0,
                'processing' => $ordersByStatus['processing'] ?? 0,
                'shipped' => $ordersByStatus['shipped'] ?? 0,
                'delivered' => $ordersByStatus['delivered'] ?? 0,
                'cancelled' => $ordersByStatus['cancelled'] ?? 0,
            ];

            $deliveryStatuses = [
                'to_ship' => $salesStatuses['processing'],
                'in_transit' => $salesStatuses['shipped'],
                'delivered' => $salesStatuses['delivered'],
                'pending' => $salesStatuses['pending'],
                'cancelled' => $salesStatuses['cancelled'],
            ];

            return view('admin.admin_dashboard', compact(
                'totalOrders',
                'totalProducts',
                'totalUsers',
                'totalRevenue',
                'recentOrders',
                'revenueChange',
                'ordersByStatus',
                'thisMonthRevenue',
                'lastMonthRevenue',
                'inventoryStatuses',
                'salesStatuses',
                'deliveryStatuses',
                'adminUsers',
                'staffUsers',
                'customerUsers'
            ));
        })->middleware('role:admin')->name('admin_dashboard');

        // USERS (Admin Only)
        Route::resource('users', UserManagementController::class)
            ->only(['index', 'create', 'store', 'show', 'edit', 'update'])
                ->middleware('role:admin');

        // archive/unarchive/delete/verify users
        Route::post('users/{user}/archive', [UserManagementController::class, 'archive'])
            ->middleware('role:admin')
            ->name('users.archive');
        Route::post('users/{user}/unarchive', [UserManagementController::class, 'unarchive'])
            ->middleware('role:admin')
            ->name('users.unarchive');
        Route::post('users/{user}/verify', [UserManagementController::class, 'verify'])
            ->middleware('role:admin')
            ->name('users.verify');
        Route::delete('users/{user}', [UserManagementController::class, 'destroy'])
            ->middleware('role:admin')
            ->name('users.destroy');

        // ROLES (Admin Only) - manage role permissions
        Route::get('roles', [UserManagementController::class, 'rolesIndex'])
            ->middleware('role:admin')
            ->name('roles.index');
        Route::post('roles', [UserManagementController::class, 'storeRole'])
            ->middleware('role:admin')
            ->name('roles.store');
        Route::delete('roles/{role}', [UserManagementController::class, 'destroyRole'])
            ->middleware('role:admin')
            ->name('roles.destroy');
        Route::get('roles/{role}', [UserManagementController::class, 'showRole'])
            ->middleware('role:admin')
            ->name('roles.show');
        Route::get('roles/{role}/edit', [UserManagementController::class, 'editRole'])
            ->middleware('role:admin')
            ->name('roles.edit');
        Route::put('roles/{role}', [UserManagementController::class, 'updateRole'])
            ->middleware('role:admin')
            ->name('roles.update');
        Route::post('roles/{role}/archive', [UserManagementController::class, 'archiveRole'])
            ->middleware('role:admin')
            ->name('roles.archive');
        Route::post('roles/{role}/restore', [UserManagementController::class, 'restoreRole'])
            ->middleware('role:admin')
            ->name('roles.restore');

        // STAFF management (Admin Only)
        Route::get('staff', [StaffController::class, 'index'])
            ->middleware('role:admin')
            ->name('staff.index');
        Route::get('staff/create', [StaffController::class, 'create'])
            ->middleware('role:admin')
            ->name('staff.create');
        Route::post('staff', [StaffController::class, 'store'])
            ->middleware('role:admin')
            ->name('staff.store');
        Route::get('staff/{user}/edit', [StaffController::class, 'edit'])
            ->middleware('role:admin')
            ->name('staff.edit');
        Route::put('staff/{user}', [StaffController::class, 'update'])
            ->middleware('role:admin')
            ->name('staff.update');
        Route::delete('staff/{user}', [StaffController::class, 'destroy'])
            ->middleware('role:admin')
            ->name('staff.destroy');

        // PRODUCTS
        // ↓ RULE: all static segments (e.g. "create") and sub-resource routes
        //   (e.g. "{product}/json") MUST come BEFORE the plain "{product}" show
        //   route, otherwise Laravel swallows them as a product ID.

        Route::get('products', [ProductController::class, 'index'])
            ->middleware('role_or_permission:admin|inventory.view')
            ->name('products.index');

        Route::get('products/create', [ProductController::class, 'create'])
            ->middleware('role_or_permission:admin|inventory.create')
            ->name('products.create');

        Route::post('products', [ProductController::class, 'store'])
            ->middleware('role_or_permission:admin|inventory.create')
            ->name('products.store');

        // ← MUST be before products/{product} (show) so "json" isn't treated as an ID
        Route::get('products/{product}/json', [ProductController::class, 'showJson'])
            ->middleware('role_or_permission:admin|inventory.view')
            ->name('products.json');


        // archive/unarchive products (must come before products/{product})
        Route::post('products/{product}/archive', [ProductController::class, 'archive'])
            ->middleware('role_or_permission:admin|inventory.archive')
            ->name('products.archive');
        Route::post('products/{product}/unarchive', [ProductController::class, 'unarchive'])
            ->middleware('role_or_permission:admin|inventory.archive')
            ->name('products.unarchive');

        Route::get('products/{product}', [ProductController::class, 'show'])
            ->middleware('role_or_permission:admin|inventory.view')
            ->name('products.show');

        Route::get('products/{product}/edit', [ProductController::class, 'edit'])
            ->middleware('role_or_permission:admin|inventory.update')
            ->name('products.edit');

        Route::put('products/{product}', [ProductController::class, 'update'])
            ->middleware('role_or_permission:admin|inventory.update')
            ->name('products.update');

        Route::delete('products/{product}', [ProductController::class, 'destroy'])
            ->middleware('role_or_permission:admin|inventory.delete')
            ->name('products.destroy');

        // ORDERS
        Route::get('orders', [AdminOrderController::class, 'index'])->middleware('permission:sales.view')->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->middleware('permission:sales.view')->name('orders.show');
        Route::get('orders/{order}/email-preview/placed', [AdminOrderController::class, 'previewPlacedEmail'])->middleware('permission:sales.view')->name('orders.email_preview.placed');
        Route::get('orders/{order}/email-preview/status', [AdminOrderController::class, 'previewStatusEmail'])->middleware('permission:sales.view')->name('orders.email_preview.status');
        Route::put('orders/{order}', [AdminOrderController::class, 'update'])->middleware('permission:sales.view')->name('orders.update');
        Route::delete('orders/{order}', [AdminOrderController::class, 'destroy'])->middleware('permission:sales.view')->name('orders.destroy');

        // SALES
        Route::get('sales', [SalesController::class, 'index'])
            ->middleware('permission:sales.view')
            ->name('sales.index');

        // DELIVERIES (track orders in delivery)
        Route::get('deliveries', [DeliveryController::class, 'index'])
            ->middleware('permission:deliveries.manage')
            ->name('deliveries.index');
        Route::get('deliveries/{order}', [DeliveryController::class, 'show'])
            ->middleware('permission:deliveries.manage')
            ->name('deliveries.show');
        Route::put('deliveries/{order}', [DeliveryController::class, 'update'])
            ->middleware('permission:deliveries.manage')
            ->name('deliveries.update');

        // HELP — accessible by all authenticated admin/staff
        Route::get('help', fn() => view('admin.help'))->name('help');
    });
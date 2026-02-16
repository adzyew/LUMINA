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
use App\Http\Controllers\SalesController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\Admin\StaffDashboardController;

Route::middleware(['auth'])
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
        Route::get('staff', [StaffDashboardController::class, 'index'])
            ->middleware('role_or_permission:admin|inventory.view|sales.view|deliveries.manage')
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

            return view('admin.admin_dashboard', compact(
                'totalOrders',
                'totalProducts',
                'totalUsers',
                'totalRevenue',
                'recentOrders',
                'revenueChange',
                'ordersByStatus'
            ));
        })->middleware('role:admin')->name('admin_dashboard');

        // USERS (Admin Only)
        Route::resource('users', UserManagementController::class)
                ->only(['index', 'create', 'store', 'edit', 'update'])
                ->middleware('role:admin');

        // ROLES (Admin Only) - manage role permissions
        Route::get('roles', [UserManagementController::class, 'rolesIndex'])
            ->middleware('role:admin')
            ->name('roles.index');
        Route::get('roles/{role}/edit', [UserManagementController::class, 'editRole'])
            ->middleware('role:admin')
            ->name('roles.edit');
        Route::put('roles/{role}', [UserManagementController::class, 'updateRole'])
            ->middleware('role:admin')
            ->name('roles.update');

        // PRODUCTS
        Route::get('products', [ProductController::class, 'index'])
            ->middleware('permission:inventory.view')
            ->name('products.index');

        Route::get('products/create', [ProductController::class, 'create'])
            ->middleware('permission:inventory.create')
            ->name('products.create');

        Route::post('products', [ProductController::class, 'store'])
            ->middleware('permission:inventory.create')
            ->name('products.store');

        Route::get('products/{product}', [ProductController::class, 'show'])
            ->middleware('permission:inventory.view')
            ->name('products.show');

        Route::get('products/{product}/edit', [ProductController::class, 'edit'])
            ->middleware('permission:inventory.update')
            ->name('products.edit');

        Route::put('products/{product}', [ProductController::class, 'update'])
            ->middleware('permission:inventory.update')
            ->name('products.update');

        Route::delete('products/{product}', [ProductController::class, 'destroy'])
            ->middleware('permission:inventory.delete')
            ->name('products.destroy');

        // ORDERS
        Route::get('orders', [AdminOrderController::class, 'index'])->middleware('permission:sales.view')->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->middleware('permission:sales.view')->name('orders.show');
        Route::put('orders/{order}', [AdminOrderController::class, 'update'])->middleware('permission:sales.view')->name('orders.update');

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
    });

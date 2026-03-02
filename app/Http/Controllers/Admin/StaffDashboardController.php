<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffDashboardController extends Controller
{
    /**
     * Redirect staff to their role-specific dashboard.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        dd([
            'Roles' => $user->getRoleNames(), 
            'Permissions' => $user->getAllPermissions()->pluck('name')
        ]);

        if ($user->hasRole('inventory_manager') || $user->can('inventory.view')) {
            return redirect()->route('admin.inventory.dashboard');
        }
        if ($user->hasRole('sales_staff') || $user->can('sales.view')) {
            return redirect()->route('admin.sales.dashboard');
        }
        if ($user->hasRole('delivery_staff') || $user->can('deliveries.manage')) {
            return redirect()->route('admin.delivery.dashboard');
        }
        if ($user->hasRole('staff')) {
            return redirect()->route('admin.inventory.dashboard');
        }

        return redirect()->route('admin.admin_dashboard');
    }

    /**
     * Inventory Manager Dashboard - products, stock levels.
     */
    public function inventoryDashboard(): View
    {
        $totalProducts = Product::count();
        $lowStock = Product::whereBetween('stock_quantity', [1, 5])->count();
        $outOfStock = Product::where('stock_quantity', '<=', 0)->count();
        $recentProducts = Product::latest()->take(5)->get();

        return view('admin.staff.inventory_dashboard', compact(
            'totalProducts',
            'lowStock',
            'outOfStock',
            'recentProducts'
        ));
    }

    /**
     * Sales Staff Dashboard - orders, revenue.
     */
    public function salesDashboard(): View
    {
        $completedStatuses = ['confirmed', 'processing', 'shipped', 'delivered'];
        $totalRevenue = Order::whereIn('status', $completedStatuses)->sum('total_price');
        $totalOrders = Order::whereIn('status', $completedStatuses)->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $thisMonthRevenue = Order::whereIn('status', $completedStatuses)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $recentOrders = Order::with('user')->latest()->take(8)->get();
        $ordersByStatus = Order::selectRaw('status, count(*) as count')->groupBy('status')->pluck('count', 'status')->toArray();

        return view('admin.staff.sales_dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'thisMonthRevenue',
            'recentOrders',
            'ordersByStatus'
        ));
    }

    /**
     * Delivery Staff Dashboard - shipments to process.
     */
    public function deliveryDashboard(): View
    {
        $toShip = Order::where('status', 'processing')->count();
        $shipped = Order::where('status', 'shipped')->count();
        $delivered = Order::where('status', 'delivered')->count();
        $pendingShipment = Order::with(['user', 'items.product'])
            ->whereIn('status', ['processing', 'shipped'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.staff.delivery_dashboard', compact(
            'toShip',
            'shipped',
            'delivered',
            'pendingShipment'
        ));
    }
}

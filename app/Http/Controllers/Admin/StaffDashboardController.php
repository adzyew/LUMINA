<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffDashboardController extends Controller
{
    /**
     * Staff hub dashboard with quick status previews per department.
     */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('admin')) {
            return redirect()->route('admin.admin_dashboard');
        }

        $canInventory = $user->can('inventory.view');
        $canSales = $user->can('sales.view') || $user->can('returns.manage');
        $canDelivery = $user->can('deliveries.manage');
        $canFeedback = $user->can('reviews.moderate');

        if (!$canInventory && !$canSales && !$canDelivery && !$canFeedback) {
            return redirect()->route('admin.admin_dashboard');
        }

        $inventoryStats = null;
        if ($canInventory) {
            $inventoryStats = [
                'total' => Product::count(),
                'low_stock' => Product::whereBetween('stock_quantity', [1, 5])->count(),
                'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            ];
        }

        $salesStats = null;
        if ($canSales) {
            $salesStats = [
                'pending' => Order::where('status', 'pending')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'delivered' => Order::where('status', 'delivered')->count(),
            ];
        }

        $deliveryStats = null;
        if ($canDelivery) {
            $deliveryStats = [
                'to_ship' => Order::where('status', 'processing')->count(),
                'in_transit' => Order::where('status', 'shipped')->count(),
                'delivered' => Order::where('status', 'delivered')->count(),
            ];
        }

        $feedbackStats = null;
        if ($canFeedback) {
            $feedbackStats = [
                'pending' => Review::where('status', 'pending')->count(),
                'flagged' => Review::where('is_flagged', true)->count(),
                'approved' => Review::where('status', 'approved')->count(),
            ];
        }

        return view('admin.staff.dashboard', compact(
            'inventoryStats',
            'salesStats',
            'deliveryStats',
            'feedbackStats'
        ));
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

        $salesStatuses = [
            'pending' => Order::where('status', 'pending')->count(),
            'confirmed' => Order::where('status', 'confirmed')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'shipped' => Order::where('status', 'shipped')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.staff.sales_dashboard', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'thisMonthRevenue',
            'recentOrders',
            'ordersByStatus',
            'salesStatuses'
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

        $deliveryStatuses = [
            'processing' => $toShip,
            'shipped' => $shipped,
            'delivered' => $delivered,
            'pending' => Order::where('status', 'pending')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.staff.delivery_dashboard', compact(
            'toShip',
            'shipped',
            'delivered',
            'pendingShipment',
            'deliveryStatuses'
        ));
    }
}

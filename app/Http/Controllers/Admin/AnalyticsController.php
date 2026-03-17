<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AnalyticsController extends Controller
{
    public function index()
    {
        $completedStatuses = ['confirmed', 'processing', 'shipped', 'delivered'];

        $totalRevenue = Order::whereIn('status', $completedStatuses)->sum('total_price');
        $thisMonthRevenue = Order::whereIn('status', $completedStatuses)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_price');
        $lastMonthRevenue = Order::whereIn('status', $completedStatuses)
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->sum('total_price');

        $revenueChange = $lastMonthRevenue > 0
            ? round((($thisMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;

        $ordersByStatus = Order::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $topProducts = OrderItem::with('product')
            ->selectRaw('product_id, sum(quantity) as total_sold')
            ->whereHas('order', fn ($q) => $q->whereIn('status', $completedStatuses))
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->take(5)
            ->get();

        $topCustomers = User::whereHas('orders', fn ($q) => $q->whereIn('status', $completedStatuses))
            ->withSum(['orders as total_spent' => function ($q) use ($completedStatuses) {
                $q->whereIn('status', $completedStatuses);
            }], 'total_price')
            ->orderByDesc('total_spent')
            ->take(10)
            ->get();

        $ordersLast24Hours = [];
        for ($i = 23; $i >= 0; $i--) {
            $hour = now()->subHours($i);
            $ordersLast24Hours[$hour->format('H:i')] = Order::whereIn('status', $completedStatuses)
                ->whereBetween('created_at', [$hour->copy()->startOfHour(), $hour->copy()->endOfHour()])
                ->sum('total_price');
        }

        $ordersLast7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $ordersLast7Days[$date->format('M d')] = Order::whereIn('status', $completedStatuses)
                ->whereDate('created_at', $date)
                ->sum('total_price');
        }

        $ordersLast30Days = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $ordersLast30Days[$date->format('M d')] = Order::whereIn('status', $completedStatuses)
                ->whereDate('created_at', $date)
                ->sum('total_price');
        }

        $ordersLast12Months = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $ordersLast12Months[$month->format('M Y')] = Order::whereIn('status', $completedStatuses)
                ->whereMonth('created_at', $month->month)
                ->whereYear('created_at', $month->year)
                ->sum('total_price');
        }

        return view('admin.analytics.index', compact(
            'totalRevenue',
            'thisMonthRevenue',
            'lastMonthRevenue',
            'revenueChange',
            'ordersByStatus',
            'topProducts',
            'topCustomers',
            'ordersLast24Hours',
            'ordersLast7Days',
            'ordersLast30Days',
            'ordersLast12Months'
        ));
    }

    public function exportOrders(Request $request): StreamedResponse
    {
        $request->validate([
            'from'   => 'nullable|date',
            'to'     => 'nullable|date|after_or_equal:from',
            'status' => 'nullable|in:pending,confirmed,processing,shipped,delivered,cancelled',
        ]);

        $query = Order::with(['user', 'items.product'])->latest();

        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders-' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order ID', 'Date', 'Customer', 'Email', 'Status', 'Total (₱)', 'Items']);

            foreach ($orders as $order) {
                $items = $order->items->map(fn ($i) => $i->product->name . ' x' . $i->quantity)->implode('; ');
                fputcsv($handle, [
                    $order->display_order_number,
                    $order->created_at->format('Y-m-d H:i'),
                    $order->user->name ?? 'Guest',
                    $order->user->email ?? '',
                    $order->status,
                    $order->total_price,
                    $items,
                ]);
            }
            fclose($handle);
        }, 200, $headers);
    }
}

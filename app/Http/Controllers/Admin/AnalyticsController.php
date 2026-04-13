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

        $cashflowMonths = collect(range(5, 0))->map(fn (int $offset) => now()->copy()->subMonths($offset));

        $cashflowLabels = $cashflowMonths
            ->map(fn ($month) => $month->format('M Y'))
            ->values()
            ->all();

        $cashInflowSeries = $cashflowMonths
            ->map(function ($month) use ($completedStatuses): float {
                return (float) Order::query()
                    ->whereIn('status', $completedStatuses)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total_price');
            })
            ->values()
            ->all();

        $cashOutflowSeries = $cashflowMonths
            ->map(function ($month) use ($completedStatuses): float {
                return (float) Order::query()
                    ->whereIn('status', $completedStatuses)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('discount_amount');
            })
            ->values()
            ->all();

        $netCashflowSeries = collect($cashInflowSeries)
            ->map(fn ($inflow, $index): float => (float) $inflow - (float) ($cashOutflowSeries[$index] ?? 0))
            ->values()
            ->all();

        $totalCashInflow = array_sum($cashInflowSeries);
        $totalCashOutflow = array_sum($cashOutflowSeries);
        $totalNetCashflow = $totalCashInflow - $totalCashOutflow;

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
            'ordersLast12Months',
            'cashflowLabels',
            'cashInflowSeries',
            'cashOutflowSeries',
            'netCashflowSeries',
            'totalCashInflow',
            'totalCashOutflow',
            'totalNetCashflow'
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

        $filename = 'orders-' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }

            fputcsv($handle, ['Order ID', 'Date', 'Customer', 'Email', 'Status', 'Total (PHP)', 'Items']);

            $query->chunkById(200, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    $items = $order->items->map(function ($item) {
                        $name = $item->product->name ?? ('Product #' . $item->product_id);
                        return $name . ' x' . $item->quantity;
                    })->implode('; ');

                    fputcsv($handle, [
                        $order->display_order_number,
                        optional($order->created_at)->format('Y-m-d H:i'),
                        $order->user->name ?? 'Guest',
                        $order->user->email ?? '',
                        $order->status,
                        number_format((float) $order->total_price, 2, '.', ''),
                        $items,
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma' => 'no-cache',
        ]);
    }
}

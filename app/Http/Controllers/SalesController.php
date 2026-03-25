<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $completedStatuses = ['confirmed', 'processing', 'shipped', 'delivered'];

        $totalRevenue = Order::whereIn('status', $completedStatuses)->sum('total_price');
        $totalOrders = Order::whereIn('status', $completedStatuses)->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        $recentOrders = Order::with('user')
            ->whereIn('status', $completedStatuses)
            ->latest()
            ->take(10)
            ->get();

        $orders = Order::with('user')
            ->whereIn('status', $completedStatuses)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $months = collect(range(5, 0))->map(fn (int $offset) => now()->copy()->subMonths($offset));

        $salesTrendLabels = $months
            ->map(fn ($month) => $month->format('M Y'))
            ->values()
            ->all();

        $salesTrendSeries = $months
            ->map(function ($month) use ($completedStatuses): float {
                return (float) Order::whereIn('status', $completedStatuses)
                    ->whereYear('created_at', $month->year)
                    ->whereMonth('created_at', $month->month)
                    ->sum('total_price');
            })
            ->values()
            ->all();

        $paymentGroupExpression = Schema::hasColumn('orders', 'payment_channel')
            ? "COALESCE(NULLIF(payment_channel, ''), payment_method)"
            : 'payment_method';

        $paymentBreakdown = Order::query()
            ->whereIn('status', $completedStatuses)
            ->selectRaw($paymentGroupExpression . ' AS payment_group, COUNT(*) AS total')
            ->groupBy('payment_group')
            ->pluck('total', 'payment_group');

        $paymentMixLabels = [];
        $paymentMixSeries = [];
        foreach ($paymentBreakdown as $channel => $count) {
            $paymentMixLabels[] = $this->formatPaymentChannel((string) $channel);
            $paymentMixSeries[] = (int) $count;
        }

        return view('admin.sales.index', compact(
            'totalRevenue',
            'totalOrders',
            'pendingOrders',
            'recentOrders',
            'orders',
            'salesTrendLabels',
            'salesTrendSeries',
            'paymentMixLabels',
            'paymentMixSeries'
        ));
    }

    private function formatPaymentChannel(string $channel): string
    {
        $normalized = strtolower(trim($channel));

        return match ($normalized) {
            'cod' => 'COD',
            'gcash' => 'GCash',
            'paymaya', 'maya' => 'Maya',
            'grab_pay', 'grabpay' => 'GrabPay',
            'card' => 'Card',
            'online', '' => 'Online',
            default => ucfirst(str_replace('_', ' ', $normalized)),
        };
    }
}

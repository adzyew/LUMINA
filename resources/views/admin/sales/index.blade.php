@extends('admin.admin_layout')

@section('title', 'Sales | Lumina Admin')

@section('content')
<header class="mb-8">
    @include('partials.favicon')
    <h1 class="text-3xl font-playfair font-bold text-gray-900">Sales</h1>
    <p class="text-gray-600 text-sm mt-1">View revenue and sales reports.</p>
</header>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h3 class="text-gray-500 text-sm font-medium mb-1">Total Revenue</h3>
        <p class="text-3xl font-bold text-amber-300">₱{{ number_format($totalRevenue, 2) }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h3 class="text-gray-500 text-sm font-medium mb-1">Completed Orders</h3>
        <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h3 class="text-gray-500 text-sm font-medium mb-1">Pending Orders</h3>
        <p class="text-3xl font-bold text-amber-400">{{ $pendingOrders }}</p>
    </div>
</div>

<div class="grid grid-cols-1 xl:grid-cols-3 gap-4 mb-8">
    <div class="xl:col-span-2 bg-white border border-gray-200 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-lg font-bold text-gray-900">Revenue Trend</h3>
            <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Last 6 Months</span>
        </div>
        <div id="salesTrendChart" class="h-[280px]"></div>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-3">Payment Mix</h3>
        @if(count($paymentMixSeries) > 0)
            <div id="paymentMixChart" class="h-[280px]"></div>
        @else
            <div class="h-[280px] flex items-center justify-center text-sm text-gray-500 border border-dashed border-gray-200 rounded-xl">
                No completed order payments yet.
            </div>
        @endif
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-2xl p-6">
    <h3 class="text-lg font-bold text-gray-900 mb-4">Sales History</h3>
    <table class="w-full text-left">
        <thead>
            <tr class="text-gray-500 text-sm border-b border-gray-200">
                <th class="pb-3 font-medium">Order ID</th>
                <th class="pb-3 font-medium">Customer</th>
                <th class="pb-3 font-medium">Payment</th>
                <th class="pb-3 font-medium">Status</th>
                <th class="pb-3 font-medium text-right">Amount</th>
            </tr>
        </thead>
        <tbody class="text-sm">
            @forelse($orders as $order)
            <tr class="border-b border-gray-100 hover:bg-gray-50">
                <td class="py-4 text-gray-900">#{{ $order->display_order_number }}</td>
                <td class="py-4 text-gray-600">{{ $order->user->name ?? 'Guest' }}</td>
                <td class="py-4 text-gray-600">{{ $order->payment_channel_label }}</td>
                <td class="py-4"><span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-400">{{ ucfirst($order->status) }}</span></td>
                <td class="py-4 text-right text-amber-300 font-bold">₱{{ number_format($order->total_price, 2) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="py-12 text-center text-gray-500">No sales yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())<div class="mt-6">{{ $orders->links() }}</div>@endif

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trendLabels = @json($salesTrendLabels);
        const trendSeries = @json($salesTrendSeries);
        const paymentLabels = @json($paymentMixLabels);
        const paymentSeries = @json($paymentMixSeries);

        new ApexCharts(document.querySelector('#salesTrendChart'), {
            chart: {
                type: 'area',
                height: 280,
                toolbar: { show: false },
                fontFamily: 'inherit',
            },
            series: [{
                name: 'Revenue',
                data: trendSeries,
            }],
            xaxis: {
                categories: trendLabels,
                labels: { style: { colors: '#6b7280' } },
            },
            yaxis: {
                labels: {
                    style: { colors: '#6b7280' },
                    formatter: function (val) {
                        return 'P' + Number(val).toLocaleString();
                    }
                }
            },
            stroke: { curve: 'smooth', width: 3 },
            dataLabels: { enabled: false },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.35,
                    opacityTo: 0.05,
                    stops: [0, 90, 100],
                },
            },
            colors: ['#f59e0b'],
            grid: { borderColor: '#f3f4f6' },
            tooltip: {
                y: {
                    formatter: function (val) {
                        return 'P' + Number(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    }
                }
            }
        }).render();

        if (paymentSeries.length > 0) {
            new ApexCharts(document.querySelector('#paymentMixChart'), {
                chart: {
                    type: 'donut',
                    height: 280,
                    fontFamily: 'inherit',
                },
                labels: paymentLabels,
                series: paymentSeries,
                colors: ['#f59e0b', '#10b981', '#3b82f6', '#f97316', '#8b5cf6', '#14b8a6'],
                legend: {
                    position: 'bottom',
                    fontSize: '13px',
                    labels: { colors: '#4b5563' },
                },
                dataLabels: { enabled: false },
                stroke: { width: 0 },
            }).render();
        }
    });
</script>
@endsection

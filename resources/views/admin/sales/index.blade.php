@extends('admin.admin_layout')

@section('title', 'Sales | Lumina Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <header>
        <h1 class="text-4xl font-playfair font-bold text-gray-900">Sales</h1>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <article class="rounded-xl border border-amber-200 bg-linear-to-br from-amber-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-amber-700 font-semibold">Total Revenue</p>
            <p class="text-4xl font-bold text-amber-600 mt-2">PHP {{ number_format($totalRevenue, 2) }}</p>
        </article>
        <article class="rounded-xl border border-blue-200 bg-linear-to-br from-blue-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-blue-700 font-semibold">Completed Orders</p>
            <p class="text-4xl font-bold text-blue-700 mt-2">{{ number_format($totalOrders) }}</p>
        </article>
        <article class="rounded-xl border border-orange-200 bg-linear-to-br from-orange-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-orange-700 font-semibold">Pending Orders</p>
            <p class="text-4xl font-bold text-orange-600 mt-2">{{ number_format($pendingOrders) }}</p>
        </article>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
        <section class="xl:col-span-2 bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <h2 class="text-2xl font-playfair font-bold text-gray-900">Revenue Trend</h2>
                <span class="text-xs font-semibold uppercase tracking-wide text-gray-500">Last 6 Months</span>
            </div>
            <div id="salesTrendChart" class="min-h-75"></div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-3">Payment Mix</h2>
            @if(count($paymentMixSeries) > 0)
                <div id="paymentMixChart" class="min-h-75"></div>
            @else
                <div class="min-h-75 flex items-center justify-center text-sm text-gray-500 border border-dashed border-gray-200 rounded-2xl">
                    No completed order payments yet.
                </div>
            @endif
        </section>
    </div>

    <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
        <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-3">Sales History</h2>
        <div class="overflow-x-auto">
            <table class="w-full text-left min-w-215">
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
                    <tr class="border-b border-gray-100 hover:bg-amber-50/60 transition-colors">
                        <td class="py-4 text-gray-900 font-medium">#{{ $order->display_order_number }}</td>
                        <td class="py-4 text-gray-600">{{ $order->user->name ?? 'Guest' }}</td>
                        <td class="py-4 text-gray-600">{{ $order->payment_channel_label }}</td>
                        <td class="py-4">
                            <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="py-4 text-right text-amber-600 font-bold">PHP {{ number_format($order->total_price, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="py-12 text-center text-gray-500">No sales yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    @if($orders->hasPages())
    <div>{{ $orders->links() }}</div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const trendLabels = @json($salesTrendLabels);
    const trendSeries = @json($salesTrendSeries);
    const paymentLabels = @json($paymentMixLabels);
    const paymentSeries = @json($paymentMixSeries);

    new ApexCharts(document.querySelector('#salesTrendChart'), {
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false },
            fontFamily: 'inherit',
        },
        series: [{ name: 'Revenue', data: trendSeries }],
        xaxis: { categories: trendLabels, labels: { style: { colors: '#6b7280' } } },
        yaxis: {
            labels: {
                style: { colors: '#6b7280' },
                formatter: function (val) {
                    return 'PHP ' + Number(val).toLocaleString();
                },
            },
        },
        stroke: { curve: 'smooth', width: 3 },
        dataLabels: { enabled: false },
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.35,
                opacityTo: 0.06,
                stops: [0, 90, 100],
            },
        },
        colors: ['#f59e0b'],
        grid: { borderColor: '#f3f4f6' },
        tooltip: {
            y: {
                formatter: function (val) {
                    return 'PHP ' + Number(val).toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                },
            },
        },
    }).render();

    if (paymentSeries.length > 0) {
        new ApexCharts(document.querySelector('#paymentMixChart'), {
            chart: { type: 'donut', height: 300, fontFamily: 'inherit' },
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

@extends('admin.admin_layout')

@section('title', 'Analytics | Lumina Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <header class="flex flex-col gap-4 xl:flex-row xl:items-end xl:justify-between">
        <div>
            <h1 class="text-4xl font-playfair font-bold text-gray-900">Analytics</h1>
            <p class="text-sm text-gray-600 mt-1">Track revenue, order status, and store cashflow in one view.</p>
        </div>

        <form action="{{ route('admin.analytics.export') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-2 w-full xl:w-auto">
            <input type="date" name="from" value="{{ request('from') }}" class="min-w-42.5 bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900" placeholder="From">
            <input type="date" name="to" value="{{ request('to') }}" class="min-w-42.5 bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900" placeholder="To">
            <select name="status" class="min-w-40 bg-white border border-gray-300 rounded-lg px-3 py-2 text-sm text-gray-900">
                <option value="">All Statuses</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="confirmed" @selected(request('status') === 'confirmed')>Confirmed</option>
                <option value="processing" @selected(request('status') === 'processing')>Processing</option>
                <option value="shipped" @selected(request('status') === 'shipped')>Shipped</option>
                <option value="delivered" @selected(request('status') === 'delivered')>Delivered</option>
                <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
            </select>
            <button type="submit" class="px-4 py-2 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 text-sm">Export CSV</button>
        </form>
    </header>

    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4">
        <article class="rounded-xl border border-amber-200 bg-linear-to-br from-amber-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-amber-700 font-semibold">Total Revenue</p>
            <p class="text-2xl font-bold text-amber-600 mt-2">PHP {{ number_format($totalRevenue, 2) }}</p>
        </article>
        <article class="rounded-xl border border-blue-200 bg-linear-to-br from-blue-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-blue-700 font-semibold">This Month</p>
            <p class="text-2xl font-bold text-blue-700 mt-2">PHP {{ number_format($thisMonthRevenue, 2) }}</p>
        </article>
        <article class="rounded-xl border border-gray-200 bg-linear-to-br from-gray-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-gray-700 font-semibold">Last Month</p>
            <p class="text-2xl font-bold text-gray-900 mt-2">PHP {{ number_format($lastMonthRevenue, 2) }}</p>
        </article>
        <article class="rounded-xl border {{ $revenueChange >= 0 ? 'border-green-200 bg-linear-to-br from-green-50 to-white' : 'border-red-200 bg-linear-to-br from-red-50 to-white' }} p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide {{ $revenueChange >= 0 ? 'text-green-700' : 'text-red-700' }} font-semibold">Revenue Change</p>
            <p class="text-2xl font-bold mt-2 {{ $revenueChange >= 0 ? 'text-green-600' : 'text-red-600' }}">{{ $revenueChange >= 0 ? '+' : '' }}{{ $revenueChange }}%</p>
        </article>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <article class="rounded-xl border border-red-200 bg-linear-to-br from-red-50 to-white p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide text-red-700 font-semibold">Cash Outflow (6 Months)</p>
            <p class="text-2xl font-bold text-red-600 mt-2">PHP {{ number_format($totalCashOutflow, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Discounts and points redeemed</p>
        </article>
        <article class="rounded-xl border {{ $totalNetCashflow >= 0 ? 'border-emerald-200 bg-linear-to-br from-emerald-50 to-white' : 'border-red-200 bg-gradient-to-br from-red-50 to-white' }} p-5 shadow-sm">
            <p class="text-xs uppercase tracking-wide {{ $totalNetCashflow >= 0 ? 'text-emerald-700' : 'text-red-700' }} font-semibold">Net Cashflow (6 Months)</p>
            <p class="text-2xl font-bold mt-2 {{ $totalNetCashflow >= 0 ? 'text-emerald-600' : 'text-red-600' }}">PHP {{ number_format($totalNetCashflow, 2) }}</p>
            <p class="text-xs text-gray-500 mt-1">Inflow minus outflow</p>
        </article>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <div class="flex flex-wrap gap-2 items-center justify-between mb-3">
                <h2 class="text-2xl font-playfair font-bold text-gray-900" id="revenueChartTitle">Revenue (Last 7 Days)</h2>
                <div class="flex gap-1 text-xs">
                    <button onclick="setRevenuePeriod('day')" data-period="day" class="period-btn px-3 py-1.5 rounded-lg font-semibold transition-colors text-gray-500 hover:bg-gray-100">Day</button>
                    <button onclick="setRevenuePeriod('week')" data-period="week" class="period-btn px-3 py-1.5 rounded-lg font-semibold transition-colors bg-amber-300 text-black">Week</button>
                    <button onclick="setRevenuePeriod('month')" data-period="month" class="period-btn px-3 py-1.5 rounded-lg font-semibold transition-colors text-gray-500 hover:bg-gray-100">Month</button>
                    <button onclick="setRevenuePeriod('year')" data-period="year" class="period-btn px-3 py-1.5 rounded-lg font-semibold transition-colors text-gray-500 hover:bg-gray-100">Year</button>
                </div>
            </div>
            <div id="revenueChart" class="min-h-70"></div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-3">Orders by Status</h2>
            <div id="statusChart" class="min-h-70"></div>
        </section>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-3">Top Products</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm min-w-105">
                    <thead>
                        <tr class="text-gray-500 border-b border-gray-200">
                            <th class="pb-3 text-left">Product</th>
                            <th class="pb-3 text-right">Units Sold</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($topProducts as $item)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 text-gray-900">{{ $item->product->name ?? 'Product #'.$item->product_id }}</td>
                            <td class="py-3 text-right text-amber-600 font-bold">{{ $item->total_sold }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="py-8 text-center text-gray-500">No sales data yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
            <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-3">Moneyflow (Last 6 Months)</h2>
            <div id="salesTrendChart" class="min-h-75"></div>
        </section>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const pesoFmt = val => 'PHP ' + parseFloat(val || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    const periodData = {
        day:   { title: 'Revenue (Last 24 Hours)', labels: {!! json_encode(array_keys($ordersLast24Hours)) !!}, values: {!! json_encode(array_values($ordersLast24Hours)) !!} },
        week:  { title: 'Revenue (Last 7 Days)', labels: {!! json_encode(array_keys($ordersLast7Days)) !!}, values: {!! json_encode(array_values($ordersLast7Days)) !!} },
        month: { title: 'Revenue (Last 30 Days)', labels: {!! json_encode(array_keys($ordersLast30Days)) !!}, values: {!! json_encode(array_values($ordersLast30Days)) !!} },
        year:  { title: 'Revenue (Last 12 Months)', labels: {!! json_encode(array_keys($ordersLast12Months)) !!}, values: {!! json_encode(array_values($ordersLast12Months)) !!} },
    };

    const revenueEl = document.getElementById('revenueChart');
    let revenueChart = null;

    if (revenueEl) {
        revenueChart = new ApexCharts(revenueEl, {
            chart: { type: 'bar', height: 280, toolbar: { show: false }, background: 'transparent' },
            theme: { mode: isDark ? 'dark' : 'light' },
            series: [{ name: 'Revenue', data: periodData.week.values }],
            xaxis: { categories: periodData.week.labels },
            colors: ['#f59e0b'],
            dataLabels: { enabled: false },
            plotOptions: { bar: { borderRadius: 8, columnWidth: '52%' } },
            grid: { borderColor: isDark ? 'rgba(255,255,255,0.05)' : '#f3f4f6' },
            yaxis: { labels: { formatter: pesoFmt } },
            tooltip: { y: { formatter: pesoFmt } },
        });
        revenueChart.render();
    }

    window.setRevenuePeriod = function (period) {
        if (!revenueChart) return;
        const data = periodData[period];
        revenueChart.updateOptions({
            series: [{ name: 'Revenue', data: data.values }],
            xaxis: { categories: data.labels },
        });
        document.getElementById('revenueChartTitle').textContent = data.title;
        document.querySelectorAll('.period-btn').forEach(btn => {
            const isActive = btn.dataset.period === period;
            btn.className = 'period-btn px-3 py-1.5 rounded-lg font-semibold transition-colors ' +
                (isActive ? 'bg-amber-300 text-black' : 'text-gray-500 hover:bg-gray-100');
        });
    };

    const statusEl = document.getElementById('statusChart');
    if (statusEl) {
        const statusData = {!! json_encode($ordersByStatus) !!};
        const labels = Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1));
        const values = Object.values(statusData);

        new ApexCharts(statusEl, {
            chart: { type: 'donut', height: 280, background: 'transparent' },
            theme: { mode: isDark ? 'dark' : 'light' },
            series: values,
            labels: labels,
            colors: ['#f59e0b', '#3b82f6', '#8b5cf6', '#a855f7', '#22c55e', '#ef4444'],
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            stroke: { width: 0 },
        }).render();
    }

    const cashflowLabels = {!! json_encode($cashflowLabels) !!};
    const cashInflowSeries = {!! json_encode($cashInflowSeries) !!};
    const cashOutflowSeries = {!! json_encode($cashOutflowSeries) !!};
    const netCashflowSeries = {!! json_encode($netCashflowSeries) !!};

    const cashflowEl = document.getElementById('salesTrendChart');
    if (cashflowEl) {
        new ApexCharts(cashflowEl, {
            chart: { type: 'line', height: 300, toolbar: { show: false }, background: 'transparent' },
            theme: { mode: isDark ? 'dark' : 'light' },
            series: [
                { name: 'Cash Inflow', type: 'column', data: cashInflowSeries },
                { name: 'Cash Outflow', type: 'column', data: cashOutflowSeries },
                { name: 'Net Cashflow', type: 'line', data: netCashflowSeries },
            ],
            xaxis: { categories: cashflowLabels },
            yaxis: { labels: { formatter: pesoFmt } },
            colors: ['#22c55e', '#ef4444', '#f59e0b'],
            stroke: { width: [0, 0, 3], curve: 'smooth' },
            dataLabels: { enabled: false },
            plotOptions: { bar: { columnWidth: '42%', borderRadius: 8 } },
            grid: { borderColor: isDark ? 'rgba(255,255,255,0.05)' : '#f3f4f6' },
            tooltip: { y: { formatter: pesoFmt } },
            legend: { position: 'top' },
        }).render();
    }
});
</script>
@endsection

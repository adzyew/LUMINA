@extends('admin.admin_layout')

@section('title', 'Analytics & CRM | Lumina Admin')

@section('content')
<header class="flex justify-between items-center mb-8">
    <div>
        <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Analytics & CRM</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Sales analytics, top products, and customer insights.</p>
    </div>
    <form action="{{ route('admin.analytics.export') }}" method="GET" class="flex gap-2 items-center">
        <input type="date" name="from" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white" placeholder="From">
        <input type="date" name="to" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white" placeholder="To">
        <select name="status" class="bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg px-3 py-2 text-sm text-gray-900 dark:text-white">
            <option value="">All Statuses</option>
            <option value="pending">Pending</option>
            <option value="confirmed">Confirmed</option>
            <option value="delivered">Delivered</option>
        </select>
        <button type="submit" class="px-4 py-2 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 text-sm">Export CSV</button>
    </form>
</header>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Revenue</h3>
        <p class="text-3xl font-bold text-amber-300">₱{{ number_format($totalRevenue, 2) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">This Month</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($thisMonthRevenue, 2) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Last Month</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">₱{{ number_format($lastMonthRevenue, 2) }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Revenue Change</h3>
        <p class="text-3xl font-bold {{ $revenueChange >= 0 ? 'text-green-400' : 'text-red-400' }}">{{ $revenueChange >= 0 ? '+' : '' }}{{ $revenueChange }}%</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white" id="revenueChartTitle">Revenue (Last 7 Days)</h3>
            <div class="flex gap-1 text-xs">
                <button onclick="setRevenuePeriod('day')" data-period="day" class="period-btn px-3 py-1 rounded-lg font-semibold transition-colors text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10">Day</button>
                <button onclick="setRevenuePeriod('week')" data-period="week" class="period-btn px-3 py-1 rounded-lg font-semibold transition-colors bg-amber-300 text-black">Week</button>
                <button onclick="setRevenuePeriod('month')" data-period="month" class="period-btn px-3 py-1 rounded-lg font-semibold transition-colors text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10">Month</button>
                <button onclick="setRevenuePeriod('year')" data-period="year" class="period-btn px-3 py-1 rounded-lg font-semibold transition-colors text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10">Year</button>
            </div>
        </div>
        <div id="revenueChart"></div>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Orders by Status</h3>
        <div id="statusChart"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Top Products</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 border-b border-gray-200 dark:border-white/10">
                    <th class="pb-3 text-left">Product</th>
                    <th class="pb-3 text-right">Units Sold</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topProducts as $item)
                <tr class="border-b border-gray-100 dark:border-white/5">
                    <td class="py-3 text-gray-900 dark:text-white">{{ $item->product->name ?? 'Product #'.$item->product_id }}</td>
                    <td class="py-3 text-right text-amber-300 font-bold">{{ $item->total_sold }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="py-6 text-center text-gray-500">No sales data yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Top Customers</h3>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-500 border-b border-gray-200 dark:border-white/10">
                    <th class="pb-3 text-left">Customer</th>
                    <th class="pb-3 text-right">Total Spent</th>
                </tr>
            </thead>
            <tbody>
                @forelse($topCustomers as $customer)
                <tr class="border-b border-gray-100 dark:border-white/5">
                    <td class="py-3 text-gray-900 dark:text-white">{{ $customer->name }}</td>
                    <td class="py-3 text-right text-amber-300 font-bold">₱{{ number_format($customer->total_spent ?? 0, 2) }}</td>
                </tr>
                @empty
                <tr><td colspan="2" class="py-6 text-center text-gray-500">No customer data yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    const pesoFmt = val => '₱' + parseFloat(val || 0).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

    const periodData = {
        day:   { title: 'Revenue (Last 24 Hours)', labels: {!! json_encode(array_keys($ordersLast24Hours)) !!}, values: {!! json_encode(array_values($ordersLast24Hours)) !!} },
        week:  { title: 'Revenue (Last 7 Days)',   labels: {!! json_encode(array_keys($ordersLast7Days)) !!},   values: {!! json_encode(array_values($ordersLast7Days)) !!} },
        month: { title: 'Revenue (Last 30 Days)',  labels: {!! json_encode(array_keys($ordersLast30Days)) !!},  values: {!! json_encode(array_values($ordersLast30Days)) !!} },
        year:  { title: 'Revenue (Last 12 Months)',labels: {!! json_encode(array_keys($ordersLast12Months)) !!},values: {!! json_encode(array_values($ordersLast12Months)) !!} },
    };

    const revenueEl = document.getElementById('revenueChart');
    let revenueChart = null;

    if (revenueEl) {
        revenueChart = new ApexCharts(revenueEl, {
            chart: { type: 'bar', height: 250, toolbar: { show: false }, background: 'transparent' },
            theme: { mode: isDark ? 'dark' : 'light' },
            series: [{ name: 'Revenue (₱)', data: periodData.week.values }],
            xaxis: { categories: periodData.week.labels },
            colors: ['#f59e0b'],
            dataLabels: { enabled: false },
            plotOptions: { bar: { borderRadius: 6, columnWidth: '50%' } },
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
            series: [{ name: 'Revenue (₱)', data: data.values }],
            xaxis: { categories: data.labels },
        });
        document.getElementById('revenueChartTitle').textContent = data.title;
        document.querySelectorAll('.period-btn').forEach(btn => {
            const isActive = btn.dataset.period === period;
            btn.className = 'period-btn px-3 py-1 rounded-lg font-semibold transition-colors ' +
                (isActive ? 'bg-amber-300 text-black' : 'text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-white/10');
        });
    };

    const statusEl = document.getElementById('statusChart');
    if (statusEl) {
        const statusData = {!! json_encode($ordersByStatus) !!};
        const labels = Object.keys(statusData).map(s => s.charAt(0).toUpperCase() + s.slice(1));
        const values = Object.values(statusData);
        new ApexCharts(statusEl, {
            chart: { type: 'donut', height: 250, background: 'transparent' },
            theme: { mode: isDark ? 'dark' : 'light' },
            series: values,
            labels: labels,
            colors: ['#f59e0b', '#3b82f6', '#8b5cf6', '#a855f7', '#22c55e', '#ef4444'],
            dataLabels: { enabled: false },
            legend: { position: 'bottom' },
            stroke: { width: 0 },
        }).render();
    }
});
</script>
@endsection

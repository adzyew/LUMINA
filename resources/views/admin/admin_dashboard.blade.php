@extends('admin.admin_layout')

@section('title', 'Admin Dashboard | Lumina')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    @include('admin.staff.partials.topbar', [
        'title' => 'Admin Overview',
        'subtitle' => 'Track performance, orders, inventory, and delivery health in one place.',
    ])

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <article class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-amber-700">Total Revenue</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <svg class="size-5 text-amber-700" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                        <path d="M208 96C190.3 96 176 110.3 176 128L176 192L152 192C138.7 192 128 202.7 128 216C128 229.3 138.7 240 152 240L176 240L176 272L152 272C138.7 272 128 282.7 128 296C128 309.3 138.7 320 152 320L176 320L176 512C176 529.7 190.3 544 208 544C225.7 544 240 529.7 240 512L240 416L336 416C401.6 416 458 376.5 482.7 320L520 320C533.3 320 544 309.3 544 296C544 282.7 533.3 272 520 272L495.2 272C495.7 266.7 496 261.4 496 256C496 250.6 495.7 245.3 495.2 240L520 240C533.3 240 544 229.3 544 216C544 202.7 533.3 192 520 192L482.7 192C458 135.5 401.6 96 336 96L208 96zM407.6 192L240 192L240 160L336 160C364.4 160 390 172.4 407.6 192zM240 240L430.7 240C431.6 245.2 432 250.5 432 256C432 261.5 431.5 266.8 430.7 272L240 272L240 240zM407.6 320C390 339.6 364.5 352 336 352L240 352L240 320L407.6 320z"/>
                    </svg>                
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-gray-900">PHP {{ number_format((float) ($totalRevenue ?? 0), 2) }}</p>
            <p class="mt-2 text-xs font-medium {{ ($revenueChange ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ ($revenueChange ?? 0) >= 0 ? '+' : '' }}{{ $revenueChange ?? 0 }}% vs last month
            </p>
        </article>

        <article class="rounded-3xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Total Orders</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-gray-900">{{ number_format($totalOrders ?? 0) }}</p>
            <p class="mt-2 text-xs text-gray-500">All-time order count</p>
        </article>

        <article class="rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Products</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-gray-900">{{ number_format($totalProducts ?? 0) }}</p>
            <p class="mt-2 text-xs text-gray-500">Catalog items currently tracked</p>
        </article>

        <article class="rounded-3xl border border-violet-200 bg-gradient-to-br from-violet-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-violet-700">Users</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 00-4-4h-1m-4 5H3v-1a4 4 0 014-4h6a4 4 0 014 4v1zM9 7a4 4 0 118 0"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-gray-900">{{ number_format($totalUsers ?? 0) }}</p>
            <p class="mt-2 text-xs text-gray-500">Admins, staff, and customers</p>
        </article>
    </section>

    <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h2 class="text-2xl font-bold text-gray-900">Order Status Overview</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">Manage Orders</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Pending</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ $salesStatuses['pending'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Confirmed</p>
                <p class="mt-2 text-3xl font-bold text-blue-700">{{ $salesStatuses['confirmed'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700">Processing</p>
                <p class="mt-2 text-3xl font-bold text-indigo-700">{{ $salesStatuses['processing'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-purple-200 bg-purple-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-purple-700">Shipped</p>
                <p class="mt-2 text-3xl font-bold text-purple-700">{{ $salesStatuses['shipped'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-green-700">Delivered</p>
                <p class="mt-2 text-3xl font-bold text-green-700">{{ $salesStatuses['delivered'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-red-700">Cancelled</p>
                <p class="mt-2 text-3xl font-bold text-red-700">{{ $salesStatuses['cancelled'] ?? 0 }}</p>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <article class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-3">Inventory Status</h3>
            <div id="inventoryChart"></div>
        </article>
        <article class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-3">Sales Status</h3>
            <div id="salesStatusChart"></div>
        </article>
        <article class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-3">Delivery Status</h3>
            <div id="deliveryChart"></div>
        </article>
    </section>

    <section class="grid grid-cols-1 xl:grid-cols-3 gap-5">
        <article class="xl:col-span-2 rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-2xl font-bold text-gray-900">Recent Orders</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500">
                            <th class="pb-3 pr-2">Order</th>
                            <th class="pb-3 pr-2">Customer</th>
                            <th class="pb-3 pr-2">Payment</th>
                            <th class="pb-3 pr-2">Status</th>
                            <th class="pb-3 text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($recentOrders as $order)
                            <tr class="border-b border-gray-100 align-middle">
                                <td class="py-3 pr-2 font-bold text-gray-900">#{{ $order->display_order_number }}</td>
                                <td class="py-3 pr-2 text-gray-700">{{ $order->user->name ?? 'N/A' }}</td>
                                <td class="py-3 pr-2 text-gray-600">{{ $order->payment_channel_label }}</td>
                                <td class="py-3 pr-2">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                        {{ $order->status === 'processing' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                        {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : '' }}
                                        {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                    ">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td class="py-3 text-right font-bold text-gray-900">PHP {{ number_format((float) ($order->total_price ?? 0), 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-8 text-center text-gray-500">No recent orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>

        <article class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Team Snapshot</h3>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                    <span class="text-gray-600">Administrators</span>
                    <span class="font-bold text-gray-900">{{ $adminUsers ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                    <span class="text-gray-600">Staff</span>
                    <span class="font-bold text-gray-900">{{ $staffUsers ?? 0 }}</span>
                </div>
                <div class="flex items-center justify-between rounded-xl bg-gray-50 border border-gray-100 px-4 py-3">
                    <span class="text-gray-600">Customers</span>
                    <span class="font-bold text-gray-900">{{ $customerUsers ?? 0 }}</span>
                </div>
            </div>
        </article>
    </section>
</div>

<script>
    const isDark = document.documentElement.classList.contains('dark');

    new ApexCharts(document.getElementById('inventoryChart'), {
        chart: { type: 'donut', height: 220, background: 'transparent' },
        series: [
            {{ $inventoryStatuses['in_stock'] ?? 0 }},
            {{ $inventoryStatuses['low_stock'] ?? 0 }},
            {{ $inventoryStatuses['out_of_stock'] ?? 0 }}
        ],
        labels: ['In Stock', 'Low Stock', 'Out of Stock'],
        colors: ['#10b981', '#f59e0b', '#ef4444'],
        legend: { position: 'bottom', labels: { colors: isDark ? '#9ca3af' : '#6b7280' } },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'In Stock', color: isDark ? '#fff' : '#111', formatter: () => '{{ $inventoryStatuses["in_stock"] ?? 0 }}' } } } } },
        stroke: { show: false },
        theme: { mode: isDark ? 'dark' : 'light' },
        tooltip: { theme: isDark ? 'dark' : 'light' },
    }).render();

    new ApexCharts(document.getElementById('salesStatusChart'), {
        chart: { type: 'donut', height: 220, background: 'transparent' },
        series: [
            {{ $salesStatuses['pending'] ?? 0 }},
            {{ $salesStatuses['confirmed'] ?? 0 }},
            {{ $salesStatuses['processing'] ?? 0 }},
            {{ $salesStatuses['shipped'] ?? 0 }},
            {{ $salesStatuses['delivered'] ?? 0 }},
            {{ $salesStatuses['cancelled'] ?? 0 }}
        ],
        labels: ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
        colors: ['#f59e0b', '#3b82f6', '#6366f1', '#a855f7', '#10b981', '#ef4444'],
        legend: { position: 'bottom', labels: { colors: isDark ? '#9ca3af' : '#6b7280' } },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', color: isDark ? '#fff' : '#111' } } } } },
        stroke: { show: false },
        theme: { mode: isDark ? 'dark' : 'light' },
        tooltip: { theme: isDark ? 'dark' : 'light' },
    }).render();

    new ApexCharts(document.getElementById('deliveryChart'), {
        chart: { type: 'donut', height: 220, background: 'transparent' },
        series: [
            {{ $deliveryStatuses['to_ship'] ?? 0 }},
            {{ $deliveryStatuses['in_transit'] ?? 0 }},
            {{ $deliveryStatuses['delivered'] ?? 0 }},
            {{ $deliveryStatuses['cancelled'] ?? 0 }}
        ],
        labels: ['To Ship', 'In Transit', 'Delivered', 'Cancelled'],
        colors: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
        legend: { position: 'bottom', labels: { colors: isDark ? '#9ca3af' : '#6b7280' } },
        dataLabels: { enabled: false },
        plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Delivered', color: isDark ? '#fff' : '#111', formatter: () => '{{ $deliveryStatuses["delivered"] ?? 0 }}' } } } } },
        stroke: { show: false },
        theme: { mode: isDark ? 'dark' : 'light' },
        tooltip: { theme: isDark ? 'dark' : 'light' },
    }).render();
</script>
@endsection


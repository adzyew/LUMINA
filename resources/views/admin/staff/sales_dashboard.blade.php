@extends('admin.admin_layout')

@section('title', 'Sales Dashboard | Lumina')

@section('content')
<header class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Sales Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Welcome back, {{ auth()->user()->name }}.</p>
    </div>
    <a href="{{ route('admin.orders.index') }}" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
        View Orders
    </a>
</header>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Revenue</h3>
        <p class="text-3xl font-bold text-amber-600 dark:text-amber-300">₱{{ number_format($totalRevenue ?? 0, 2) }}</p>
        <a href="{{ route('admin.sales.index') }}" class="text-sm text-amber-500 hover:text-amber-400 mt-2 inline-block">View report →</a>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Completed Orders</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalOrders ?? 0 }}</p>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Pending Orders</h3>
        <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ $pendingOrders ?? 0 }}</p>
        <a href="{{ route('admin.orders.index') }}?status=pending" class="text-sm text-amber-500 hover:text-amber-400 mt-2 inline-block">Process →</a>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">This Month</h3>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">₱{{ number_format($thisMonthRevenue ?? 0, 2) }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Orders</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-sm text-amber-500 hover:text-amber-400">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="text-gray-500 dark:text-gray-400 text-sm border-b border-gray-200 dark:border-white/10">
                        <th class="pb-3 font-medium">Order ID</th>
                        <th class="pb-3 font-medium">Customer</th>
                        <th class="pb-3 font-medium">Status</th>
                        <th class="pb-3 font-medium text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($recentOrders ?? [] as $order)
                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">
                        <td class="py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="font-medium text-amber-500 hover:text-amber-400">#{{ $order->id }}</a>
                        </td>
                        <td class="py-4 text-gray-900 dark:text-white">{{ $order->user->name ?? 'Guest' }}</td>
                        <td class="py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-bold {{ $order->status === 'delivered' ? 'bg-green-500/20 text-green-600 dark:text-green-400' : 'bg-amber-500/20 text-amber-600 dark:text-amber-400' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="py-4 text-right font-bold text-amber-600 dark:text-amber-300">₱{{ number_format($order->total_price ?? 0, 2) }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-8 text-center text-gray-500 dark:text-gray-400">No orders yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Order Status</h3>
        <div class="space-y-2">
            @foreach(['pending'=>'Pending','confirmed'=>'Confirmed','processing'=>'Processing','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled'] as $key => $label)
            <div class="flex justify-between text-sm">
                <span class="text-gray-500 dark:text-gray-400">{{ $label }}</span>
                <span class="font-medium text-gray-900 dark:text-white">{{ $ordersByStatus[$key] ?? 0 }}</span>
            </div>
            @endforeach
        </div>
        <a href="{{ route('admin.sales.index') }}" class="block w-full mt-6 py-3 bg-amber-300 text-black font-bold text-center rounded-lg hover:bg-amber-400 transition-colors">
            Sales Report
        </a>
    </div>
</div>
@endsection

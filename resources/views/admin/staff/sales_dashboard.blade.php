@extends('admin.admin_layout')

@section('title', 'Sales Dashboard | Lumina')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    @include('admin.staff.partials.topbar', [
        'title' => 'Sales & Orders',
        'subtitle' => 'Manage incoming orders and track store revenue.',
    ])

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <article class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-amber-700">Pending Orders</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-gray-900">{{ number_format($pendingOrders ?? 0) }}</p>
        </article>

        <article class="rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Revenue (This Month)</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 1v8m0 0v1"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-gray-900">PHP {{ number_format((float) ($thisMonthRevenue ?? 0), 2) }}</p>
        </article>

        <article class="rounded-3xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Completed Orders</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-gray-900">{{ number_format($totalOrders ?? 0) }}</p>
        </article>

        <article class="rounded-3xl border border-violet-200 bg-gradient-to-br from-violet-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-violet-700">Lifetime Revenue</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-violet-100 text-violet-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-gray-900">PHP {{ number_format((float) ($totalRevenue ?? 0), 2) }}</p>
        </article>
    </section>

    <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h2 class="text-2xl font-bold text-gray-900">Order Status Overview</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">Manage Orders</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-3">
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

    <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Recent Orders to Process</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">View All Orders</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <th class="py-3 px-2">Order ID</th>
                        <th class="py-3 px-2">Customer</th>
                        <th class="py-3 px-2">Date</th>
                        <th class="py-3 px-2">Payment</th>
                        <th class="py-3 px-2">Status</th>
                        <th class="py-3 px-2 text-right">Total</th>
                        <th class="py-3 px-2 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm text-gray-700">
                    @forelse($recentOrders as $order)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 px-2 font-bold text-gray-900">#{{ $order->display_order_number }}</td>
                            <td class="py-3 px-2">
                                <p class="font-semibold text-gray-900">{{ $order->user->name ?? 'Guest' }}</p>
                                <p class="text-xs text-gray-500">{{ $order->contact_phone ?? 'No phone' }}</p>
                            </td>
                            <td class="py-3 px-2 text-gray-600">{{ $order->created_at->format('M d, Y h:ia') }}</td>
                            <td class="py-3 px-2 text-gray-600">{{ $order->payment_channel_label }}</td>
                            <td class="py-3 px-2">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                                    {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-indigo-100 text-indigo-700' : '' }}
                                    {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-700' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-3 px-2 text-right font-bold text-gray-900">PHP {{ number_format((float) $order->total_price, 2) }}</td>
                            <td class="py-3 px-2 text-center">
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-gray-500">No recent orders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection


@extends('admin.admin_layout')

@section('title', 'Delivery Dashboard | Lumina')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    @include('admin.staff.partials.topbar', [
        'title' => 'Delivery Dashboard',
        'subtitle' => 'Manage outbound orders and keep shipment updates accurate.',
    ])

    <div class="flex justify-end">
        <a href="{{ route('admin.deliveries.index') }}" class="inline-flex items-center rounded-xl bg-amber-300 px-5 py-2.5 text-sm font-bold text-black hover:bg-amber-400">
            View All Shipments
        </a>
    </div>

    <section class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <article class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-amber-700">Ready to Ship</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8h13l3 3v5h-2a2 2 0 11-4 0H9a2 2 0 11-4 0H3V8z"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-amber-700">{{ number_format($toShip ?? 0) }}</p>
            <p class="mt-2 text-xs text-gray-500">Processing orders</p>
        </article>

        <article class="rounded-3xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">In Transit</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-blue-700">{{ number_format($shipped ?? 0) }}</p>
            <p class="mt-2 text-xs text-gray-500">Shipped, awaiting delivery</p>
        </article>

        <article class="rounded-3xl border border-green-200 bg-gradient-to-br from-green-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-green-700">Delivered</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-green-100 text-green-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-green-700">{{ number_format($delivered ?? 0) }}</p>
            <p class="mt-2 text-xs text-gray-500">Completed deliveries</p>
        </article>
    </section>

    <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-4 flex items-center justify-between gap-3">
            <h2 class="text-2xl font-bold text-gray-900">Shipment Status Overview</h2>
            <a href="{{ route('admin.deliveries.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">Open Deliveries</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
            <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Pending</p>
                <p class="mt-2 text-3xl font-bold text-amber-700">{{ $deliveryStatuses['pending'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-indigo-200 bg-indigo-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-indigo-700">Processing</p>
                <p class="mt-2 text-3xl font-bold text-indigo-700">{{ $deliveryStatuses['processing'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-blue-200 bg-blue-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-blue-700">Shipped</p>
                <p class="mt-2 text-3xl font-bold text-blue-700">{{ $deliveryStatuses['shipped'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-green-200 bg-green-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-green-700">Delivered</p>
                <p class="mt-2 text-3xl font-bold text-green-700">{{ $deliveryStatuses['delivered'] ?? 0 }}</p>
            </div>
            <div class="rounded-2xl border border-red-200 bg-red-50 p-4">
                <p class="text-xs font-semibold uppercase tracking-wide text-red-700">Cancelled</p>
                <p class="mt-2 text-3xl font-bold text-red-700">{{ $deliveryStatuses['cancelled'] ?? 0 }}</p>
            </div>
        </div>
    </section>

    <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Pending Shipments</h2>
            <a href="{{ route('admin.deliveries.index') }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">View All</a>
        </div>
        @php($shipments = $pendingShipment ?? collect())
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <th class="pb-3 pr-2">Order ID</th>
                        <th class="pb-3 pr-2">Customer</th>
                        <th class="pb-3 pr-2">Status</th>
                        <th class="pb-3 pr-2">Tracking</th>
                        <th class="pb-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($shipments as $order)
                        <tr class="border-b border-gray-100">
                            <td class="py-3 pr-2 font-bold text-gray-900">#{{ $order->display_order_number }}</td>
                            <td class="py-3 pr-2 text-gray-700">{{ $order->user->name ?? 'Guest' }}</td>
                            <td class="py-3 pr-2">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-3 pr-2 text-gray-600">{{ $order->tracking_number ?? '-' }}</td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.deliveries.show', $order) }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100">
                                    Update
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-gray-500">No pending shipments.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection


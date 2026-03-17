@extends('admin.admin_layout')

@section('title', 'Delivery Dashboard | Lumina')

@section('content')
@include('admin.staff.partials.topbar', [
    'title' => 'Delivery Dashboard',
    'subtitle' => 'Welcome back, ' . auth()->user()->name . '.',
])

<div class="mb-8 flex justify-end">
    <a href="{{ route('admin.deliveries.index') }}" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
        View All Shipments
    </a>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <h3 class="text-gray-500 text-sm font-medium mb-1">Ready to Ship</h3>
        <p class="text-3xl font-bold text-amber-600">{{ $toShip ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">Processing orders</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <h3 class="text-gray-500 text-sm font-medium mb-1">In Transit</h3>
        <p class="text-3xl font-bold text-blue-600">{{ $shipped ?? 0 }}</p>
        <p class="text-xs text-gray-500 mt-1">Shipped, awaiting delivery</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
        <h3 class="text-gray-500 text-sm font-medium mb-1">Delivered</h3>
        <p class="text-3xl font-bold text-green-600">{{ $delivered ?? 0 }}</p>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm mb-8">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-bold text-gray-900">Shipment Status Overview</h3>
        <a href="{{ route('admin.deliveries.index') }}" class="text-sm text-amber-500 hover:text-amber-400">Open Deliveries</a>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
        <div class="p-4 rounded-xl border border-amber-500/20 bg-amber-500/10">
            <p class="text-xs uppercase tracking-wide text-amber-600">Pending</p>
            <p class="text-2xl font-bold text-amber-700">{{ $deliveryStatuses['pending'] ?? 0 }}</p>
        </div>
        <div class="p-4 rounded-xl border border-indigo-500/20 bg-indigo-500/10">
            <p class="text-xs uppercase tracking-wide text-indigo-600">Processing</p>
            <p class="text-2xl font-bold text-indigo-700">{{ $deliveryStatuses['processing'] ?? 0 }}</p>
        </div>
        <div class="p-4 rounded-xl border border-blue-500/20 bg-blue-500/10">
            <p class="text-xs uppercase tracking-wide text-blue-600">Shipped</p>
            <p class="text-2xl font-bold text-blue-700">{{ $deliveryStatuses['shipped'] ?? 0 }}</p>
        </div>
        <div class="p-4 rounded-xl border border-green-500/20 bg-green-500/10">
            <p class="text-xs uppercase tracking-wide text-green-600">Delivered</p>
            <p class="text-2xl font-bold text-green-700">{{ $deliveryStatuses['delivered'] ?? 0 }}</p>
        </div>
        <div class="p-4 rounded-xl border border-red-500/20 bg-red-500/10">
            <p class="text-xs uppercase tracking-wide text-red-600">Cancelled</p>
            <p class="text-2xl font-bold text-red-700">{{ $deliveryStatuses['cancelled'] ?? 0 }}</p>
        </div>
    </div>
</div>

<div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-gray-900">Pending Shipment</h3>
        <a href="{{ route('admin.deliveries.index') }}" class="text-sm text-amber-500 hover:text-amber-400">View All</a>
    </div>
    @php($shipments = $pendingShipment ?? collect())
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-500 text-sm border-b border-gray-200">
                    <th class="pb-3 font-medium">Order ID</th>
                    <th class="pb-3 font-medium">Customer</th>
                    <th class="pb-3 font-medium">Status</th>
                    <th class="pb-3 font-medium">Tracking</th>
                    <th class="pb-3 font-medium text-right"></th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($shipments as $order)
                <tr class="border-b border-gray-100 hover:bg-gray-50">
                    <td class="py-4 font-medium text-gray-900">#{{ $order->display_order_number }}</td>
                    <td class="py-4 text-gray-600">{{ $order->user->name ?? 'Guest' }}</td>
                    <td class="py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $order->status === 'shipped' ? 'bg-blue-500/20 text-blue-500' : 'bg-amber-500/20 text-amber-500' }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td class="py-4 text-gray-600">{{ $order->tracking_number ?? '—' }}</td>
                    <td class="py-4 text-right">
                        <a href="{{ route('admin.deliveries.show', $order) }}" class="px-3 py-1.5 bg-amber-300 text-black font-semibold rounded-lg text-xs hover:bg-amber-400">Update</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-gray-500">No pending shipments.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

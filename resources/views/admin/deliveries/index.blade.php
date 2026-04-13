@extends('admin.admin_layout')

@section('title', 'Delivery Tracking | Lumina Admin')

@section('content')
<header class="mb-8">
    @include('partials.favicon')
    <h1 class="text-3xl font-playfair font-bold text-gray-900">Delivery Tracking</h1>
    <p class="text-gray-600 text-sm mt-1">Track orders in processing, shipped, or delivered.</p>
</header>

<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.deliveries.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ !request('status') ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">All</a>
    <a href="{{ route('admin.deliveries.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request('status') === 'processing' ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">Processing</a>
    <a href="{{ route('admin.deliveries.index', ['status' => 'shipped']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request('status') === 'shipped' ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">Shipped</a>
    <a href="{{ route('admin.deliveries.index', ['status' => 'delivered']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request('status') === 'delivered' ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">Delivered</a>
</div>

<div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                <th class="p-4">Order ID</th>
                <th class="p-4">Customer</th>
                <th class="p-4">Status</th>
                <th class="p-4">Tracking</th>
                <th class="p-4">Shipped</th>
                <th class="p-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($orders as $order)
            <tr class="hover:bg-amber-300/10 transition duration-300">
                <td class="p-4 text-gray-500">#{{ $order->display_order_number }}</td>
                <td class="p-4 font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</td>
                <td class="p-4">
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $order->status === 'delivered' ? 'bg-green-500/20 text-green-600' : ($order->status === 'shipped' ? 'bg-purple-500/20 text-purple-600' : 'bg-indigo-500/20 text-indigo-600') }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </td>
                <td class="p-4 text-gray-600">{{ $order->tracking_number ?? '-' }}</td>
                <td class="p-4 text-gray-500">{{ $order->shipped_at ? $order->shipped_at->format('M d, Y') : '-' }}</td>
                <td class="p-4">
                    <button
                        type="button"
                        onclick="openDeliveryModal({
                            id: {{ $order->id }},
                            order_number: @js($order->display_order_number),
                            customer: @js($order->user->name ?? 'Guest'),
                            status: @js($order->status),
                            tracking_number: @js($order->tracking_number ?? ''),
                            courier_name: @js($order->courier_name ?? ''),
                            tracking_url: @js($order->tracking_url ?? ''),
                            shipped_at: @js($order->shipped_at ? $order->shipped_at->format('M d, Y H:i') : '-'),
                            delivered_at: @js($order->delivered_at ? $order->delivered_at->format('M d, Y H:i') : '-'),
                            total: @js(number_format($order->total_price, 2)),
                            items: @js($order->items->map(fn($item) => [
                                'name' => $item->product->name ?? 'Product',
                                'quantity' => $item->quantity,
                                'line_total' => number_format($item->quantity * $item->unit_price, 2),
                            ])->values())
                        })"
                        class="inline-flex px-4 py-2 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 text-sm"
                        title="Update Delivery"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                    </button>
                </td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-12 text-center text-gray-500">No deliveries in progress.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())
<div class="mt-6">{{ $orders->links() }}</div>
@endif

<div id="deliveryUpdateModal" class="fixed inset-0 z-50 hidden overflow-y-auto" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 py-8">
        <div class="fixed inset-0 bg-black/75"></div>
        <div class="relative z-10 bg-white border border-gray-200 rounded-2xl shadow-2xl p-6 sm:p-8 max-w-4xl w-full">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h3 class="text-2xl font-playfair font-bold text-gray-900">Update Delivery #<span id="deliveryModalOrderNumber"></span></h3>
                    <p id="deliveryModalSubtitle" class="text-gray-500 text-sm mt-1">Track and update delivery status.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white border border-gray-200 rounded-2xl p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">Delivery Details</h4>
                    <dl class="space-y-3 text-sm">
                        <div class="flex justify-between"><dt class="text-gray-500">Customer</dt><dd id="deliveryModalCustomer" class="text-gray-900"></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Current Status</dt><dd id="deliveryModalStatus"></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Tracking Number</dt><dd id="deliveryModalTrackingNumber" class="text-gray-900"></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Courier</dt><dd id="deliveryModalCourier" class="text-gray-900"></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Tracking URL</dt><dd id="deliveryModalTrackingUrl" class="text-gray-900 break-all text-right"></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Shipped At</dt><dd id="deliveryModalShippedAt" class="text-gray-900"></dd></div>
                        <div class="flex justify-between"><dt class="text-gray-500">Delivered At</dt><dd id="deliveryModalDeliveredAt" class="text-gray-900"></dd></div>
                    </dl>

                    <form id="deliveryUpdateForm" method="POST" class="mt-6 pt-6 border-t border-gray-200 space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Update Status</label>
                            <select id="deliveryUpdateStatus" name="status" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900">
                                <option value="processing">Processing</option>
                                <option value="shipped">Shipped</option>
                                <option value="delivered">Delivered</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Tracking Number</label>
                            <input id="deliveryUpdateTrackingNumber" type="text" name="tracking_number" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900" placeholder="e.g. TRK123456" readonly>
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Courier Name</label>
                            <input id="deliveryUpdateCourierName" type="text" name="courier_name" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900" placeholder="e.g. J&T Express">
                        </div>
                        <div>
                            <label class="block text-sm text-gray-500 mb-1">Tracking URL</label>
                            <input id="deliveryUpdateTrackingUrl" type="url" name="tracking_url" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900" placeholder="https://courier.example/track/...">
                        </div>
                        <div class="flex justify-end gap-3 pt-1">
                            <button type="button" onclick="closeDeliveryModal()" class="min-w-40 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 border border-gray-200">Cancel</button>
                            <button type="submit" class="min-w-48 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400">Update Delivery</button>
                        </div>
                    </form>
                </div>

                <div class="bg-white border border-gray-200 rounded-2xl p-6">
                    <h4 class="text-lg font-bold text-gray-900 mb-4">Order Items</h4>
                    <div id="deliveryModalItems" class="space-y-4"></div>
                    <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                        <span class="text-gray-900 font-bold">Total</span>
                        <span id="deliveryModalTotal" class="text-amber-500 font-bold"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const deliveryUpdateUrlTemplate = @js(url('admin/deliveries/__ORDER__'));

    function openDeliveryModal(order) {
        const modal = document.getElementById('deliveryUpdateModal');
        const form = document.getElementById('deliveryUpdateForm');

        document.getElementById('deliveryModalOrderNumber').textContent = order.order_number || '';
        document.getElementById('deliveryModalSubtitle').textContent = 'Track and update delivery for ' + (order.customer || 'customer') + '.';
        document.getElementById('deliveryModalCustomer').textContent = order.customer || 'Guest';
        document.getElementById('deliveryModalStatus').innerHTML = renderStatusBadge(order.status || 'processing');
        document.getElementById('deliveryModalTrackingNumber').textContent = order.tracking_number || 'Not set';
        document.getElementById('deliveryModalCourier').textContent = order.courier_name || 'Not set';
        document.getElementById('deliveryModalTrackingUrl').textContent = order.tracking_url || 'Not set';
        document.getElementById('deliveryModalShippedAt').textContent = order.shipped_at || '-';
        document.getElementById('deliveryModalDeliveredAt').textContent = order.delivered_at || '-';
        document.getElementById('deliveryModalTotal').textContent = 'PHP ' + (order.total || '0.00');

        document.getElementById('deliveryUpdateStatus').value = order.status || 'processing';
        document.getElementById('deliveryUpdateTrackingNumber').value = order.tracking_number || '';
        document.getElementById('deliveryUpdateCourierName').value = order.courier_name || '';
        document.getElementById('deliveryUpdateTrackingUrl').value = order.tracking_url || '';

        form.action = deliveryUpdateUrlTemplate.replace('__ORDER__', String(order.id));

        const itemsWrap = document.getElementById('deliveryModalItems');
        itemsWrap.innerHTML = '';
        const items = Array.isArray(order.items) ? order.items : [];
        if (items.length === 0) {
            itemsWrap.innerHTML = '<p class="text-gray-500">No items.</p>';
        } else {
            items.forEach((item) => {
                const row = document.createElement('div');
                row.className = 'flex justify-between py-2 border-b border-gray-100';
                row.innerHTML = '<p class="text-gray-900">' + item.name + ' × ' + item.quantity + '</p><p class="text-amber-500">PHP ' + item.line_total + '</p>';
                itemsWrap.appendChild(row);
            });
        }

        modal.classList.remove('hidden');
    }

    function closeDeliveryModal() {
        document.getElementById('deliveryUpdateModal').classList.add('hidden');
    }

    function renderStatusBadge(status) {
        if (status === 'delivered') {
            return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-green-500/20 text-green-600">Delivered</span>';
        }
        if (status === 'shipped') {
            return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-purple-500/20 text-purple-600">Shipped</span>';
        }
        return '<span class="px-2 py-1 rounded-full text-xs font-medium bg-indigo-500/20 text-indigo-600">Processing</span>';
    }
</script>
@endsection

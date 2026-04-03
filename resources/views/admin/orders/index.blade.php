@extends('admin.admin_layout')

@section('title', 'Orders | Lumina Admin')

@section('content')
<header class="mb-8">
    @include('partials.favicon')
    <h1 class="text-3xl font-playfair font-bold text-gray-900">Orders</h1>
    <p class="text-gray-600 text-sm mt-1">Track and manage customer orders.</p>
</header>

@if(session('success'))
    <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">{{ session('success') }}</div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Total Orders</p>
        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalOrders ?? 0) }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Completed Orders</p>
        <p class="text-3xl font-bold text-green-600 mt-2">{{ number_format($completedOrders ?? 0) }}</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-2xl p-5">
        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Cancelled Orders</p>
        <p class="text-3xl font-bold text-red-500 mt-2">{{ number_format($cancelledOrders ?? 0) }}</p>
    </div>
</div>

<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ !request('status') ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">All</a>
    @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
    <a href="{{ route('admin.orders.index', ['status' => $s]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request('status') === $s ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">{{ ucfirst($s) }}</a>
    @endforeach
</div>

<div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
  
    <div class="overflow-x-auto">
    <table class="w-full text-left border-collapse min-w-[840px]">
        <thead>
            <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                <th class="p-4">Order ID</th>
                <th class="p-4">Customer</th>
                <th class="p-4">Payment</th>
                <th class="p-4">Total</th>
                <th class="p-4">Status</th>
                <th class="p-4">Date</th>
                <th class="p-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($orders as $order)
            <tr class="hover:bg-amber-300/10 transition duration-300">
                <td class="p-4 text-gray-500">#{{ $order->display_order_number }}</td>
                <td class="p-4 font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</td>
                <td class="p-4 text-gray-500">{{ $order->payment_channel_label }}</td>
                <td class="p-4 text-amber-300 font-bold">₱{{ number_format($order->total_price, 2) }}</td>
                <td class="p-4">
                    @php
                        $statusBadgeClass = match ($order->status) {
                            'pending' => 'bg-amber-100 text-amber-700 border border-amber-200',
                            'confirmed' => 'bg-blue-100 text-blue-700 border border-blue-200',
                            'processing' => 'bg-indigo-100 text-indigo-700 border border-indigo-200',
                            'shipped' => 'bg-purple-100 text-purple-700 border border-purple-200',
                            'delivered' => 'bg-green-100 text-green-700 border border-green-200',
                            'cancelled' => 'bg-red-100 text-red-700 border border-red-200',
                            default => 'bg-gray-100 text-gray-700 border border-gray-200',
                        };
                    @endphp
                    <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusBadgeClass }}">{{ ucfirst($order->status) }}</span>
                </td>
                <td class="p-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                <td class="p-4">
                    <div class="flex items-center justify-center gap-2">
                        <button type="button" title="View" onclick="openOrderViewModal({
                            id: {{ $order->id }},
                            display_order_number: @js($order->display_order_number),
                            customer: @js($order->user->name ?? 'Guest'),
                            email: @js($order->user->email ?? '—'),
                            status: @js($order->status),
                            payment: @js($order->payment_display ?? $order->payment_channel_label),
                            tracking_number: @js($order->tracking_number ?? '—'),
                            courier_name: @js($order->courier_name ?? '—'),
                            contact_phone: @js($order->contact_phone ?? ''),
                            shipping_address: @js($order->formatted_shipping_address ?? $order->shipping_address ?? '—'),
                            notes: @js($order->notes ?? ''),
                            created_at: @js($order->created_at->format('F d, Y \a\t g:i A')),
                            items: @js($order->items->map(fn($item) => [
                                'name' => $item->product->name ?? ('Product #'.$item->product_id),
                                'quantity' => $item->quantity,
                                'unit_price' => number_format($item->unit_price, 2),
                                'total' => number_format($item->quantity * $item->unit_price, 2)
                            ])),
                            total_price: '₱{{ number_format($order->total_price, 2) }}'
                        })" class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-400/10 hover:bg-amber-400 text-amber-500 hover:text-black transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                        </button>
                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Delete this order? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="p-12 text-center text-gray-500">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
    </div>
</div>

@if($orders->hasPages())<div class="mt-6">{{ $orders->links() }}</div>@endif


{{-- ========================= ORDER VIEW MODAL ========================= --}}
<div id="order-view-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4" onclick="event.stopPropagation()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-playfair font-bold text-gray-900">Order <span id="order-view-number"></span></h2>
            <button onclick="closeOrderViewModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors text-xl leading-none">&times;</button>
        </div>
        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            <div>
                <dl class="space-y-3 text-sm mb-6">
                    <div class="flex justify-between"><dt class="text-gray-500">Customer</dt><dd id="order-view-customer" class="text-gray-900"></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd id="order-view-email" class="text-gray-900"></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd id="order-view-status" class="text-gray-900"></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Payment</dt><dd id="order-view-payment" class="text-gray-900"></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tracking</dt><dd id="order-view-tracking-number" class="text-gray-900"></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Courier</dt><dd id="order-view-courier" class="text-gray-900"></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Contact</dt><dd id="order-view-contact" class="text-gray-900"></dd></div>
                    <div><dt class="text-gray-500 mb-1">Shipping Address</dt><dd id="order-view-shipping-address" class="text-gray-900 whitespace-pre-line"></dd></div>
                    <div><dt class="text-gray-500 mb-1">Notes</dt><dd id="order-view-notes" class="text-gray-900 whitespace-pre-line"></dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Created At</dt><dd id="order-view-created-at" class="text-gray-900"></dd></div>
                </dl>
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4">Items</h3>
                <div id="order-view-items" class="space-y-4"></div>
                <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                    <span class="text-gray-900 font-bold">Total</span>
                    <span id="order-view-total" class="text-amber-300 font-bold text-xl"></span>
                </div>
            </div>
        </div>
        <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
            <button id="order-edit-btn" type="button" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors text-sm">Update Order</button>
            <button onclick="closeOrderViewModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 border border-gray-200 transition-colors text-sm">Close</button>
        </div>
    </div>
</div>

{{-- ========================= ORDER UPDATE MODAL ========================= --}}
<div id="order-update-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4" onclick="event.stopPropagation()">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-playfair font-bold text-gray-900">Update Order</h2>
            <button onclick="closeOrderUpdateModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors text-xl leading-none">&times;</button>
        </div>
        <form id="order-update-form" class="p-6 sm:p-8 space-y-4" onsubmit="return submitOrderUpdate(event)">
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Status</label>
                <select id="order-update-status" name="status" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900">
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="processing">Processing</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Courier Name</label>
                <input id="order-update-courier" name="courier_name" type="text" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900">
            </div>
            <div>
                <label class="block text-xs font-semibold uppercase tracking-wide text-gray-300 mb-1">Tracking Number</label>
                <input id="order-update-tracking-number" name="tracking_number" type="text" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900 read-only:text-gray-400" readonly>
            </div>
            <div id="order-update-success" class="hidden mt-2 text-green-600 text-sm font-semibold"></div>
            <div id="order-update-error" class="hidden mt-2 text-red-600 text-sm font-semibold"></div>
            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="submit" id="order-update-btn" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400">Update Order</button>
                <button type="button" onclick="closeOrderUpdateModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 border border-gray-200 transition-colors text-sm">Cancel</button>
            </div>
        </form>
    </div>
</div>

{{-- TOAST NOTIFICATION SUCCESS --}}

<div id="order-toast" class="fixed top-10 left-1/2 -translate-x-1/2 -translate-y-2 z-[100] hidden items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg border text-sm font-medium transition-all duration-300 max-w-sm opacity-0 bg-green-50 border-green-200 text-green-800">
    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path id="order-toast-icon" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
    </svg>
    <div class="flex-1">
        <p id="order-toast-message" class="text-sm opacity-90">Order updated successfully.</p>
    </div>
</div>

<script>
    function showOrderModal(id) {
        var el = document.getElementById(id);
        el.classList.remove('hidden');
        el.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }
    function hideOrderModal(id) {
        var el = document.getElementById(id);
        el.classList.add('hidden');
        el.classList.remove('flex');
        document.body.style.overflow = '';
    }
    let currentOrderId = null;
    let currentOrderData = null;
    window.openOrderViewModal = function(order) {
        currentOrderId = order.id;
        currentOrderData = order;
        document.getElementById('order-view-number').textContent = '#' + order.display_order_number;
        document.getElementById('order-view-customer').textContent = order.customer;
        document.getElementById('order-view-email').textContent = order.email;
        document.getElementById('order-view-status').textContent = order.status.charAt(0).toUpperCase() + order.status.slice(1);
        document.getElementById('order-view-payment').textContent = order.payment;
        document.getElementById('order-view-tracking-number').textContent = order.tracking_number;
        document.getElementById('order-view-courier').textContent = order.courier_name;
        document.getElementById('order-view-contact').textContent = order.contact_phone || '—';
        document.getElementById('order-view-shipping-address').textContent = order.shipping_address;
        document.getElementById('order-view-notes').textContent = order.notes || '—';
        document.getElementById('order-view-created-at').textContent = order.created_at;
        document.getElementById('order-view-total').textContent = order.total_price;
        // Items
        var itemsDiv = document.getElementById('order-view-items');
        itemsDiv.innerHTML = '';
        if (order.items && order.items.length) {
            order.items.forEach(function(item) {
                var div = document.createElement('div');
                div.className = 'flex justify-between items-center py-2 border-b border-gray-100';
                div.innerHTML = '<div><p class=\"text-gray-900 font-medium\">' + item.name + '</p>' +
                    '<p class=\"text-gray-500 text-sm\">' + item.quantity + ' × ₱' + item.unit_price + '</p></div>' +
                    '<p class=\"text-amber-300 font-bold\">₱' + item.total + '</p>';
                itemsDiv.appendChild(div);
            });
        } else {
            itemsDiv.innerHTML = '<p class=\"text-gray-500\">No items in this order.</p>';
        }
        document.getElementById('order-edit-btn').onclick = function () {
            closeOrderViewModal();
            openOrderUpdateModal();
        };
        showOrderModal('order-view-modal');
    };

    function openOrderUpdateModal() {
        if (!currentOrderData) return;
        document.getElementById('order-update-status').value = currentOrderData.status;
        document.getElementById('order-update-courier').value = currentOrderData.courier_name;
        document.getElementById('order-update-tracking-number').value = currentOrderData.tracking_number;
        document.getElementById('order-update-success').classList.add('hidden');
        document.getElementById('order-update-error').classList.add('hidden');
        showOrderModal('order-update-modal');
    }
    function closeOrderUpdateModal() { hideOrderModal('order-update-modal'); }

    let orderToastTimer = null;

    function showOrderToast(message, type = 'success') {
        var toast = document.getElementById('order-toast');
        var title = document.getElementById('order-toast-title');
        var msgEl = document.getElementById('order-toast-message');
        var iconEl = document.getElementById('order-toast-icon');

        msgEl.textContent = message || 'Done.';

        if (type === 'success') {
            title.textContent = 'Success';
            toast.className = 'fixed top-10 left-1/2 -translate-x-1/2 -translate-y-2 z-[100] hidden items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg border text-sm font-medium transition-all duration-300 max-w-sm opacity-0 bg-green-50 border-green-200 text-green-800';
            iconEl.setAttribute('d', 'M5 13l4 4L19 7');
        } else {
            title.textContent = 'Error';
            toast.className = 'fixed top-10 left-1/2 -translate-x-1/2 -translate-y-2 z-[100] hidden items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg border text-sm font-medium transition-all duration-300 max-w-sm opacity-0 bg-red-50 border-red-200 text-red-800';
            iconEl.setAttribute('d', 'M6 18L18 6M6 6l12 12');
        }

        toast.classList.remove('hidden');
        toast.classList.add('flex');

        requestAnimationFrame(function () {
            toast.classList.remove('opacity-0', '-translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');
        });

        if (orderToastTimer) {
            clearTimeout(orderToastTimer);
        }

        orderToastTimer = setTimeout(function () {
            hideOrderToast();
        }, 3500);
    }

    function hideOrderToast() {
        var toast = document.getElementById('order-toast');

        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', '-translate-y-2');

        setTimeout(function () {
            toast.classList.add('hidden');
            toast.classList.remove('flex');
        }, 300);
    }

    function hideOrderToast() {
        var toast = document.getElementById('order-toast');
        toast.classList.remove('opacity-100', 'translate-y-0');
        toast.classList.add('opacity-0', 'translate-y-[-10px]');

        setTimeout(function () {
            toast.classList.add('hidden');
        }, 300);
    }

    function submitOrderUpdate(e) {
        e.preventDefault();

        var btn = document.getElementById('order-update-btn');
        var successDiv = document.getElementById('order-update-success');
        var errorDiv = document.getElementById('order-update-error');

        btn.disabled = true;
        btn.textContent = 'Updating...';
        successDiv.classList.add('hidden');
        errorDiv.classList.add('hidden');

        var status = document.getElementById('order-update-status').value;
        var courier = document.getElementById('order-update-courier').value;
        var trackingNumber = document.getElementById('order-update-tracking-number').value;

        var formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('status', status);
        formData.append('courier_name', courier);
        formData.append('tracking_number', trackingNumber);

        fetch(`/admin/orders/${currentOrderId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async resp => {
        let data;
        try {
            data = await resp.json();
        } catch (err) {
            throw new Error('Server error: invalid JSON response.');
        }
        if (!resp.ok) {
            throw new Error(data.message || 'Update failed.');
        }
        if (data.success || data.status === 'success') {
            closeOrderUpdateModal();
            showOrderToast(data.message || 'Order updated successfully!', 'success');

            setTimeout(function () {
                window.location.reload();
            }, 900);
        } else {
            throw new Error(data.message || 'Update failed.');
        }
        })
        .catch((err) => {
            errorDiv.textContent = err.message || 'Update failed.';
            errorDiv.classList.remove('hidden');
            console.error(err);
        })
        .finally(() => {
            btn.disabled = false;
            btn.textContent = 'Update Order';
        });

        return false;
    }
    window.closeOrderViewModal = function() { hideOrderModal('order-view-modal'); };


</script>

@endsection

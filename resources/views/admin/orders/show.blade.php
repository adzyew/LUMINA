@extends('admin.admin_layout')

@section('title', "Order #{$order->display_order_number} | Lumina Admin")

@section('content')
<div class="max-w-4xl w-full">
    <header class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-gray-900">Order #{{ $order->display_order_number }}</h1>
            <p class="text-gray-600 text-sm mt-1">{{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-600 hover:text-black">← Back to Orders</a>
    </header>

    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Order Details</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Customer</dt><dd class="text-gray-900">{{ $order->user->name ?? 'Guest' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="text-gray-900">{{ $order->user->email ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt>
                    <dd>
                        @php
                            $statusColors = ['pending'=>'bg-amber-500/20 text-amber-400','confirmed'=>'bg-blue-500/20 text-blue-400','processing'=>'bg-indigo-500/20 text-indigo-400','shipped'=>'bg-purple-500/20 text-purple-400','delivered'=>'bg-green-500/20 text-green-400','cancelled'=>'bg-red-500/20 text-red-400'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-500/20' }}">{{ ucfirst($order->status) }}</span>
                    </dd>
                </div>
                <div class="flex justify-between"><dt class="text-gray-500">Tracking</dt><dd class="text-gray-900">{{ $order->tracking_number ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Courier</dt><dd class="text-gray-900">{{ $order->courier_name ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Tracking URL</dt><dd class="text-gray-900 break-all">{{ $order->tracking_url ?? '—' }}</dd></div>
                @if($order->contact_phone)
                <div class="flex justify-between"><dt class="text-gray-500">Contact</dt><dd class="text-gray-900">{{ $order->contact_phone }}</dd></div>
                @endif
                @if($order->formatted_shipping_address)
                <div><dt class="text-gray-500 mb-1">Shipping Address</dt><dd class="text-gray-900 whitespace-pre-line">{{ $order->formatted_shipping_address }}{{ $order->notes ? "\n\nNotes: " . $order->notes : '' }}</dd></div>
                @elseif($order->shipping_address)
                <div><dt class="text-gray-500 mb-1">Shipping Address</dt><dd class="text-gray-900">{{ $order->shipping_address }}{{ $order->notes ? ' — Notes: ' . $order->notes : '' }}</dd></div>
                @endif
            </dl>

            <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="mt-6 pt-6 border-t border-gray-200 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Update Status</label>
                    <select name="status" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900">
                        @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Tracking Number</label>
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900 read-only:text-gray-400" placeholder="e.g. TRK123456" readonly>
                </div>
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Courier Name</label>
                    <input type="text" name="courier_name" value="{{ old('courier_name', $order->courier_name) }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900" placeholder="e.g. J&T Express">
                </div>
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Tracking URL</label>
                    <input type="url" name="tracking_url" value="{{ old('tracking_url', $order->tracking_url) }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900" placeholder="https://courier.example/track/...">
                </div>
                <button type="submit" class="w-full py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400">Update Order</button>
            </form>

            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="mt-3" onsubmit="return confirm('Delete this order? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full py-2.5 bg-red-600 text-white font-bold rounded-lg hover:bg-red-700">Delete Order</button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-xs uppercase tracking-wide font-semibold text-gray-500 mb-3">Email Previews</p>
                <div class="flex flex-wrap gap-2">
                    <a target="_blank" href="{{ route('admin.orders.email_preview.placed', $order) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Order Confirmation</a>
                    <a target="_blank" href="{{ route('admin.orders.email_preview.status', ['order' => $order, 'status' => 'pending', 'previous_status' => 'pending']) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Pending</a>
                    <a target="_blank" href="{{ route('admin.orders.email_preview.status', ['order' => $order, 'status' => 'confirmed', 'previous_status' => 'pending']) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Confirmed</a>
                    <a target="_blank" href="{{ route('admin.orders.email_preview.status', ['order' => $order, 'status' => 'processing', 'previous_status' => 'confirmed']) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Processing</a>
                    <a target="_blank" href="{{ route('admin.orders.email_preview.status', ['order' => $order, 'status' => 'shipped', 'previous_status' => 'processing']) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Shipped</a>
                    <a target="_blank" href="{{ route('admin.orders.email_preview.status', ['order' => $order, 'status' => 'delivered', 'previous_status' => 'shipped']) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Delivered</a>
                    <a target="_blank" href="{{ route('admin.orders.email_preview.status', ['order' => $order, 'status' => 'cancelled', 'previous_status' => 'processing']) }}" class="px-3 py-2 text-xs rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100">Cancelled</a>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Items</h3>
            <div class="space-y-4">
                @forelse($order->items as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <div>
                        <p class="text-gray-900 font-medium">{{ $item->product->name ?? 'Product #'.$item->product_id }}</p>
                        <p class="text-gray-500 text-sm">{{ $item->quantity }} × ₱{{ number_format($item->unit_price, 2) }}</p>
                    </div>
                    <p class="text-amber-300 font-bold">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                </div>
                @empty
                <p class="text-gray-500">No items in this order.</p>
                @endforelse
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                <span class="text-gray-900 font-bold">Total</span>
                <span class="text-amber-300 font-bold text-xl">₱{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

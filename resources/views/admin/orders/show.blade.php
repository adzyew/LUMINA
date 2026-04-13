@extends('admin.admin_layout')

@section('title', "Order #{$order->display_order_number} | Lumina Admin")

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <header class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-4xl font-playfair font-bold text-gray-900">Order #{{ $order->display_order_number }}</h1>
            <p class="text-sm text-gray-600 mt-1">{{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-100">Back to Orders</a>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <section class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
            <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-4">Order Details</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Customer</dt><dd class="text-gray-900">{{ $order->user->name ?? 'Guest' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Email</dt><dd class="text-gray-900">{{ $order->user->email ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt>
                    <dd>
                        @php
                            $statusColors = ['pending'=>'bg-amber-100 text-amber-700 border border-amber-200','confirmed'=>'bg-blue-100 text-blue-700 border border-blue-200','processing'=>'bg-indigo-100 text-indigo-700 border border-indigo-200','shipped'=>'bg-purple-100 text-purple-700 border border-purple-200','delivered'=>'bg-green-100 text-green-700 border border-green-200','cancelled'=>'bg-red-100 text-red-700 border border-red-200'];
                        @endphp
                        <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-700 border border-gray-200' }}">{{ ucfirst($order->status) }}</span>
                    </dd>
                </div>
                <div class="flex justify-between"><dt class="text-gray-500">Payment</dt><dd class="text-gray-900">{{ $order->payment_display }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Tracking</dt><dd class="text-gray-900">{{ $order->tracking_number ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Courier</dt><dd class="text-gray-900">{{ $order->courier_name ?? '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Tracking URL</dt><dd class="text-gray-900 break-all">{{ $order->tracking_url ?? '-' }}</dd></div>
                @if($order->contact_phone)
                <div class="flex justify-between"><dt class="text-gray-500">Contact</dt><dd class="text-gray-900">{{ $order->contact_phone }}</dd></div>
                @endif
                @if($order->formatted_shipping_address)
                <div><dt class="text-gray-500 mb-1">Shipping Address</dt><dd class="text-gray-900 whitespace-pre-line">{{ $order->formatted_shipping_address }}{{ $order->notes ? "\n\nNotes: " . $order->notes : '' }}</dd></div>
                @elseif($order->shipping_address)
                <div><dt class="text-gray-500 mb-1">Shipping Address</dt><dd class="text-gray-900">{{ $order->shipping_address }}{{ $order->notes ? ' - Notes: ' . $order->notes : '' }}</dd></div>
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
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900" placeholder="e.g. TRK123456">
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
        </section>

        <section class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
            <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-4">Items</h2>
            <div class="space-y-4">
                @forelse($order->items as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                    <div>
                        <p class="text-gray-900 font-medium">{{ $item->product->name ?? 'Product #'.$item->product_id }}</p>
                        <p class="text-gray-500 text-sm">{{ $item->quantity }} x PHP {{ number_format($item->unit_price, 2) }}</p>
                    </div>
                    <p class="text-amber-600 font-bold">PHP {{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                </div>
                @empty
                <p class="text-gray-500">No items in this order.</p>
                @endforelse
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                <span class="text-gray-900 font-bold">Total</span>
                <span class="text-amber-600 font-bold text-xl">PHP {{ number_format($order->total_price, 2) }}</span>
            </div>
        </section>
    </div>
</div>
@endsection

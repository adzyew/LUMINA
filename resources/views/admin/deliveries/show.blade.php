@extends('admin.admin_layout')

@section('title', "Delivery #{$order->display_order_number} | Lumina Admin")

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <header class="flex flex-wrap justify-between gap-3 items-center">
        <div>
            <h1 class="text-4xl font-playfair font-bold text-gray-900">Delivery #{{ $order->display_order_number }}</h1>
            <p class="text-sm text-gray-600 mt-1">Track and update delivery status.</p>
        </div>
        <a href="{{ route('admin.deliveries.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-100">Back to Deliveries</a>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
            <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-4">Delivery Details</h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500">Customer</dt><dd class="text-gray-900">{{ $order->user->name ?? 'Guest' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Status</dt><dd><span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700 border border-green-200' : ($order->status === 'shipped' ? 'bg-purple-100 text-purple-700 border border-purple-200' : 'bg-indigo-100 text-indigo-700 border border-indigo-200') }}">{{ ucfirst($order->status) }}</span></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Tracking Number</dt><dd class="text-gray-900">{{ $order->tracking_number ?? 'Not set' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Courier</dt><dd class="text-gray-900">{{ $order->courier_name ?? 'Not set' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Tracking URL</dt><dd class="text-gray-900 break-all">{{ $order->tracking_url ?? 'Not set' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Shipped At</dt><dd class="text-gray-900">{{ $order->shipped_at ? $order->shipped_at->format('M d, Y H:i') : '-' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500">Delivered At</dt><dd class="text-gray-900">{{ $order->delivered_at ? $order->delivered_at->format('M d, Y H:i') : '-' }}</dd></div>
            </dl>

            <form method="POST" action="{{ route('admin.deliveries.update', $order) }}" class="mt-6 pt-6 border-t border-gray-200 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm text-gray-500 mb-1">Update Status</label>
                    <select name="status" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-2 text-gray-900">
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
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
                <button type="submit" class="w-full py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400">Update Delivery</button>
            </form>
        </div>

        <div class="bg-white border border-gray-200 rounded-3xl p-6 shadow-sm">
            <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-4">Order Items</h2>
            <div class="space-y-4">
                @forelse($order->items as $item)
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <p class="text-gray-900">{{ $item->product->name ?? 'Product' }} x {{ $item->quantity }}</p>
                    <p class="text-amber-600 font-semibold">PHP {{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                </div>
                @empty
                <p class="text-gray-500">No items.</p>
                @endforelse
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 flex justify-between">
                <span class="text-gray-900 font-bold">Total</span>
                <span class="text-amber-600 font-bold">PHP {{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

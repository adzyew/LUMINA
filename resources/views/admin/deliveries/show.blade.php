@extends('admin.admin_layout')

@section('title', "Delivery #{$order->id} | Lumina Admin")

@section('content')
<div class="max-w-4xl w-full">
    <header class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Delivery #{{ $order->id }}</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Track and update delivery status</p>
        </div>
        <a href="{{ route('admin.deliveries.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white">← Back to Deliveries</a>
    </header>

    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Delivery Details</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Customer</dt><dd class="text-gray-900 dark:text-white">{{ $order->user->name ?? 'Guest' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Status</dt><dd><span class="px-2 py-1 rounded-full text-xs font-medium {{ $order->status === 'delivered' ? 'bg-green-500/20 text-green-400' : ($order->status === 'shipped' ? 'bg-purple-500/20 text-purple-400' : 'bg-indigo-500/20 text-indigo-400') }}">{{ ucfirst($order->status) }}</span></dd></div>
                <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Tracking Number</dt><dd class="text-gray-900 dark:text-white">{{ $order->tracking_number ?? 'Not set' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Shipped At</dt><dd class="text-gray-900 dark:text-white">{{ $order->shipped_at ? $order->shipped_at->format('M d, Y H:i') : '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Delivered At</dt><dd class="text-gray-900 dark:text-white">{{ $order->delivered_at ? $order->delivered_at->format('M d, Y H:i') : '—' }}</dd></div>
            </dl>

            <form method="POST" action="{{ route('admin.deliveries.update', $order) }}" class="mt-6 pt-6 border-t border-gray-200 dark:border-white/10 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Update Status</label>
                    <select name="status" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-2 text-gray-900 dark:text-white">
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Tracking Number</label>
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-2 text-gray-900 dark:text-white" placeholder="e.g. TRK123456">
                </div>
                <button type="submit" class="w-full py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400">Update Delivery</button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Order Items</h3>
            <div class="space-y-4">
                @forelse($order->items as $item)
                <div class="flex justify-between py-2 border-b border-gray-100 dark:border-white/5">
                    <p class="text-gray-900 dark:text-white">{{ $item->product->name ?? 'Product' }} × {{ $item->quantity }}</p>
                    <p class="text-amber-300">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                </div>
                @empty
                <p class="text-gray-500">No items.</p>
                @endforelse
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-white/10 flex justify-between">
                <span class="text-gray-900 dark:text-white font-bold">Total</span>
                <span class="text-amber-300 font-bold">₱{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

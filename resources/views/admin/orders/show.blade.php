@extends('admin.admin_layout')

@section('title', "Order #{$order->id} | Lumina Admin")

@section('content')
<div class="max-w-4xl w-full">
    <header class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Order #{{ $order->id }}</h1>
            <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $order->created_at->format('F d, Y \a\t g:i A') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white">← Back to Orders</a>
    </header>

    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">{{ session('success') }}</div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Order Details</h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Customer</dt><dd class="text-gray-900 dark:text-white">{{ $order->user->name ?? 'Guest' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Email</dt><dd class="text-gray-900 dark:text-white">{{ $order->user->email ?? '—' }}</dd></div>
                <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Status</dt>
                    <dd>
                        @php
                            $statusColors = ['pending'=>'bg-amber-500/20 text-amber-400','confirmed'=>'bg-blue-500/20 text-blue-400','processing'=>'bg-indigo-500/20 text-indigo-400','shipped'=>'bg-purple-500/20 text-purple-400','delivered'=>'bg-green-500/20 text-green-400','cancelled'=>'bg-red-500/20 text-red-400'];
                        @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-500/20' }}">{{ ucfirst($order->status) }}</span>
                    </dd>
                </div>
                <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Tracking</dt><dd class="text-gray-900 dark:text-white">{{ $order->tracking_number ?? '—' }}</dd></div>
                @if($order->contact_phone)
                <div class="flex justify-between"><dt class="text-gray-500 dark:text-gray-400">Contact</dt><dd class="text-gray-900 dark:text-white">{{ $order->contact_phone }}</dd></div>
                @endif
                @if($order->formatted_shipping_address)
                <div><dt class="text-gray-500 dark:text-gray-400 mb-1">Shipping Address</dt><dd class="text-gray-900 dark:text-white whitespace-pre-line">{{ $order->formatted_shipping_address }}{{ $order->notes ? "\n\nNotes: " . $order->notes : '' }}</dd></div>
                @elseif($order->shipping_address)
                <div><dt class="text-gray-500 dark:text-gray-400 mb-1">Shipping Address</dt><dd class="text-gray-900 dark:text-white">{{ $order->shipping_address }}{{ $order->notes ? ' — Notes: ' . $order->notes : '' }}</dd></div>
                @endif
            </dl>

            <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="mt-6 pt-6 border-t border-gray-200 dark:border-white/10 space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Update Status</label>
                    <select name="status" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-2 text-gray-900 dark:text-white">
                        @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
                            <option value="{{ $s }}" {{ $order->status === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm text-gray-500 dark:text-gray-400 mb-1">Tracking Number</label>
                    <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-2 text-gray-900 dark:text-white" placeholder="e.g. TRK123456">
                </div>
                <button type="submit" class="w-full py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400">Update Order</button>
            </form>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Items</h3>
            <div class="space-y-4">
                @forelse($order->items as $item)
                <div class="flex justify-between items-center py-2 border-b border-gray-100 dark:border-white/5">
                    <div>
                        <p class="text-gray-900 dark:text-white font-medium">{{ $item->product->name ?? 'Product #'.$item->product_id }}</p>
                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ $item->quantity }} × ₱{{ number_format($item->unit_price, 2) }}</p>
                    </div>
                    <p class="text-amber-300 font-bold">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</p>
                </div>
                @empty
                <p class="text-gray-500">No items in this order.</p>
                @endforelse
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-white/10 flex justify-between">
                <span class="text-gray-900 dark:text-white font-bold">Total</span>
                <span class="text-amber-300 font-bold text-xl">₱{{ number_format($order->total_price, 2) }}</span>
            </div>
        </div>
    </div>
</div>
@endsection

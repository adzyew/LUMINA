@extends('layouts.customer')

@section('title', 'Order #{{ $order->id }} | Lumina')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-10 py-12 max-w-4xl">

        {{-- Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-amber-300 text-sm transition-colors">&larr; Back to Orders</a>
                <h1 class="text-3xl font-playfair font-bold text-white mt-2">Order #{{ $order->id }}</h1>
                <p class="text-gray-400 text-sm mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                {{ $order->status === 'pending'    ? 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20' : '' }}
                {{ $order->status === 'confirmed'  ? 'bg-blue-500/10 text-blue-300 border border-blue-500/20' : '' }}
                {{ $order->status === 'processing' ? 'bg-indigo-500/10 text-indigo-300 border border-indigo-500/20' : '' }}
                {{ $order->status === 'shipped'    ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : '' }}
                {{ $order->status === 'delivered'  ? 'bg-green-500/10 text-green-400 border border-green-500/20' : '' }}
                {{ $order->status === 'cancelled'  ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
            ">{{ $order->status }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Order Items --}}
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-gray-900/60 rounded-2xl border border-white/10 overflow-hidden">
                    <div class="px-5 py-4 border-b border-white/10">
                        <h2 class="text-white font-bold">Items Ordered</h2>
                    </div>
                    <div class="divide-y divide-white/10">
                        @foreach($order->items as $item)
                            <div class="flex items-center gap-4 px-5 py-4">
                                @if($item->product && $item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-16 h-16 rounded-xl object-cover flex-shrink-0">
                                @else
                                    <div class="w-16 h-16 rounded-xl bg-gray-800 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-7 h-7 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 3h18M3 21h18" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-white font-semibold truncate">{{ $item->product->name ?? 'Product Unavailable' }}</p>
                                    <p class="text-gray-400 text-sm">Qty: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <p class="text-amber-300 font-bold">Php {{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                                    <p class="text-gray-500 text-xs">Php {{ number_format($item->unit_price, 2) }} each</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Order Summary & Shipping --}}
            <div class="space-y-4">

                {{-- Price Summary --}}
                <div class="bg-gray-900/60 rounded-2xl border border-white/10 p-5">
                    <h2 class="text-white font-bold mb-4">Order Summary</h2>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-400">
                            <span>Subtotal</span>
                            <span>Php {{ number_format($order->total_price + $order->discount_amount, 2) }}</span>
                        </div>
                        @if($order->discount_amount > 0)
                            <div class="flex justify-between text-green-400">
                                <span>Discount</span>
                                <span>- Php {{ number_format($order->discount_amount, 2) }}</span>
                            </div>
                        @endif
                        @if($order->points_used > 0)
                            <div class="flex justify-between text-amber-400">
                                <span>Points Used</span>
                                <span>{{ $order->points_used }} pts</span>
                            </div>
                        @endif
                        <div class="border-t border-white/10 pt-2 flex justify-between text-white font-bold">
                            <span>Total</span>
                            <span class="text-amber-300">Php {{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                {{-- Shipping Info --}}
                <div class="bg-gray-900/60 rounded-2xl border border-white/10 p-5">
                    <h2 class="text-white font-bold mb-4">Shipping Details</h2>
                    <div class="space-y-3 text-sm">
                        <div>
                            <p class="text-gray-500">Delivery Address</p>
                            <p class="text-white mt-0.5">{{ $order->formatted_shipping_address ?: 'Not provided' }}</p>
                        </div>
                        @if($order->contact_phone)
                            <div>
                                <p class="text-gray-500">Contact Number</p>
                                <p class="text-white mt-0.5">{{ $order->contact_phone }}</p>
                            </div>
                        @endif
                        <div>
                            <p class="text-gray-500">Tracking Number</p>
                            <p class="text-white font-mono mt-0.5">{{ $order->tracking_number ?? 'Pending assignment' }}</p>
                        </div>
                        @if($order->courier_name)
                            <div>
                                <p class="text-gray-500">Courier</p>
                                <p class="text-white mt-0.5">{{ $order->courier_name }}</p>
                            </div>
                        @endif
                        @if($order->tracking_url)
                            <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center gap-1.5 text-amber-300 hover:text-amber-400 font-semibold transition-colors">
                                Track Shipment
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                </svg>
                            </a>
                        @endif
                        @if($order->shipped_at)
                            <div>
                                <p class="text-gray-500">Shipped</p>
                                <p class="text-white mt-0.5">{{ $order->shipped_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif
                        @if($order->delivered_at)
                            <div>
                                <p class="text-gray-500">Delivered</p>
                                <p class="text-white mt-0.5">{{ $order->delivered_at->format('M d, Y h:i A') }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if($order->notes)
                    <div class="bg-gray-900/60 rounded-2xl border border-white/10 p-5">
                        <h2 class="text-white font-bold mb-2">Order Notes</h2>
                        <p class="text-gray-400 text-sm">{{ $order->notes }}</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

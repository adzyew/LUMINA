@extends('layouts.customer')

@section('title')
    Order #{{ $order->display_order_number }} | Lumina
@endsection

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-10 py-12 max-w-6xl">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
            <div>
                <a href="{{ route('orders.index') }}" class="text-gray-500 hover:text-amber-600 text-sm transition-colors">&larr; Back to Orders</a>
                <h1 class="text-3xl font-playfair font-bold text-gray-900 mt-2">Order #{{ $order->display_order_number }}</h1>
                <p class="text-gray-600 text-sm mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                <a href="{{ route('orders.invoice', $order) }}" class="inline-flex items-center mt-3 px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-800 text-sm font-semibold transition-colors">
                    Download Invoice (PDF)
                </a>
            </div>
            <span class="self-start px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                {{ $order->status === 'pending'    ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                {{ $order->status === 'confirmed'  ? 'bg-blue-100 text-blue-700 border border-blue-200' : '' }}
                {{ $order->status === 'processing' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : '' }}
                {{ $order->status === 'shipped'    ? 'bg-purple-100 text-purple-700 border border-purple-200' : '' }}
                {{ $order->status === 'delivered'  ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                {{ $order->status === 'cancelled'  ? 'bg-red-100 text-red-700 border border-red-200' : '' }}
            ">{{ str_replace('_', ' ', $order->status) }}</span>
        </div>

        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h2 class="text-gray-900 text-lg font-bold mb-4">Order Information</h2>

                <div class="mb-6">
                    <h3 class="text-base font-bold text-gray-900 mb-3">Items Ordered</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 flex items-center gap-4">
                                @if($item->product && $item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-14 h-14 rounded-xl object-cover shrink-0">
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 3h18M3 21h18" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-900 font-semibold truncate">{{ $item->product->name ?? 'Product Unavailable' }}</p>
                                    <p class="text-gray-600 text-sm">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-amber-600 font-bold">Php {{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                                    <p class="text-gray-500 text-xs">Php {{ number_format($item->unit_price, 2) }} each</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                        <span class="font-semibold text-gray-600 uppercase tracking-wide">Delivery Address:</span>
                        <span class="text-gray-900 text-right">{{ $order->formatted_shipping_address ?: 'Not provided' }}</span>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                        <span class="font-semibold text-gray-600 uppercase tracking-wide">Contact Number:</span>
                        <span class="text-gray-900 text-right">{{ $order->contact_phone ?: 'Not provided' }}</span>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                        <span class="font-semibold text-gray-600 uppercase tracking-wide">Email Address:</span>
                        <span class="text-gray-900 text-right">{{ $order->contact_email ?: 'Not provided' }}</span>
                    </div>
                    <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                        <span class="font-semibold text-gray-600 uppercase tracking-wide">Tracking Number:</span>
                        <span class="text-gray-900 text-right font-mono">{{ $order->tracking_number ?? 'Pending assignment' }}</span>
                    </div>
                </div>

                <div class="mt-6">
                    <h3 class="text-base font-bold text-gray-900 mb-3">Order Summary</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between text-gray-600">
                            <span>Subtotal:</span>
                            <span>Php {{ number_format($order->total_price + $order->discount_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Payment:</span>
                            <span>{{ $order->payment_display }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 mt-3 flex justify-between font-bold text-base">
                            <span class="text-gray-900">Total Amount</span>
                            <span class="text-amber-600">Php {{ number_format($order->total_price, 2) }}</span>
                        </div>
                    </div>
                </div>

                @if($order->tracking_url)
                    <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 text-amber-600 hover:text-amber-700 font-semibold transition-colors mt-5">
                        Track Shipment
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('layouts.customer')

@section('title', 'My Orders | Lumina')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-10 py-12 max-w-7xl">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-playfair font-bold text-gray-900 mb-2">My Orders</h1>
                <p class="text-gray-600">Track all your orders and their latest status.</p>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($orders as $order)
                <div class="bg-white rounded-2xl p-5 border border-gray-200 shadow-sm">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-gray-900 font-bold text-lg">Order #{{ $order->display_order_number }}</p>
                            <p class="text-gray-600 text-sm">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                            {{ $order->status === 'confirmed' ? 'bg-blue-100 text-blue-700 border border-blue-200' : '' }}
                            {{ $order->status === 'processing' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : '' }}
                            {{ $order->status === 'shipped' ? 'bg-purple-100 text-purple-700 border border-purple-200' : '' }}
                            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700 border border-red-200' : '' }}
                        ">{{ $order->status }}</span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-4 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500">Items</p>
                            <p class="text-gray-700 font-medium">{{ $order->items->count() }} item(s)</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Total</p>
                            <p class="text-amber-600 font-bold">Php {{ number_format($order->total_price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Payment</p>
                            <p class="text-gray-700 font-medium">{{ $order->payment_display }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Tracking Number</p>
                            <div class="flex items-center gap-8 ">
                                <p class="text-gray-700 font-medium">{{ $order->tracking_number ?? 'Pending assignment' }}</p>
                                <a href="{{ route('orders.show', $order) }}" title="View Details" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-300 hover:bg-amber-400 text-black font-bold rounded-lg text-xs transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-amber-50 rounded-2xl border border-amber-100">
                    <p class="text-gray-900 font-bold text-lg mb-2">No orders yet</p>
                    <p class="text-gray-600 mb-6">Start shopping to see your order history here.</p>
                    <a href="{{ route('products.index') }}" class="px-6 py-3 bg-amber-300 text-black font-bold rounded-full hover:bg-amber-400 transition-colors">
                        Browse Collection
                    </a>
                </div>
            @endforelse
        </div>

        @if($orders->hasPages())
            <div class="mt-8">{{ $orders->links() }}</div>
        @endif
    </div>
@endsection

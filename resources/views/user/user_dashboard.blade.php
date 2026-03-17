@extends('layouts.customer')

@section('title', 'My Dashboard | Lumina')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-10 py-12 max-w-7xl">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-gray-200 pb-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-playfair font-bold text-gray-900 mb-2">Account Overview</h1>
                <p class="text-gray-600">Manage your profile and view your order history.</p>
            </div>
            <a href="{{ route('products.index') }}" class="mt-4 md:mt-0 text-amber-600 hover:text-amber-700 transition-colors text-sm font-semibold flex items-center gap-1">
                Continue Shopping &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

            <div class="lg:col-span-1">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sticky top-24">
                    @if(Auth::user()->profile_photo_url)
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="w-20 h-20 rounded-full object-cover mx-auto mb-6 border-2 border-amber-300/30">
                    @else
                    <div class="w-20 h-20 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center text-black text-3xl font-bold mb-6 mx-auto">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    @endif
                    <h2 class="text-center text-xl font-bold text-gray-900 mb-1">{{ Auth::user()->name }}</h2>
                    <p class="text-center text-gray-500 text-sm mb-6">{{ Auth::user()->email }}</p>


                    <div class="space-y-3">
                        <div class="flex justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-gray-500 text-xs">Member Since</span>
                            <span class="text-gray-900 font-semibold text-sm">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                            <span class="text-gray-500 text-xs">Account Status</span>
                            <span class="text-green-600 text-sm font-bold flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span> Active
                            </span>
                        </div>
                        <div class="p-3 bg-amber-50 rounded-lg border border-amber-200">
                            <div class="flex items-center justify-between">
                                <span class="text-amber-700 text-xs font-semibold uppercase tracking-wider">Lumina Rewards</span>
                                <span class="text-amber-700 text-sm font-bold">{{ Auth::user()->points_balance }} pts</span>
                            </div>
                            <p class="text-[11px] text-amber-700 mt-1">Earn 1 point for every ₱100 spent.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
        Recent Orders
    </h3>

    <div class="space-y-4">
        @forelse($orders as $order)
            <div class="bg-white rounded-xl p-5 border border-gray-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <p class="text-gray-900 font-bold text-lg">Order #{{ $order->display_order_number }}</p>
                    <p class="text-gray-600 text-sm">{{ $order->created_at->format('M d, Y') }}</p>
                    <p class="text-gray-600 text-sm mt-1">
                        {{ $order->items->count() }} item(s) • ₱{{ number_format($order->total_price, 2) }}
                    </p>
                </div>

                <div class="flex flex-col sm:items-end gap-2">
                    <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                        {{ $order->status === 'pending' ? 'bg-amber-100 text-amber-700' : '' }}
                        {{ $order->status === 'processing' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $order->status === 'shipped' ? 'bg-blue-100 text-blue-700' : '' }}
                        {{ $order->status === 'delivered' ? 'bg-green-100 text-green-700' : '' }}
                        {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-700' : '' }}
                    ">
                        {{ $order->status }}
                    </span>

                    {{--<a href="{{ route('orders.invoice', $order->id) }}" class="text-amber-600 hover:text-amber-700 text-sm font-semibold flex items-center gap-1 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"></path></svg>
                        Invoice
                    </a>--}}
                </div>
            </div>
        @empty
            <div class="text-center py-10">
                <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 text-amber-400">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
                <p class="text-gray-900 font-bold text-lg mb-1">No orders found</p>
                <p class="text-gray-600 mb-6">You haven't purchased any luxury items yet.</p>
                <a href="{{ route('collection') }}" class="px-6 py-3 bg-amber-300 text-black font-bold rounded-full hover:bg-amber-400 transition-colors">
                    Browse Collection
                </a>
            </div>
        @endforelse
    </div>
</div>

                <div id="settings" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 scroll-mt-24">
                    <h3 class="text-xl font-playfair font-bold text-gray-900 mb-2">Profile & Security</h3>
                    <p class="text-gray-600 text-sm mb-4">Update your name, profile photo, and phone.</p>
                    <a href="{{ route('profile.show') }}" class="inline-flex items-center gap-2 text-amber-600 text-sm font-semibold hover:text-amber-700 transition-colors">
                        View profile
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

            </div>
        </div>
    </div>
@endsection

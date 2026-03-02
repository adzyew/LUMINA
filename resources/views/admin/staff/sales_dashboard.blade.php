@extends('layouts.admin') {{-- Ensure this matches your admin layout --}}

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-white mb-2">Sales & Orders</h1>
            <p class="text-gray-400">Manage incoming orders and track store revenue.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.admin_dashboard') }}" class="px-5 py-2.5 bg-white/5 border border-white/10 rounded-full text-white text-sm font-semibold hover:bg-white/10 transition-colors">
                Back to Hub
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
        <div class="bg-gray-900/60 rounded-3xl p-6 border border-white/5 shadow-lg relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-amber-500/20 rounded-2xl text-amber-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-gray-400 font-medium">Pending Orders</h3>
            </div>
            <p class="text-3xl font-bold text-white">{{ $pendingOrders }}</p>
        </div>

        <div class="bg-gray-900/60 rounded-3xl p-6 border border-white/5 shadow-lg relative overflow-hidden">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-500/10 rounded-full blur-2xl"></div>
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-green-500/20 rounded-2xl text-green-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h3 class="text-gray-400 font-medium">Revenue (This Month)</h3>
            </div>
            <p class="text-3xl font-bold text-white">₱{{ number_format($thisMonthRevenue, 2) }}</p>
        </div>

        <div class="bg-gray-900/60 rounded-3xl p-6 border border-white/5 shadow-lg relative overflow-hidden">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-blue-500/20 rounded-2xl text-blue-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h3 class="text-gray-400 font-medium">Completed Orders</h3>
            </div>
            <p class="text-3xl font-bold text-white">{{ $totalOrders }}</p>
        </div>

        <div class="bg-gray-900/60 rounded-3xl p-6 border border-white/5 shadow-lg relative overflow-hidden">
            <div class="flex items-center gap-4 mb-4">
                <div class="p-3 bg-purple-500/20 rounded-2xl text-purple-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h3 class="text-gray-400 font-medium">Lifetime Revenue</h3>
            </div>
            <p class="text-3xl font-bold text-white">₱{{ number_format($totalRevenue, 2) }}</p>
        </div>
    </div>

    <div class="bg-gray-900/80 rounded-[2rem] p-6 sm:p-8 border border-white/5 shadow-xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-white">Recent Orders to Process</h2>
            <a href="{{ route('admin.orders.index') ?? '#' }}" class="text-sm text-amber-400 hover:text-amber-300 transition-colors font-medium">View All Orders &rarr;</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 text-gray-400 text-sm tracking-wider uppercase">
                        <th class="py-4 px-4 font-medium">Order ID</th>
                        <th class="py-4 px-4 font-medium">Customer</th>
                        <th class="py-4 px-4 font-medium">Date</th>
                        <th class="py-4 px-4 font-medium">Status</th>
                        <th class="py-4 px-4 font-medium text-right">Total</th>
                        <th class="py-4 px-4 font-medium text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-300">
                    @forelse($recentOrders as $order)
                        <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                            <td class="py-4 px-4 font-bold text-white">#{{ $order->id }}</td>
                            <td class="py-4 px-4">
                                <div class="font-medium text-white">{{ $order->user->name ?? 'Guest' }}</div>
                                <div class="text-xs text-gray-500">{{ $order->contact_phone ?? 'No phone' }}</div>
                            </td>
                            <td class="py-4 px-4 text-sm">{{ $order->created_at->format('M d, Y h:ia') }}</td>
                            <td class="py-4 px-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                                    {{ $order->status === 'pending' ? 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20' : '' }}
                                    {{ $order->status === 'processing' ? 'bg-blue-500/10 text-blue-400 border border-blue-500/20' : '' }}
                                    {{ $order->status === 'shipped' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : '' }}
                                    {{ $order->status === 'delivered' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : '' }}
                                ">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-right font-bold text-amber-300">₱{{ number_format($order->total_price, 2) }}</td>
                            <td class="py-4 px-4 text-center">
                                <a href="{{ route('admin.orders.show', $order->id) ?? '#' }}" class="inline-flex items-center justify-center px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-semibold rounded-full transition-colors">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-gray-500">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                No recent orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
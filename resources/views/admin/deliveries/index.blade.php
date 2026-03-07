@extends('admin.admin_layout')

@section('title', 'Delivery Tracking | Lumina Admin')

@section('content')
<header class="mb-8">
    <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Delivery Tracking</h1>
    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Track orders in processing, shipped, or delivered.</p>
</header>

@if(session('success'))
    <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">{{ session('success') }}</div>
@endif

<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.deliveries.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ !request('status') ? 'bg-amber-300 text-black' : 'bg-gray-100 dark:bg-white/5 text-gray-600 hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10' }}">All</a>
    <a href="{{ route('admin.deliveries.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request('status') === 'processing' ? 'bg-amber-300 text-black' : 'bg-gray-100 dark:bg-white/5 text-gray-600 hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10' }}">Processing</a>
    <a href="{{ route('admin.deliveries.index', ['status' => 'shipped']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request('status') === 'shipped' ? 'bg-amber-300 text-black' : 'bg-gray-100 dark:bg-white/5 text-gray-600 hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10' }}">Shipped</a>
    <a href="{{ route('admin.deliveries.index', ['status' => 'delivered']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request('status') === 'delivered' ? 'bg-amber-300 text-black' : 'bg-gray-100 dark:bg-white/5 text-gray-600 hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10' }}">Delivered</a>
</div>

<div class="bg-white dark:bg-gray-900 rounded-2xl overflow-hidden border border-gray-200 dark:border-white/5">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 dark:bg-white/5 text-gray-700 dark:text-white border-b border-gray-200 dark:border-white/10 text-sm">
                <th class="p-4">Order ID</th>
                <th class="p-4">Customer</th>
                <th class="p-4">Status</th>
                <th class="p-4">Tracking</th>
                <th class="p-4">Shipped</th>
                <th class="p-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 dark:divide-white/10">
            @forelse($orders as $order)
            <tr class="hover:bg-amber-300/10 transition duration-300">
                <td class="p-4 text-gray-500 dark:text-gray-400">#{{ $order->id }}</td>
                <td class="p-4 font-medium text-gray-900 dark:text-white">{{ $order->user->name ?? 'Guest' }}</td>
                <td class="p-4"><span class="px-2 py-1 rounded-full text-xs font-medium {{ $order->status === 'delivered' ? 'bg-green-500/20 text-green-400' : ($order->status === 'shipped' ? 'bg-purple-500/20 text-purple-400' : 'bg-indigo-500/20 text-indigo-400') }}">{{ ucfirst($order->status) }}</span></td>
                <td class="p-4 text-gray-600 dark:text-gray-300">{{ $order->tracking_number ?? '—' }}</td>
                <td class="p-4 text-gray-500 dark:text-gray-400">{{ $order->shipped_at ? $order->shipped_at->format('M d, Y') : '—' }}</td>
                <td class="p-4"><a href="{{ route('admin.deliveries.show', $order) }}" class="inline-flex px-4 py-2 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 text-sm">Track</a></td>
            </tr>
            @empty
            <tr><td colspan="6" class="p-12 text-center text-gray-500">No deliveries in progress.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())<div class="mt-6">{{ $orders->links() }}</div>@endif
@endsection

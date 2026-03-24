@extends('admin.admin_layout')

@section('title', 'Orders | Lumina Admin')

@section('content')
<header class="mb-8">
    @include('partials.favicon')
    <h1 class="text-3xl font-playfair font-bold text-gray-900">Orders</h1>
    <p class="text-gray-600 text-sm mt-1">Track and manage customer orders.</p>
</header>

@if(session('success'))
    <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">{{ session('success') }}</div>
@endif

<div class="flex flex-wrap gap-2 mb-6">
    <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ !request('status') ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">All</a>
    @foreach(['pending','confirmed','processing','shipped','delivered','cancelled'] as $s)
    <a href="{{ route('admin.orders.index', ['status' => $s]) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ request('status') === $s ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">{{ ucfirst($s) }}</a>
    @endforeach
</div>

<div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
    <table class="w-full text-left border-collapse">
        <thead>
            <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                <th class="p-4">Order ID</th>
                <th class="p-4">Customer</th>
                <th class="p-4">Payment</th>
                <th class="p-4">Total</th>
                <th class="p-4">Status</th>
                <th class="p-4">Date</th>
                <th class="p-4 text-center">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-200">
            @forelse($orders as $order)
            <tr class="hover:bg-amber-300/10 transition duration-300">
                <td class="p-4 text-gray-500">#{{ $order->display_order_number }}</td>
                <td class="p-4 font-medium text-gray-900">{{ $order->user->name ?? 'Guest' }}</td>
                <td class="p-4 text-gray-500">{{ $order->payment_channel_label }}</td>
                <td class="p-4 text-amber-300 font-bold">₱{{ number_format($order->total_price, 2) }}</td>
                <td class="p-4"><span class="px-2 py-1 rounded-full text-xs font-medium bg-amber-500/20 text-amber-300">{{ ucfirst($order->status) }}</span></td>
                <td class="p-4 text-gray-500">{{ $order->created_at->format('M d, Y') }}</td>
                <td class="p-4">
                    <div class="flex items-center justify-center gap-2">
                        <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex px-4 py-2 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 text-sm">View</a>
                        <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" onsubmit="return confirm('Delete this order? This cannot be undone.');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex px-3 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 text-sm">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="p-12 text-center text-gray-500">No orders found.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($orders->hasPages())<div class="mt-6">{{ $orders->links() }}</div>@endif
@endsection

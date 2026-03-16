@extends('admin.admin_layout')

@section('title', 'Inventory Dashboard | Lumina')

@section('content')
@include('admin.staff.partials.topbar', [
    'title' => 'Inventory Dashboard',
    'subtitle' => 'Welcome back, ' . auth()->user()->name . '.',
])

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Products</h3>
        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
            <a href="{{ route('admin.products.index', ['filter' => 'all']) }}" class="text-sm text-amber-500 hover:text-amber-400 mt-2 inline-block">View all →</a>
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Low Stock (1–5 units)</h3>
        <p class="text-3xl font-bold {{ $lowStock > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-gray-900 dark:text-white' }}">{{ $lowStock }}</p>
        @if($lowStock > 0)
            <a href="{{ route('admin.products.index', ['filter' => 'active', 'stock' => 'low_stock']) }}" class="text-sm text-amber-500 hover:text-amber-400 mt-2 inline-block">Restock →</a>
        @endif
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Out of Stock</h3>
        <p class="text-3xl font-bold {{ $outOfStock > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-900 dark:text-white' }}">{{ $outOfStock }}</p>
        @if($outOfStock > 0)
            <a href="{{ route('admin.products.index', ['filter' => 'active', 'stock' => 'out_of_stock']) }}" class="text-sm text-amber-500 hover:text-amber-400 mt-2 inline-block">Restock now →</a>
        @endif
    </div>
    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
        <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">In Stock</h3>
        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $totalProducts - $outOfStock }}</p>
    </div>
</div>

<div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Products</h3>
        <a href="{{ route('admin.products.index', ['filter' => 'active']) }}" class="text-sm text-amber-500 hover:text-amber-400">View All</a>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead>
                <tr class="text-gray-500 dark:text-gray-400 text-sm border-b border-gray-200 dark:border-white/10">
                    <th class="pb-3 font-medium">Product</th>
                    <th class="pb-3 font-medium">Category</th>
                    <th class="pb-3 font-medium">Stock</th>
                    <th class="pb-3 font-medium text-right">Price</th>
                    <th class="pb-3 font-medium"></th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($recentProducts as $product)
                <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5">
                    <td class="py-4 font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                    <td class="py-4 text-gray-600 dark:text-gray-400">{{ $product->category ?? '—' }}</td>
                    <td class="py-4">
                        @php $qty = $product->stock_quantity ?? 0; @endphp
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $qty <= 0 ? 'bg-red-500/20 text-red-500' : ($qty <= 5 ? 'bg-amber-500/20 text-amber-500' : 'bg-green-500/20 text-green-500') }}">
                            {{ $qty }} units
                        </span>
                    </td>
                    <td class="py-4 text-right text-amber-600 dark:text-amber-400 font-bold">₱{{ number_format($product->price ?? 0, 2) }}</td>
                    <td class="py-4 text-right">
                        <a href="{{ route('admin.products.edit', $product) }}" class="text-amber-500 hover:text-amber-400 text-sm">Edit</a>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="py-8 text-center text-gray-500 dark:text-gray-400">No products yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

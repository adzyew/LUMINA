@extends('admin.admin_layout')

@section('title', 'Inventory Dashboard | Lumina')

@section('content')
<div class="max-w-7xl mx-auto space-y-8">
    @include('admin.staff.partials.topbar', [
        'title' => 'Inventory Dashboard',
        'subtitle' => 'Monitor stock movement and quickly act on low or empty inventory.',
    ])

    <section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5">
        <article class="rounded-3xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-blue-700">Total Products</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-gray-900">{{ number_format($totalProducts ?? 0) }}</p>
            <a href="{{ route('admin.products.index', ['filter' => 'all']) }}" class="mt-3 inline-block text-xs font-semibold text-amber-600 hover:text-amber-700">View all products</a>
        </article>

        <article class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-amber-700">Low Stock</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-amber-100 text-amber-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M4.93 19h14.14c1.54 0 2.502-1.667 1.732-3L13.73 4c-.77-1.333-2.694-1.333-3.464 0L3.2 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold {{ ($lowStock ?? 0) > 0 ? 'text-amber-700' : 'text-gray-900' }}">{{ number_format($lowStock ?? 0) }}</p>
            @if(($lowStock ?? 0) > 0)
                <a href="{{ route('admin.products.index', ['filter' => 'active', 'stock' => 'low_stock']) }}" class="mt-3 inline-block text-xs font-semibold text-amber-600 hover:text-amber-700">Review low stock</a>
            @endif
        </article>

        <article class="rounded-3xl border border-red-200 bg-gradient-to-br from-red-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-red-700">Out of Stock</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-red-100 text-red-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-12.728 12.728m0-12.728l12.728 12.728"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold {{ ($outOfStock ?? 0) > 0 ? 'text-red-700' : 'text-gray-900' }}">{{ number_format($outOfStock ?? 0) }}</p>
            @if(($outOfStock ?? 0) > 0)
                <a href="{{ route('admin.products.index', ['filter' => 'active', 'stock' => 'out_of_stock']) }}" class="mt-3 inline-block text-xs font-semibold text-amber-600 hover:text-amber-700">Restock now</a>
            @endif
        </article>

        <article class="rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-white p-6 shadow-sm">
            <div class="flex items-center justify-between">
                <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">In Stock</p>
                <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-emerald-100 text-emerald-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
            </div>
            <p class="mt-5 text-4xl font-bold text-emerald-700">{{ number_format(($totalProducts ?? 0) - ($outOfStock ?? 0)) }}</p>
            <p class="mt-3 text-xs text-gray-500">Available for purchase</p>
        </article>
    </section>

    <section class="rounded-3xl border border-gray-200 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Recent Products</h2>
            <a href="{{ route('admin.products.index', ['filter' => 'active']) }}" class="text-sm font-semibold text-amber-600 hover:text-amber-700">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wider text-gray-500">
                        <th class="pb-3 pr-2">Product</th>
                        <th class="pb-3 pr-2">Category</th>
                        <th class="pb-3 pr-2">Stock</th>
                        <th class="pb-3 pr-2 text-right">Price</th>
                        <th class="pb-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($recentProducts as $product)
                        @php $qty = (int) ($product->stock_quantity ?? 0); @endphp
                        <tr class="border-b border-gray-100">
                            <td class="py-3 pr-2 font-semibold text-gray-900">{{ $product->name }}</td>
                            <td class="py-3 pr-2 text-gray-600">{{ $product->category ?? '-' }}</td>
                            <td class="py-3 pr-2">
                                <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold
                                    {{ $qty <= 0 ? 'bg-red-100 text-red-700' : ($qty <= 5 ? 'bg-amber-100 text-amber-700' : 'bg-green-100 text-green-700') }}
                                ">
                                    {{ $qty }} units
                                </span>
                            </td>
                            <td class="py-3 pr-2 text-right font-bold text-gray-900">PHP {{ number_format((float) ($product->price ?? 0), 2) }}</td>
                            <td class="py-3 text-right">
                                <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center justify-center rounded-xl border border-gray-200 px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-gray-100">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-10 text-center text-gray-500">No products yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection


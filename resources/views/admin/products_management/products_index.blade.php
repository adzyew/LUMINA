@extends('admin.admin_layout')

@section('title', 'Manage Products | Lumina Admin')

@section('content')
    <header class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-gray-900">All Products</h1>
            <p class="text-gray-600 text-sm mt-1">Manage your product catalog.</p>
        </div>
        <div class="flex gap-3 items-center">
            <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
                + Add New Product
            </a>
        </div>
    </header>

    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg border border-green-200">
            {{ session('success') }}
        </div>
    @endif
    <div class="flex flex-wrap gap-2 mb-4">
        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['filter' => 'all'])) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? 'all') === 'all' ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">All</a>
        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['filter' => 'active'])) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? '') === 'active' ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">Active</a>
        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['filter' => 'archived'])) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? '') === 'archived' ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">Archived</a>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="mb-6 bg-white border border-gray-200 rounded-2xl p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
        <input type="hidden" name="filter" value="{{ $filter ?? 'all' }}">

        <div>
            <label for="search" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Search</label>
            <input id="search" name="search" type="text" value="{{ $search ?? '' }}" placeholder="Name or description"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300">
        </div>

        <div>
            <label for="category" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Category</label>
            <select id="category" name="category"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300">
                <option value="">All Categories</option>
                @foreach(($categories ?? collect()) as $categoryOption)
                    @php($categoryLabel = trim((string) $categoryOption))
                    @continue($categoryLabel === '')
                    <option style="color:#111827; background-color:#ffffff;" value="{{ $categoryOption }}" {{ ($category ?? '') === $categoryOption ? 'selected' : '' }}>{{ $categoryLabel }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="stock" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Stock Status</label>
            <select id="stock" name="stock"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300">
                <option style="color:#111827; background-color:#ffffff;" value="all" {{ ($stock ?? 'all') === 'all' ? 'selected' : '' }}>All Stock Levels</option>
                <option style="color:#111827; background-color:#ffffff;" value="in_stock" {{ ($stock ?? '') === 'in_stock' ? 'selected' : '' }}>In Stock (6+)</option>
                <option style="color:#111827; background-color:#ffffff;" value="low_stock" {{ ($stock ?? '') === 'low_stock' ? 'selected' : '' }}>Low Stock (1-5)</option>
                <option style="color:#111827; background-color:#ffffff;" value="out_of_stock" {{ ($stock ?? '') === 'out_of_stock' ? 'selected' : '' }}>Out of Stock (0)</option>
            </select>
        </div>

        <div class="flex items-end gap-2">
            <button type="submit" class="px-4 py-2 rounded-lg bg-amber-300 text-black font-semibold hover:bg-amber-400 transition-colors">Apply</button>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors">Reset</a>
        </div>
    </form>

    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                    <th class="p-4">Image</th>
                    <th class="p-4">Name</th>
                    <th class="p-4">Category</th>
                    <th class="p-4">Stock</th>
                    <th class="p-4">Price</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($products as $product)
                <tr class="hover:bg-amber-300/12 transition duration-300">
                    <td class="p-4">
                        <img src="{{ $product->image_url }}" class="w-12 h-12 object-cover rounded border border-gray-200">
                    </td>
                    <td class="p-4 font-medium text-gray-900">{{ $product->name }}</td>
                    <td class="p-4 text-gray-900 font-bold">{{ $product->category }}</td>
                    <td class="p-4 text-gray-700">{{ $product->stock_quantity ?? 0 }}</td>
                    <td class="p-4 text-amber-300 font-bold">₱{{ number_format($product->price, 2) }}</td>
                    <td class="p-4">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('admin.products.show', $product->id) }}" title="View"
                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-400/10 hover:bg-amber-400 text-amber-500 hover:text-black transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" title="Edit"
                               class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-500/10 hover:bg-blue-500 text-blue-500 hover:text-white transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            {{-- Archive / Unarchive actions (use modal confirmation) --}}
                           @if(isset($product->archived_at) && $product->archived_at)
        <form action="{{ route('admin.products.unarchive', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to unarchive this product?');">
            @csrf
            <button type="submit" title="Unarchive"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-500/10 hover:bg-gray-500 text-gray-500 hover:text-white transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </button>
        </form>
        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Permanently delete this product? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit" title="Delete permanently"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                </svg>
            </button>
        </form>
    @else
        <form action="{{ route('admin.products.archive', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to archive this product?');">
            @csrf
            <button type="submit" title="Archive"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-500/10 hover:bg-gray-500 text-gray-500 hover:text-white transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </button>
        </form>
    @endif
                            {{-- archive/unarchive handled above (single action shown) --}}
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($products->isEmpty())
            <div class="p-8 text-center text-gray-500">
                No products found.
            </div>
        @endif
    </div>
    <div class="mt-4">
        {{ $products->links() }}
    </div>

<!-- Hidden form used by the confirmation modal to submit archive/unarchive -->
<form id="archive-action-form" method="POST" style="display:none;">
    @csrf
</form>

<!-- Confirmation Modal -->
<div id="archive-confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Confirm action</h3>
        <p id="archive-modal-message" class="text-sm text-gray-700 mb-4">Are you sure?</p>
        <div class="flex justify-end gap-3">
            <button id="archive-modal-cancel" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
            <button id="archive-modal-confirm" class="px-4 py-2 bg-amber-300 rounded font-bold">Confirm</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('archive-confirm-modal');
    const msg = document.getElementById('archive-modal-message');
    const confirmBtn = document.getElementById('archive-modal-confirm');
    const cancelBtn = document.getElementById('archive-modal-cancel');
    const hiddenForm = document.getElementById('archive-action-form');

    let pendingActionUrl = null;

    document.querySelectorAll('.archive-action').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            pendingActionUrl = btn.dataset.actionUrl || btn.getAttribute('data-action-url');
            const productName = btn.getAttribute('data-product-name') || 'this item';
            msg.textContent = `Are you sure you want to ${btn.textContent.trim().toLowerCase()} "${productName}"?`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        });
    });

    cancelBtn.addEventListener('click', (e) => {
        e.preventDefault();
        pendingActionUrl = null;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    });

    confirmBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (!pendingActionUrl) return;
        hiddenForm.setAttribute('action', pendingActionUrl);
        hiddenForm.submit();
    });
});
</script>
@endpush

@endsection

@extends('admin.admin_layout')

@section('title', 'Manage Products | Lumina Admin')

@section('content')
    <header class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-black">All Products</h1>
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
    <div class="flex flex-wrap gap-2 mb-6">
        <a href="{{ route('admin.products.index', ['filter' => 'all']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? 'all') === 'all' ? 'bg-amber-300 text-black' : 'bg-white/5 text-gray-600 hover:bg-white/10 border border-gray-300' }}">All</a>
        <a href="{{ route('admin.products.index', ['filter' => 'active']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? '') === 'active' ? 'bg-amber-300 text-black' : 'bg-white/5 text-gray-600 hover:bg-white/10 border border-gray-300' }}">Active</a>
        <a href="{{ route('admin.products.index', ['filter' => 'archived']) }}" class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors {{ ($filter ?? '') === 'archived' ? 'bg-amber-300 text-black' : 'bg-white/5 text-gray-600 hover:bg-white/10 border border-gray-300' }}">Archived</a>
    </div>

    <div class="bg-gray-900 rounded-2xl overflow-hidden border border-white/5">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 text-white border-b border-white/10 text-sm">
                    <th class="p-4">ID</th>
                    <th class="p-4">Image</th>
                    <th class="p-4">Name</th>
                    <th class="p-4">Category</th>
                    <th class="p-4">Price</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @foreach($products as $product)
                <tr class="hover:bg-amber-300/12 transition duration-300">
                    <td class="p-4 text-gray-400">{{ $product->id }}</td>
                    <td class="p-4">
                        <img src="{{ $product->image_url }}" class="w-12 h-12 object-cover rounded border border-white/10">
                    </td>
                    <td class="p-4 font-medium">{{ $product->name }}</td>
                    <td class="p-4 text-white font-bold">{{ $product->category }}</td>
                    <td class="p-4 text-amber-300 font-bold">₱{{ number_format($product->price, 2) }}</td>
                    <td class="p-4">
                        <div class="flex justify-center gap-3">
                            <a href="{{ route('admin.products.show', $product->id) }}" class="text-white hover:text-gray-500" title="view">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </a>
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-indigo-600 hover:text-indigo-900" title="update">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                            {{-- Archive / Unarchive actions (use modal confirmation) --}}
                           @if(isset($product->archived_at) && $product->archived_at)
        <form action="{{ route('admin.products.unarchive', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to unarchive this product?');">
            @csrf
            <button type="submit" class="text-green-500 hover:text-green-400 transition-colors" title="Unarchive">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
                  <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
            </button>
        </form>
    @else
        <form action="{{ route('admin.products.archive', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to archive this product?');">
            @csrf
            <button type="submit" class="text-yellow-500 hover:text-yellow-400 transition-colors" title="Archive">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8">
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

<!-- Hidden form used by the confirmation modal to submit archive/unarchive -->
<form id="archive-action-form" method="POST" style="display:none;">
    @csrf
</form>

<!-- Confirmation Modal -->
<div id="archive-confirm-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">
    <div class="bg-white dark:bg-gray-800 rounded-lg p-6 w-full max-w-md">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Confirm action</h3>
        <p id="archive-modal-message" class="text-sm text-gray-700 dark:text-gray-300 mb-4">Are you sure?</p>
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
@extends('admin.admin_layout')

@section('title', 'Manage Products | Lumina Admin')

@section('content')
    <header class="flex justify-between items-center mb-8">
    @include('partials.favicon')
        <div>
            <h1 class="text-3xl font-playfair font-bold text-gray-900">Product Management</h1>
        </div>
    </header>

    <div id="products-autofilter-content" data-admin-autofilter-root="1">
    <div class="flex flex-wrap gap-2 mb-2">
        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['filter' => 'all'])) }}" data-autofilter-link="1" data-autofilter-container="#products-autofilter-content" class="px-5 py-2.5 rounded-lg text-md font-semibold transition-colors {{ ($filter ?? 'all') === 'all'      ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">All</a>
        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['filter' => 'active'])) }}" data-autofilter-link="1" data-autofilter-container="#products-autofilter-content" class="px-5 py-2.5 rounded-lg text-md font-semibold transition-colors {{ ($filter ?? '') === 'active'   ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">Active</a>
        <a href="{{ route('admin.products.index', array_merge(request()->query(), ['filter' => 'archived'])) }}" data-autofilter-link="1" data-autofilter-container="#products-autofilter-content" class="px-5 py-2.5 rounded-lg text-md font-semibold transition-colors {{ ($filter ?? '') === 'archived' ? 'bg-amber-300 text-black' : 'bg-gray-100 text-gray-600 hover:bg-gray-200 border border-gray-300' }}">Archived</a>
        <div class="flex flex-wrap justify-end items-end ml-auto">
            <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-amber-300 text-gray-900 font-bold rounded-lg hover:bg-amber-400 transition-colors">
                + Add New Product
            </a>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="js-admin-auto-filter mb-6 bg-white border border-gray-200 rounded-2xl p-4 grid grid-cols-1 md:grid-cols-4 gap-3" data-autofilter-container="#products-autofilter-content">
        <input type="hidden" name="filter" value="{{ $filter ?? 'all' }}">
        <div>
            <label for="search" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Search</label>
            <input id="search" name="search" type="text" value="{{ $search ?? '' }}" placeholder="Name or description"
                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300">
        </div>
        <div>
            <label for="category" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Category</label>
            <select id="category" name="category" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300">
                <option value="">All Categories</option>
                @foreach(($categories ?? collect()) as $categoryOption)
                    @php($categoryLabel = trim((string) $categoryOption))
                    @continue($categoryLabel === '')
                    <option style="color:#111827;background-color:#ffffff;" value="{{ $categoryOption }}" {{ ($category ?? '') === $categoryOption ? 'selected' : '' }}>{{ $categoryLabel }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="stock" class="block text-xs font-semibold uppercase tracking-wide text-gray-500 mb-1">Stock Status</label>
            <select id="stock" name="stock" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300">
                <option style="color:#111827;background-color:#ffffff;" value="all"          {{ ($stock ?? 'all') === 'all'        ? 'selected' : '' }}>All Stock Levels</option>
                <option style="color:#111827;background-color:#ffffff;" value="in_stock"     {{ ($stock ?? '') === 'in_stock'      ? 'selected' : '' }}>In Stock (6+)</option>
                <option style="color:#111827;background-color:#ffffff;" value="low_stock"    {{ ($stock ?? '') === 'low_stock'     ? 'selected' : '' }}>Low Stock (1-5)</option>
                <option style="color:#111827;background-color:#ffffff;" value="out_of_stock" {{ ($stock ?? '') === 'out_of_stock'  ? 'selected' : '' }}>Out of Stock (0)</option>
            </select>
        </div>
        <div class="flex items-end gap-2">
            <a href="{{ route('admin.products.index') }}" data-autofilter-reset="1" data-autofilter-container="#products-autofilter-content" class="px-4 py-2 rounded-lg border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-100 transition-colors">Reset</a>
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
                <tr id="product-row-{{ $product->id }}" class="hover:bg-amber-300/12 transition duration-300">
                    <td class="p-4">
                        <img id="product-img-{{ $product->id }}" src="{{ $product->image_url }}" class="w-12 h-12 object-cover rounded border border-gray-200">
                    </td>
                    <td class="p-4 font-medium text-gray-900">
                        <span id="product-name-{{ $product->id }}">{{ $product->name }}</span>
                    </td>
                    <td class="p-4 text-gray-900 font-bold">
                        <span id="product-category-{{ $product->id }}">{{ $product->category }}</span>
                    </td>
                    <td class="p-4 text-gray-700">
                        <span id="product-stock-{{ $product->id }}">{{ $product->stock_quantity ?? 0 }}</span>
                    </td>
                    <td class="p-4 text-amber-300 font-bold">
                        <span id="product-price-{{ $product->id }}">₱{{ number_format($product->price, 2) }}</span>
                    </td>
                    <td class="p-4">
                        <div class="flex justify-center gap-3">

                            {{-- VIEW --}}
                            <button type="button" title="View"
                                onclick="openViewModal({
                                    id: {{ $product->id }},
                                    name: @js($product->name),
                                    category: @js($product->category),
                                    price: '₱{{ number_format($product->price, 2) }}',
                                    stock: {{ $product->stock_quantity ?? 0 }},
                                    description: @js($product->description ?? 'No description available.'),
                                    image_url: @js($product->image_url),
                                    archived: {{ (isset($product->archived_at) && $product->archived_at) ? 'true' : 'false' }}
                                })"
                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-400/10 hover:bg-amber-400 text-amber-500 hover:text-black transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </button>

                            {{-- EDIT --}}
                            <button type="button" title="Edit"
                                onclick="openEditModal({{ $product->id }})"
                                class="w-10 h-10 flex items-center justify-center rounded-xl bg-blue-500/10 hover:bg-blue-500 text-blue-500 hover:text-white transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </button>

                            {{-- Archive / Unarchive / Delete --}}
                            @if(isset($product->archived_at) && $product->archived_at)
                                <form action="{{ route('admin.products.unarchive', ['id' => $product->id]) }}" method="POST" class="confirm-action-form" data-confirm-message="Unarchive this product?" data-confirm-title="Unarchive Product" data-confirm-action="Unarchive">
                                    @csrf
                                    <button type="submit" title="Unarchive" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-500/10 hover:bg-gray-500 text-gray-500 hover:text-white transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                                    </button>
                                </form>
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="confirm-action-form" data-confirm-message="Permanently delete this product? This cannot be undone." data-confirm-title="Delete Product" data-confirm-action="Delete" data-confirm-delay="10">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" title="Delete permanently" class="w-10 h-10 flex items-center justify-center rounded-xl bg-red-500/10 hover:bg-red-500 text-red-400 hover:text-white transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.products.archive', ['id' => $product->id] ) }}" method="POST" class="confirm-action-form" data-confirm-message="Archive this product?" data-confirm-title="Archive Product" data-confirm-action="Archive">    
                                    @csrf
                                    <button type="submit" title="Archive" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-500/10 hover:bg-gray-500 text-gray-500 hover:text-white transition-all duration-200">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                                    </button>
                                </form>
                            @endif

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($products->isEmpty())
            <div class="p-8 text-center text-gray-500">No products found.</div>
        @endif
    </div>

    <div class="mt-4">{{ $products->links() }}</div>
    </div>


    {{-- ================================================================
         TOAST NOTIFICATION
         ================================================================ --}}
    <div id="toast" class="fixed bottom-6 right-6 z-200 hidden items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg border text-sm font-medium transition-all duration-300 max-w-sm">
        <svg id="toast-icon" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"></svg>
        <span id="toast-message"></span>
    </div>


    {{-- ================================================================
         VIEW MODAL
         ================================================================ --}}
    <div id="view-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4" onclick="event.stopPropagation()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-playfair font-bold text-gray-900">Product Details</h2>
            </div>
            <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <img id="view-image" src="" alt="" class="w-full h-72 object-cover rounded-xl border border-gray-200 cursor-pointer" onclick="openViewImageZoom()">
                </div>
                <div class="space-y-5">
                    <div><p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Name</p><p id="view-name" class="text-xl font-bold text-gray-900"></p></div>
                    <div><p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Category</p><p id="view-category" class="text-base font-semibold text-gray-700"></p></div>
                    <div><p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Price</p><p id="view-price" class="text-xl font-bold text-amber-600"></p></div>
                    <div><p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Stock</p><p id="view-stock" class="text-lg font-semibold text-gray-900"></p></div>
                    <div><p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Status</p><span id="view-status" class="inline-block px-3 py-1 rounded-full text-sm font-medium"></span></div>
                    <div><p class="text-xs font-semibold uppercase tracking-wide text-gray-400 mb-1">Description</p><p id="view-description" class="text-gray-600 leading-relaxed text-sm"></p></div>
                </div>
            </div>
            <div class="flex justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button id="view-edit-btn" type="button" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors text-sm">Edit</button>
                <button onclick="closeViewModal()" class="px-5 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 border border-gray-200 transition-colors text-sm">Close</button>
            </div>
        </div>
    </div>

    <div id="view-image-zoom" class="fixed inset-0 z-[60] hidden items-center justify-center bg-black/70 backdrop-blur-sm">
        <div class="absolute inset-0" onclick="closeViewImageZoom()"></div>
        <div class="relative max-w-4xl max-h-[90vh] p-4 z-10">
            <img id="view-image-zoom-src" src="" class="rounded-lg shadow-2xl max-h-[85vh] object-contain" alt="">
            <button onclick="closeViewImageZoom()" class="absolute -top-2 -right-2 bg-white text-gray-700 w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-100 border border-gray-200 shadow text-xl">&times;</button>
        </div>
    </div>

    <div id="confirm-modal" class="fixed inset-0 z-70 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
        <div class="absolute inset-0" data-confirm-close="1"></div>
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md border border-gray-200 p-6">
            <h3 id="confirm-modal-title" class="text-xl font-playfair font-bold text-gray-900">Confirm Action</h3>
            <p id="confirm-modal-message" class="text-sm text-gray-600 mt-2">Are you sure?</p>
            <p id="confirm-modal-countdown-wrap" class="hidden text-sm text-gray-500 mt-2">Please wait <span id="confirm-modal-countdown" class="font-semibold text-red-500">10</span> seconds to confirm.</p>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" id="confirm-modal-cancel" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 font-semibold hover:bg-gray-100 transition-colors">Cancel</button>
                <button type="button" id="confirm-modal-confirm" class="px-4 py-2 rounded-lg bg-amber-300 text-black font-bold hover:bg-amber-400 transition-colors">Confirm</button>
            </div>
        </div>
    </div>


    {{-- ================================================================
         EDIT MODAL
         ================================================================ --}}
    <div id="edit-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4" onclick="event.stopPropagation()">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center px-6 py-4 border-b border-gray-200">
                <div>
                    <h2 class="text-xl font-playfair font-bold text-gray-900">Edit Product</h2>
                    <p id="edit-modal-subtitle" class="text-gray-500 text-sm mt-0.5"></p>
                </div>
                <button onclick="closeEditModal()" class="w-8 h-8 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 transition-colors text-xl leading-none">&times;</button>
            </div>

            <div id="edit-modal-loading" class="p-12 flex flex-col items-center justify-center gap-3">
                <svg class="animate-spin w-8 h-8 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <p class="text-sm text-gray-500">Loading product data…</p>
            </div>

            <div id="edit-modal-errors" class="hidden mx-6 mt-4 bg-red-50 border border-red-300 text-red-700 px-4 py-3 rounded-lg text-sm">
                <ul id="edit-modal-errors-list" class="list-disc list-inside space-y-1"></ul>
            </div>

            <form id="edit-modal-form" method="POST" enctype="multipart/form-data" action="" class="hidden px-6 py-6 space-y-6">
                @csrf
                <input type="hidden" name="_method" value="PUT">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Product Name</label>
                        <input type="text" id="edit-name" name="name" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 focus:ring-2 focus:ring-amber-100 outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Category</label>
                        <select id="edit-category" name="category" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 focus:ring-2 focus:ring-amber-100 outline-none transition-colors">
                            <option value="Rings">Rings</option>
                            <option value="Necklaces">Necklaces</option>
                            <option value="Earrings">Earrings</option>
                            <option value="Bracelets">Bracelets</option>
                            <option value="Watches">Watches</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Price (₱)</label>
                        <input type="number" step="0.01" id="edit-price" name="price" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 focus:ring-2 focus:ring-amber-100 outline-none transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Stock Quantity</label>
                        <input type="number" id="edit-stock-qty" name="stock_quantity" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 focus:ring-2 focus:ring-amber-100 outline-none transition-colors">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                    <textarea id="edit-description" name="description" rows="4" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 focus:ring-2 focus:ring-amber-100 outline-none transition-colors resize-none"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Product Size / Fit</label>
                        <input type="text" id="edit-size-spec" name="size_spec" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 focus:ring-2 focus:ring-amber-100 outline-none transition-colors" placeholder="e.g. Adjustable ring, 16-18cm, 40mm case">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Specification Details</label>
                        <textarea id="edit-specification-details" name="specification_details" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 focus:ring-2 focus:ring-amber-100 outline-none transition-colors resize-none" placeholder="One detail per line (e.g. Material: Stainless Steel)"></textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Current Image</label>
                    <img id="edit-current-image" src="" alt="" class="h-36 rounded-lg border border-gray-200 object-cover">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Change Image</label>
                    <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                    <p class="text-xs text-gray-400 mt-1">Leave empty to keep current image. Max 5MB.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Add More Gallery Images</label>
                    <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                    <p class="text-xs text-gray-400 mt-1">Select multiple files to add more photos to this product.</p>
                </div>

                <div class="flex items-center gap-2">
                    <input type="checkbox" id="edit-is-featured" name="is_featured" value="1" class="w-4 h-4 rounded border-gray-400 bg-white text-amber-300 focus:ring-amber-300">
                    <label for="edit-is-featured" class="text-sm text-gray-700">Featured Product</label>
                </div>

                <div class="flex gap-4 pt-2 border-t border-gray-100">
                    <button type="submit" id="edit-submit-btn" class="px-6 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors flex items-center gap-2">
                        <span>Update Product</span>
                        <svg id="edit-submit-spinner" class="hidden animate-spin w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                    </button>
                    <button type="button" onclick="closeEditModal()" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 border border-gray-300 transition-colors">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    {{-- ================================================================
         JAVASCRIPT
         ================================================================ --}}
    <script>
    (function () {

        /* ── CSRF ─────────────────────────────────────────────────────── */
        var CSRF = '';
        var metaTag = document.querySelector('meta[name="csrf-token"]');
        if (metaTag) { CSRF = metaTag.getAttribute('content'); }

        /* ── TOAST ────────────────────────────────────────────────────── */
        var toastTimer = null;

        function showToast(message, type) {
            var toast   = document.getElementById('toast');
            var msgEl   = document.getElementById('toast-message');
            var iconEl  = document.getElementById('toast-icon');

            msgEl.textContent = message;

            if (type === 'success') {
                toast.className = 'fixed top-10 left-1/2 -translate-x-1/2 z-100 flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg border text-sm font-medium transition-all duration-300 max-w-sm bg-green-50 border-green-200 text-green-800';
                iconEl.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>';
            } else {
                toast.className = 'fixed top-10 left-1/2 -translate-x-1/2 z-100 flex items-center gap-3 px-5 py-3.5 rounded-xl shadow-lg border text-sm font-medium transition-all duration-300 max-w-sm bg-red-50 border-red-200 text-red-800';
                iconEl.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
            }

            toast.classList.remove('hidden');
            toast.classList.add('flex');

            if (toastTimer) { clearTimeout(toastTimer); }
            toastTimer = setTimeout(function () {
                toast.classList.add('hidden');
                toast.classList.remove('flex');
            }, 3500);
        }

        var initialToastMessage = @json(session('toast_message') ?? session('success'));
        var initialToastType = @json(session('toast_type') ?? (session()->has('success') ? 'success' : null));
        if (initialToastMessage) {
            showToast(initialToastMessage, initialToastType === 'error' ? 'error' : 'success');
        }

        /* ── MODAL HELPERS ────────────────────────────────────────────── */
        function showModal(id) {
            var el = document.getElementById(id);
            el.classList.remove('hidden');
            el.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function hideModal(id) {
            var el = document.getElementById(id);
            el.classList.add('hidden');
            el.classList.remove('flex');
            var anyOpen = ['view-modal', 'edit-modal', 'view-image-zoom', 'confirm-modal'].some(function (mid) {
                return !document.getElementById(mid).classList.contains('hidden');
            });
            if (!anyOpen) { document.body.style.overflow = ''; }
        }

        var pendingConfirmForm = null;
        var confirmDelayTimer = null;

        function resetConfirmModalState() {
            var btn = document.getElementById('confirm-modal-confirm');
            var wrap = document.getElementById('confirm-modal-countdown-wrap');
            if (confirmDelayTimer) {
                clearInterval(confirmDelayTimer);
                confirmDelayTimer = null;
            }
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
            wrap.classList.add('hidden');
        }

        function openConfirmModal(title, message, actionLabel, delaySeconds) {
            resetConfirmModalState();
            document.getElementById('confirm-modal-title').textContent = title || 'Confirm Action';
            document.getElementById('confirm-modal-message').textContent = message || 'Are you sure?';
            var btn = document.getElementById('confirm-modal-confirm');
            var wrap = document.getElementById('confirm-modal-countdown-wrap');
            var countdownEl = document.getElementById('confirm-modal-countdown');
            var label = actionLabel || 'Confirm';
            btn.textContent = label;

            if (delaySeconds > 0) {
                var secondsLeft = delaySeconds;
                wrap.classList.remove('hidden');
                countdownEl.textContent = String(secondsLeft);
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btn.textContent = label + ' (' + secondsLeft + 's)';

                confirmDelayTimer = setInterval(function () {
                    secondsLeft--;
                    countdownEl.textContent = String(secondsLeft);

                    if (secondsLeft <= 0) {
                        clearInterval(confirmDelayTimer);
                        confirmDelayTimer = null;
                        btn.disabled = false;
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                        btn.textContent = label;
                    } else {
                        btn.textContent = label + ' (' + secondsLeft + 's)';
                    }
                }, 1000);
            }
            showModal('confirm-modal');
        }
        function closeConfirmModal() {
            resetConfirmModalState();
            pendingConfirmForm = null;
            hideModal('confirm-modal');
        }

        document.addEventListener('submit', function (e) {
            var form = e.target;
            if (!form || !form.classList || !form.classList.contains('confirm-action-form')) { return; }
            e.preventDefault();
            pendingConfirmForm = form;            var delaySeconds = parseInt(form.getAttribute('data-confirm-delay') || '0', 10);
            var delaySeconds = parseInt(form.getAttribute('data-confirm-delay') || '0', 10);
            openConfirmModal(
                form.getAttribute('data-confirm-title'),
                form.getAttribute('data-confirm-message'),
                form.getAttribute('data-confirm-action'),
                isNaN(delaySeconds) ? 0 : delaySeconds
            );
        });

        document.getElementById('confirm-modal-cancel').addEventListener('click', closeConfirmModal);
        document.querySelectorAll('[data-confirm-close="1"]').forEach(function (el) {
            el.addEventListener('click', closeConfirmModal);
        });
        document.getElementById('confirm-modal-confirm').addEventListener('click', function () {
            if (!pendingConfirmForm) { closeConfirmModal(); return; }
            var formToSubmit = pendingConfirmForm;
            closeConfirmModal();
            formToSubmit.submit();
        });

        /* ── VIEW MODAL ───────────────────────────────────────────────── */
        window.openViewModal = function (product) {
            document.getElementById('view-image').src           = product.image_url || '';
            document.getElementById('view-image').alt           = product.name;
            document.getElementById('view-image-zoom-src').src  = product.image_url || '';
            document.getElementById('view-name').textContent        = product.name;
            document.getElementById('view-category').textContent    = product.category || '—';
            document.getElementById('view-price').textContent       = product.price;
            document.getElementById('view-stock').textContent       = product.stock;
            document.getElementById('view-description').textContent = product.description;

            var s = document.getElementById('view-status');
            if (product.archived) {
                s.textContent = 'Archived';
                s.className = 'inline-block px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-700';
            } else if (product.stock > 0) {
                s.textContent = 'In Stock';
                s.className = 'inline-block px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700';
            } else {
                s.textContent = 'Out of Stock';
                s.className = 'inline-block px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-700';
            }

            document.getElementById('view-edit-btn').onclick = function () {
                closeViewModal();
                openEditModal(product.id);
            };

            showModal('view-modal');
        };

        window.closeViewModal     = function () { hideModal('view-modal'); };
        window.openViewImageZoom  = function () { showModal('view-image-zoom'); };
        window.closeViewImageZoom = function () { hideModal('view-image-zoom'); };

        /* ── EDIT MODAL ───────────────────────────────────────────────── */
        window.openEditModal = function (productId) {
            var loadingEl = document.getElementById('edit-modal-loading');
            loadingEl.classList.remove('hidden');
            loadingEl.innerHTML =
                '<svg class="animate-spin w-8 h-8 text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">' +
                '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>' +
                '<p class="text-sm text-gray-500">Loading product data…</p>';

            document.getElementById('edit-modal-form').classList.add('hidden');
            document.getElementById('edit-modal-errors').classList.add('hidden');
            document.getElementById('edit-modal-subtitle').textContent = '';
            showModal('edit-modal');

            fetch('/admin/products/' + productId + '/json', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(function (resp) {
                if (!resp.ok) {
                    return resp.text().then(function (t) {
                        throw new Error('HTTP ' + resp.status + ' — ' + t.substring(0, 120));
                    });
                }
                return resp.json();
            })
            .then(function (data) {
                document.getElementById('edit-modal-subtitle').textContent = data.name;
                document.getElementById('edit-name').value                 = data.name;
                document.getElementById('edit-price').value                = data.price;
                document.getElementById('edit-stock-qty').value            = data.stock_quantity;
                document.getElementById('edit-description').value          = data.description || '';
                document.getElementById('edit-size-spec').value            = (data.specifications && data.specifications.size) ? data.specifications.size : '';
                document.getElementById('edit-specification-details').value = (data.specifications && Array.isArray(data.specifications.details))
                    ? data.specifications.details.filter(function (line) { return !!line; }).join('\n')
                    : '';
                document.getElementById('edit-current-image').src          = data.image_url || '';
                document.getElementById('edit-is-featured').checked        = !!data.is_featured;

                var sel = document.getElementById('edit-category');
                for (var i = 0; i < sel.options.length; i++) {
                    sel.options[i].selected = (sel.options[i].value === data.category);
                }

                document.getElementById('edit-modal-form').setAttribute('action', '/admin/products/' + productId);
                document.getElementById('edit-modal-form').setAttribute('data-product-id', productId);
                loadingEl.classList.add('hidden');
                document.getElementById('edit-modal-form').classList.remove('hidden');
            })
            .catch(function (err) {
                loadingEl.innerHTML =
                    '<p class="text-red-500 text-sm font-medium px-4 text-center">Failed to load product data. Please try again.</p>' +
                    '<p class="text-xs text-gray-400 mt-1 px-4 text-center">' + err.message + '</p>';
            });
        };

        window.closeEditModal = function () {
            hideModal('edit-modal');
            document.getElementById('edit-modal-errors').classList.add('hidden');
            document.getElementById('edit-modal-errors-list').innerHTML = '';
        };

        /* ── EDIT FORM SUBMIT ─────────────────────────────────────────── */
        document.getElementById('edit-modal-form').addEventListener('submit', function (e) {
            e.preventDefault();

            var form    = this;
            var btn     = document.getElementById('edit-submit-btn');
            var spinner = document.getElementById('edit-submit-spinner');
            var errBox  = document.getElementById('edit-modal-errors');
            var errList = document.getElementById('edit-modal-errors-list');
            var productId = form.getAttribute('data-product-id');

            btn.disabled = true;
            spinner.classList.remove('hidden');
            errBox.classList.add('hidden');
            errList.innerHTML = '';

            fetch(form.getAttribute('action'), {
                method: 'POST',           // Laravel reads _method=PUT from FormData
                headers: {
                    'X-CSRF-TOKEN': CSRF,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(function (resp) {
                /* ── Validation errors ── */
                if (resp.status === 422) {
                    return resp.json().then(function (json) {
                        var errors = json.errors || {};
                        Object.keys(errors).forEach(function (key) {
                            errors[key].forEach(function (msg) {
                                var li = document.createElement('li');
                                li.textContent = msg;
                                errList.appendChild(li);
                            });
                        });
                        errBox.classList.remove('hidden');
                        errBox.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    });
                }

                /* ── Any other non-OK status ── */
                if (!resp.ok) {
                    throw new Error('Server error ' + resp.status);
                }

                /* ── Success — parse JSON returned by controller ── */
                return resp.json().then(function (data) {
                    closeEditModal();

                    /* Update table row in-place */
                    var id = productId;
                    if (document.getElementById('product-name-'     + id)) document.getElementById('product-name-'     + id).textContent = data.product.name;
                    if (document.getElementById('product-category-' + id)) document.getElementById('product-category-' + id).textContent = data.product.category || '—';
                    if (document.getElementById('product-stock-'    + id)) document.getElementById('product-stock-'    + id).textContent = data.product.stock_quantity;
                    if (document.getElementById('product-price-'    + id)) document.getElementById('product-price-'    + id).textContent = '₱' + parseFloat(data.product.price).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                    if (data.product.image_url && document.getElementById('product-img-' + id)) {
                        document.getElementById('product-img-' + id).src = data.product.image_url;
                    }

                    showToast('Product updated successfully!', 'success');
                });
            })
            .catch(function (err) {
                showToast('An unexpected error occurred. Please try again.', 'error');
                console.error(err);
            })
            .finally(function () {
                btn.disabled = false;
                spinner.classList.add('hidden');
            });
        });


        /* ── ESC KEY ──────────────────────────────────────────────────── */
        document.addEventListener('keydown', function (e) {
            if (e.key !== 'Escape') { return; }
            if (!document.getElementById('view-image-zoom').classList.contains('hidden')) { closeViewImageZoom(); return; }
            if (!document.getElementById('confirm-modal').classList.contains('hidden')) { closeConfirmModal(); return; }
            // Disabled closing view-modal and edit-modal with Escape
        });

    })();
    </script>

@endsection

<!doctype html>
<html lang="en">
<head>
    @include('partials.favicon')
    <title>Shop Collection | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        .text-gold { color: #d97706; }
        .skeleton-shimmer {
            position: relative;
            overflow: hidden;
            background: #e7e5e4;
        }
        .skeleton-shimmer::after {
            content: '';
            position: absolute;
            inset: 0;
            transform: translateX(-100%);
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.65), transparent);
            animation: skeleton-slide 1.2s infinite;
        }
        @keyframes skeleton-slide {
            100% { transform: translateX(100%); }
        }
    </style>
</head>
<body class="bg-stone-100 text-gray-900 relative antialiased">

    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="Luxury background" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-stone-400/30 backdrop-blur-[2px]"></div>
        <div class="absolute inset-0 bg-linear-to-b from-stone-500/70 via-stone-500/50 to-stone-200/80"></div>
    </div>

    @include('partials.navbar')

    <section class="relative min-h-48 pt-20">
        <div class="container mt-10 mx-auto px-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="sm:flex-1 text-left">
                    <h1 class="text-4xl sm:text-5xl font-playfair font-bold mt-4">
                        Explore <span class="text-amber-300">Our Collection</span>
                    </h1>
                    <p class="mt-4 text-white">Discover the finest handcrafted jewelry.</p>
                </div>

                <div class="mt-4 sm:mt-0 sm:ml-6 w-full sm:w-auto flex justify-start sm:justify-end items-center gap-3">
                    <button type="button" id="searchToggleBtn" class="inline-flex items-center justify-center h-11 w-11 rounded-xl bg-white/90 border border-gray-200 text-gray-700 hover:text-amber-600 hover:border-amber-300 shadow-sm transition-colors" aria-expanded="{{ request('search') ? 'true' : 'false' }}" aria-controls="searchPanel" aria-label="Toggle search">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35m0 0A7.5 7.5 0 1 0 6.05 6.05a7.5 7.5 0 0 0 10.6 10.6Z" />
                        </svg>
                    </button>
                    <div id="searchPanel" class="w-full sm:w-96 transition-all duration-300 {{ request('search') ? '' : 'hidden' }}">
                    <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2 w-full" id="productsSearchForm" data-products-async-form>
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                        <input type="hidden" name="max_price" value="{{ request('max_price') }}">
                        <input type="text" id="productsSearchInput" name="search" placeholder="Search products..." value="{{ request('search') }}"
                            class="w-full px-4 py-3 rounded-lg bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-400 shadow-sm transition-all">
                    </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <main class="container mx-auto px-4 sm:px-6 pb-16">

        {{-- Category Filter --}}

        <section id="productsSection">
        {{-- MOBILE DROPDOWN --}}
        <div class="block sm:hidden mb-6 mt-1">
            <form method="GET" action="{{ route('products.index') }}" id="mobileCategoryFilterForm" data-products-async-form>
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="min_price" value="{{ request('min_price') }}">
                <input type="hidden" name="max_price" value="{{ request('max_price') }}">

                <select name="category"
                    onchange="this.form.submit()"
                    class="w-full px-4 py-3 rounded-lg bg-white border border-gray-300 text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-400 shadow-sm">

                    <option value="">All Categories</option>

                    @foreach($filterCategories as $cat)
                        <option value="{{ $cat }}"
                            {{ request('category') == $cat ? 'selected' : '' }}>
                            {{ ucfirst($cat) }}
                        </option>
                    @endforeach

                </select>
            </form>
        </div>


        @php
            $maxCap = 5000;
            $selectedMinPrice = (int) request('min_price', 0);
            $selectedMaxPrice = (int) request('max_price', $maxCap);
            $selectedMinPrice = max(0, min($selectedMinPrice, $maxCap));
            $selectedMaxPrice = max($selectedMinPrice, min($selectedMaxPrice, $maxCap));
        @endphp

        {{-- DESKTOP FILTER ROW --}}
        <div class="hidden sm:grid grid-cols-1 xl:grid-cols-12 gap-3 mt-10 mb-10 w-full">
            <div class="xl:col-span-7 grid grid-cols-3 lg:grid-cols-6 gap-2">
                <a href="{{ route('products.index', request()->only('search', 'min_price', 'max_price')) }}" data-products-async-link
                    class="h-11 px-4 inline-flex items-center justify-center rounded-lg text-md font-semibold transition-all {{ !request('category') ? 'bg-amber-300 text-black shadow-sm' : 'bg-white/80 text-gray-700 hover:bg-amber-50 hover:text-amber-700 border border-gray-200' }}">
                    All Products
                </a>
                @foreach($filterCategories as $cat)
                    <a href="{{ route('products.index', array_merge(request()->only('search', 'min_price', 'max_price'), ['category' => $cat])) }}" data-products-async-link
                        class="h-11 px-4 inline-flex items-center justify-center rounded-lg text-md font-semibold capitalize transition-all {{ (request('category') ?? '') === $cat ? 'bg-amber-300 text-black shadow-sm' : 'bg-white/80 text-gray-700 hover:bg-amber-50 hover:text-amber-700 border border-gray-200' }}">
                        {{ $cat }}
                    </a>
                @endforeach
            </div>

            <form method="GET" action="{{ route('products.index') }}" class="xl:col-span-5 w-full bg-white/85 border border-gray-200 rounded-xl px-2 py-2" id="desktopPriceFilterForm" data-products-async-form>
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">

                <div class="flex items-center gap-2">
                    <div class="relative flex-1 min-w-52">
                        <div id="priceBubble" class="absolute -top-9 -translate-x-1/2 bg-amber-500 text-white text-xs font-semibold px-3 py-1.5 rounded-full shadow">&#8369;{{ number_format($selectedMaxPrice) }}</div>
                        <input id="priceRangeSlider" type="range" min="0" max="{{ $maxCap }}" step="30" value="{{ $selectedMaxPrice }}"
                            class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-amber-200 accent-amber-500">
                    </div>
                    <input id="minPriceInput" type="number" min="0" max="{{ $maxCap }}" name="min_price" value="{{ $selectedMinPrice }}"
                        class="w-20 h-7 text-center rounded-lg bg-white border border-gray-300 text-sm font-semibold text-gray-700">
                    <span class="text-gray-500">-</span>
                    <input id="maxPriceInput" type="number" min="0" max="{{ $maxCap }}" name="max_price" value="{{ $selectedMaxPrice }}"
                        class="w-20 h-7 text-center rounded-lg bg-white border border-gray-300 text-sm font-semibold text-gray-700">
                    <a href="{{ route('products.index', request()->only('search', 'category')) }}" data-products-async-link class="h-7 w-11 inline-flex items-center justify-center rounded-lg bg-gray-100 border border-gray-300 text-amber-600 hover:bg-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>

        {{-- MOBILE PRICE FILTER --}}
        <div class="block sm:hidden mb-8">
            <form method="GET" action="{{ route('products.index') }}" class="bg-white/90 border border-gray-200 rounded-xl p-3" id="mobilePriceFilterForm" data-products-async-form>
                <input type="hidden" name="search" value="{{ request('search') }}">
                <input type="hidden" name="category" value="{{ request('category') }}">
                <div class="flex items-center gap-2">
                    <div class="relative flex-1 min-w-32.5">
                        <div id="priceBubbleMobile" class="absolute -top-9 -translate-x-1/2 bg-amber-500 text-white text-xs font-semibold px-3 py-1.5 rounded-full shadow">&#8369;{{ number_format($selectedMaxPrice) }}</div>
                        <input id="priceRangeSliderMobile" type="range" min="0" max="{{ $maxCap }}" step="50" value="{{ $selectedMaxPrice }}"
                            class="w-full h-2 rounded-lg appearance-none cursor-pointer bg-amber-200 accent-amber-500">
                    </div>
                    <input id="minPriceInputMobile" type="number" min="0" max="{{ $maxCap }}" name="min_price" value="{{ $selectedMinPrice }}"
                        class="w-20 h-11 text-center px-2 rounded-lg bg-white border border-gray-300 text-sm font-semibold text-gray-700">
                    <span class="text-gray-500">-</span>
                    <input id="maxPriceInputMobile" type="number" min="0" max="{{ $maxCap }}" name="max_price" value="{{ $selectedMaxPrice }}"
                        class="w-20 h-11 text-center px-2 rounded-lg bg-white border border-gray-300 text-sm font-semibold text-amber-500">
                    <a href="{{ route('products.index', request()->only('search', 'category')) }}" data-products-async-link class="h-11 w-11 inline-flex items-center justify-center rounded-lg bg-gray-100 border border-gray-300 text-black hover:bg-gray-200 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 0 0-3.7-3.7 48.678 48.678 0 0 0-7.324 0 4.006 4.006 0 0 0-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 0 0 3.7 3.7 48.656 48.656 0 0 0 7.324 0 4.006 4.006 0 0 0 3.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3-3 3" />
                        </svg>
                    </a>
                </div>
            </form>
        </div>

        
        

        {{-- Products Grid --}}
        <div id="productsGrid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @forelse($products as $product)
                <div class="bg-white rounded-2xl p-3 sm:p-4 border border-amber-100/60 hover:border-amber-200 shadow-md hover:shadow-xl hover:shadow-amber-200/40 transition-all duration-300 flex flex-col h-full group relative">
                    <div class="relative w-full aspect-4/5 sm:aspect-square rounded-2xl overflow-hidden bg-amber-50/50 mb-4">
                        <a href="{{ route('products.show', $product) }}" class="block w-full h-full">
                            @if($product->image_url ?? null)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-amber-300">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                                </div>
                            @endif
                        </a>

                        
                    </div>

                    <div class="flex-1 flex flex-col px-1 sm:px-2">
                        <a href="{{ route('products.show', $product) }}">
                            <h3 class="text-2xl font-playfair font-black text-black mb-2 line-clamp-1" title="{{ $product->name }}">{{ $product->name }}</h3>
                        </a>
                        
                        @auth
                        @php $isWishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute bottom right-3 z-10 js-wishlist-form">
                                @csrf
                                <button type="submit" class="js-wishlist-btn w-10 h-10 bg-white/90 backdrop-blur-sm border border-gray-200 rounded-full flex items-center justify-center hover:bg-red-50 hover:border-red-200 hover:scale-110 transition-all duration-200 shadow-sm" data-wishlisted="{{ $isWishlisted ? '1' : '0' }}">
                                    <svg class="js-wishlist-icon w-5 h-5 transition-colors duration-200 {{ $isWishlisted ? 'text-red-500 fill-red-500' : 'text-gray-400 hover:text-red-500' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                    </svg>
                                </button>
                            </form>
                        @endauth
                        

                        @if($product->description ?? null)
                                @php
                                    $features = explode('•', $product->description);
                                @endphp
                                <ul class="text-sm text-gray-500 mb-6 line-clamp-2 leading-relaxed">
                                    @foreach($features as $feature)
                                        @if(trim($feature) !== '')
                                            <li>{{ trim($feature) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        </p>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <span class="px-3 py-1 rounded-full border border-amber-200 bg-amber-50 text-xs font-medium text-amber-700 capitalize">
                                {{ $product->category ?? 'Jewelry' }}
                            </span>
                            <span class="px-3 py-1 rounded-full text-xs font-medium border {{ ($product->stock_quantity ?? 0) > 0 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                                {{ ($product->stock_quantity ?? 0) > 0 ? 'In Stock' : 'Sold Out' }}
                            </span>
                        </div>

                        <div class="mt-auto flex items-center justify-between pt-2">
                            <span class="text-xl font-bold text-amber-600">
                                ₱{{ number_format($product->price ?? 0, 2) }}
                            </span>

                            <a href="{{ route('cart.add', $product->id) }}" class="js-add-to-cart-btn flex items-center gap-2 bg-amber-300 hover:bg-amber-400 text-black px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl font-semibold text-md transition-colors shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                <span class="hidden sm:inline">Add to Cart</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-white font-bold text-lg mb-4">No products found.</p>
                    <a href="{{ route('collection') }}" class="text-amber-600 hover:text-amber-700 font-semibold">View all products</a>
                </div>
            @endforelse
        </div>

        <div id="productsPagination" class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        </section>
        </main>        <script>
            document.addEventListener('DOMContentLoaded', function () {
                let activeRequest = null;

                function initToast() {
                    const toast = document.getElementById('cartToast');
                    if (!toast) { return; }
                    requestAnimationFrame(() => {
                        toast.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
                    });
                    window.setTimeout(() => {
                        toast.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
                    }, 2600);
                }

                function showActionToast(message, tone) {
                    let toast = document.getElementById('cartToast');
                    if (!toast) {
                        toast = document.createElement('div');
                        toast.id = 'cartToast';
                        toast.className = 'fixed top-24 left-1/2 -translate-x-1/2 z-50 max-w-sm w-[calc(100vw-1rem)] sm:w-auto px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 opacity-0 translate-y-2 pointer-events-none transition-all duration-500';
                        toast.innerHTML = '<span class="text-sm font-semibold"></span>';
                        document.body.appendChild(toast);
                    }

                    toast.classList.remove('bg-amber-300', 'text-black', 'bg-emerald-500', 'text-white', 'bg-red-500');
                    if (tone === 'success') {
                        toast.classList.add('bg-emerald-500', 'text-white');
                    } else if (tone === 'error') {
                        toast.classList.add('bg-red-500', 'text-white');
                    } else {
                        toast.classList.add('bg-amber-300', 'text-black');
                    }

                    const text = toast.querySelector('span');
                    if (text) text.textContent = message;

                    requestAnimationFrame(() => {
                        toast.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
                    });
                    window.setTimeout(() => {
                        toast.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
                    }, 2200);
                }

                function updateNavbarCartCount(nextCount) {
                    const badge = document.getElementById('navbarCartCount');
                    if (!badge) return;
                    const parsed = Number(nextCount) || 0;
                    badge.textContent = String(parsed);
                    badge.classList.toggle('hidden', parsed <= 0);
                }

                function bindProductActionButtons() {
                    document.querySelectorAll('.js-add-to-cart-btn').forEach((link) => {
                        if (link.dataset.ajaxBound) return;
                        link.dataset.ajaxBound = '1';
                        link.addEventListener('click', async function (event) {
                            event.preventDefault();
                            if (link.dataset.loading === '1') return;
                            link.dataset.loading = '1';
                            link.classList.add('opacity-70', 'pointer-events-none');

                            try {
                                const response = await fetch(link.href, {
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                                });
                                const data = await response.json();
                                if (!response.ok || !data.success) {
                                    throw new Error(data.message || 'Failed to add to cart');
                                }
                                updateNavbarCartCount(data.cart_count);
                                showActionToast(data.message || 'Added to cart!', 'success');
                            } catch (error) {
                                showActionToast('Failed to add to cart.', 'error');
                            } finally {
                                link.classList.remove('opacity-70', 'pointer-events-none');
                                link.dataset.loading = '0';
                            }
                        });
                    });

                    document.querySelectorAll('.js-wishlist-form').forEach((form) => {
                        if (form.dataset.ajaxBound) return;
                        form.dataset.ajaxBound = '1';
                        form.addEventListener('submit', async function (event) {
                            event.preventDefault();
                            const button = form.querySelector('.js-wishlist-btn');
                            const icon = form.querySelector('.js-wishlist-icon');
                            if (!button || !icon) return;
                            if (button.dataset.loading === '1') return;

                            button.dataset.loading = '1';
                            button.classList.add('opacity-70', 'pointer-events-none');

                            try {
                                const fd = new FormData(form);
                                const response = await fetch(form.action, {
                                    method: 'POST',
                                    headers: { 'X-Requested-With': 'XMLHttpRequest' },
                                    body: fd,
                                });
                                const data = await response.json();
                                if (!response.ok || !data.success) {
                                    throw new Error(data.message || 'Failed to update wishlist');
                                }

                                const isAdded = Boolean(data.added);
                                button.dataset.wishlisted = isAdded ? '1' : '0';
                                icon.classList.toggle('text-red-500', isAdded);
                                icon.classList.toggle('fill-red-500', isAdded);
                                icon.classList.toggle('text-gray-400', !isAdded);
                                icon.setAttribute('fill', isAdded ? 'currentColor' : 'none');

                                showActionToast(data.message || 'Wishlist updated.', 'success');
                            } catch (error) {
                                showActionToast('Failed to update wishlist.', 'error');
                            } finally {
                                button.classList.remove('opacity-70', 'pointer-events-none');
                                button.dataset.loading = '0';
                            }
                        });
                    });
                }

                function setSearchHiddenValue(name, value) {
                    const searchForm = document.getElementById('productsSearchForm');
                    if (!searchForm) { return; }
                    const input = searchForm.querySelector('input[name="' + name + '"]');
                    if (input) {
                        input.value = value || '';
                    }
                }

                function syncSearchFormFromUrl(urlString) {
                    const url = new URL(urlString, window.location.origin);
                    const searchForm = document.getElementById('productsSearchForm');
                    if (!searchForm) { return; }

                    const searchInput = searchForm.querySelector('input[name="search"]');
                    if (searchInput) {
                        searchInput.value = url.searchParams.get('search') || '';
                    }
                    setSearchHiddenValue('category', url.searchParams.get('category') || '');
                    setSearchHiddenValue('min_price', url.searchParams.get('min_price') || '');
                    setSearchHiddenValue('max_price', url.searchParams.get('max_price') || '');
                }

                async function loadProducts(urlString, pushState) {
                    const shouldPush = pushState !== false;
                    const productsSection = document.getElementById('productsSection');
                    if (!productsSection) {
                        window.location.href = urlString;
                        return;
                    }

                    if (activeRequest) {
                        activeRequest.abort();
                    }
                    activeRequest = new AbortController();
                    renderProductsSkeleton();
                    productsSection.classList.add('opacity-70', 'pointer-events-none');

                    try {
                        const response = await fetch(urlString, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            signal: activeRequest.signal
                        });
                        if (!response.ok) {
                            throw new Error('Failed to load products');
                        }

                        const html = await response.text();
                        const doc = new DOMParser().parseFromString(html, 'text/html');
                        const nextSection = doc.getElementById('productsSection');
                        if (!nextSection) {
                            window.location.href = urlString;
                            return;
                        }

                        productsSection.innerHTML = nextSection.innerHTML;
                        if (shouldPush) {
                            window.history.pushState({}, '', urlString);
                        }

                        syncSearchFormFromUrl(urlString);
                        bindProductsAjaxUi();
                    } catch (error) {
                        if (error.name !== 'AbortError') {
                            window.location.href = urlString;
                        }
                    } finally {
                        productsSection.classList.remove('opacity-70', 'pointer-events-none');
                    }
                }

                function submitFormAjax(form) {
                    const action = form.getAttribute('action') || window.location.href;
                    const url = new URL(action, window.location.origin);
                    const formData = new FormData(form);

                    formData.forEach((value, key) => {
                        const cleaned = String(value || '').trim();
                        if (cleaned === '') {
                            url.searchParams.delete(key);
                        } else {
                            url.searchParams.set(key, cleaned);
                        }
                    });

                    loadProducts(url.toString(), true);
                }

                function renderProductsSkeleton() {
                    const grid = document.getElementById('productsGrid');
                    if (grid) {
                        const cards = [];
                        for (let i = 0; i < 8; i += 1) {
                            cards.push(
                                '<div class="bg-white rounded-2xl p-3 sm:p-4 border border-amber-100/60 shadow-md">' +
                                    '<div class="w-full aspect-4/5 sm:aspect-square rounded-2xl mb-4 skeleton-shimmer"></div>' +
                                    '<div class="h-6 w-3/4 rounded-lg mb-3 skeleton-shimmer"></div>' +
                                    '<div class="h-4 w-full rounded mb-2 skeleton-shimmer"></div>' +
                                    '<div class="h-4 w-4/5 rounded mb-4 skeleton-shimmer"></div>' +
                                    '<div class="flex gap-2 mb-4">' +
                                        '<div class="h-6 w-20 rounded-full skeleton-shimmer"></div>' +
                                        '<div class="h-6 w-20 rounded-full skeleton-shimmer"></div>' +
                                    '</div>' +
                                    '<div class="flex items-center justify-between">' +
                                        '<div class="h-7 w-24 rounded-lg skeleton-shimmer"></div>' +
                                        '<div class="h-10 w-32 rounded-xl skeleton-shimmer"></div>' +
                                    '</div>' +
                                '</div>'
                            );
                        }
                        grid.innerHTML = cards.join('');
                    }

                    const pagination = document.getElementById('productsPagination');
                    if (pagination) {
                        pagination.innerHTML = '<div class="h-10 w-80 rounded-xl skeleton-shimmer"></div>';
                    }
                }

                function initPriceSlider(sliderId, bubbleId, maxInputId, minInputId) {
                    const slider = document.getElementById(sliderId);
                    const bubble = document.getElementById(bubbleId);
                    const maxInput = document.getElementById(maxInputId);
                    const minInput = document.getElementById(minInputId);
                    if (!slider || !bubble || !maxInput || !minInput) { return; }
                    const form = slider.closest('form');
                    let submitTimeout = null;

                    function autoSubmit() {
                        if (!form) { return; }
                        window.clearTimeout(submitTimeout);
                        submitTimeout = window.setTimeout(() => {
                            submitFormAjax(form);
                        }, 250);
                    }

                    function clampValues() {
                        let minVal = parseInt(minInput.value || '0', 10);
                        let maxVal = parseInt(maxInput.value || '0', 10);
                        const cap = parseInt(slider.max || '5000', 10);
                        if (isNaN(minVal) || minVal < 0) { minVal = 0; }
                        if (isNaN(maxVal) || maxVal < 0) { maxVal = 0; }
                        if (minVal > cap) { minVal = cap; }
                        if (maxVal > cap) { maxVal = cap; }
                        if (maxVal < minVal) { maxVal = minVal; }
                        minInput.value = String(minVal);
                        maxInput.value = String(maxVal);
                        slider.value = String(maxVal);
                        return { minVal, maxVal, cap };
                    }

                    function updateBubble() {
                        const values = clampValues();
                        if (!values) { return; }
                        const percent = values.cap > 0 ? (values.maxVal / values.cap) * 100 : 0;
                        bubble.textContent = '\u20B1' + values.maxVal.toLocaleString();
                        bubble.style.left = 'calc(' + percent + '%)';
                    }

                    slider.addEventListener('input', function () {
                        maxInput.value = slider.value;
                        updateBubble();
                    });
                    slider.addEventListener('change', autoSubmit);
                    maxInput.addEventListener('input', updateBubble);
                    minInput.addEventListener('input', updateBubble);
                    maxInput.addEventListener('change', autoSubmit);
                    minInput.addEventListener('change', autoSubmit);
                    updateBubble();
                }

                function bindProductsAjaxUi() {
                    const searchToggleBtn = document.getElementById('searchToggleBtn');
                    const searchPanel = document.getElementById('searchPanel');
                    const searchInput = document.getElementById('productsSearchInput');
                    if (searchToggleBtn && searchPanel && !searchToggleBtn.dataset.bound) {
                        const setSearchOpen = (isOpen) => {
                            searchPanel.classList.toggle('hidden', !isOpen);
                            searchToggleBtn.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                            if (isOpen && searchInput) {
                                window.setTimeout(() => searchInput.focus(), 100);
                            }
                        };

                        searchToggleBtn.addEventListener('click', function () {
                            setSearchOpen(searchPanel.classList.contains('hidden'));
                        });

                        document.addEventListener('click', function (event) {
                            if (!searchPanel.classList.contains('hidden')) {
                                const clickedInsidePanel = searchPanel.contains(event.target);
                                const clickedToggle = searchToggleBtn.contains(event.target);
                                if (!clickedInsidePanel && !clickedToggle) {
                                    setSearchOpen(false);
                                }
                            }
                        });

                        if (searchInput) {
                            searchInput.addEventListener('keydown', function (event) {
                                if (event.key === 'Escape') {
                                    setSearchOpen(false);
                                }
                            });
                        }

                        searchToggleBtn.dataset.bound = '1';
                    }

                    const searchForm = document.getElementById('productsSearchForm');
                    if (searchForm && !searchForm.dataset.ajaxBound) {
                        searchForm.addEventListener('submit', function (event) {
                            event.preventDefault();
                            submitFormAjax(searchForm);
                        });
                        searchForm.dataset.ajaxBound = '1';
                    }

                    document.querySelectorAll('form[data-products-async-form]').forEach((form) => {
                        if (form.dataset.ajaxBound) { return; }
                        form.addEventListener('submit', function (event) {
                            event.preventDefault();
                            submitFormAjax(form);
                        });
                        form.dataset.ajaxBound = '1';
                    });

                    document.querySelectorAll('a[data-products-async-link]').forEach((link) => {
                        if (link.dataset.ajaxBound) { return; }
                        link.addEventListener('click', function (event) {
                            event.preventDefault();
                            loadProducts(link.href, true);
                        });
                        link.dataset.ajaxBound = '1';
                    });

                    const pagination = document.getElementById('productsPagination');
                    if (pagination && !pagination.dataset.ajaxBound) {
                        pagination.addEventListener('click', function (event) {
                            const link = event.target.closest('a[href]');
                            if (!link) { return; }
                            event.preventDefault();
                            loadProducts(link.href, true);
                        });
                        pagination.dataset.ajaxBound = '1';
                    }

                    initPriceSlider('priceRangeSlider', 'priceBubble', 'maxPriceInput', 'minPriceInput');
                    initPriceSlider('priceRangeSliderMobile', 'priceBubbleMobile', 'maxPriceInputMobile', 'minPriceInputMobile');
                    bindProductActionButtons();
                }

                window.addEventListener('popstate', function () {
                    loadProducts(window.location.href, false);
                });

                syncSearchFormFromUrl(window.location.href);
                bindProductsAjaxUi();
                initToast();
            });
        </script>

        @if(session('success'))
        <div id="cartToast" class="fixed top-24 left-1/2 -translate-x-1/2 z-50 max-w-sm w-[calc(100vw-1rem)] sm:w-auto px-4 py-3 bg-amber-300 text-black rounded-xl shadow-lg flex items-center gap-3 opacity-0 translate-y-2 pointer-events-none transition-all duration-500">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="w-6 h-6"><!--!Font Awesome Free v7.2.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2026 Fonticons, Inc.-->
                <path d="M0 72C0 58.7 10.7 48 24 48L69.3 48C96.4 48 119.6 67.4 124.4 94L124.8 96L312 96L312 198.1L281 167.1C271.6 157.7 256.4 157.7 247.1 167.1C237.8 176.5 237.7 191.7 247.1 201L319.1 273C328.5 282.4 343.7 282.4 353 273L425 201C434.4 191.6 434.4 176.4 425 167.1C415.6 157.8 400.4 157.7 391.1 167.1L360.1 198.1L360.1 96L537.5 96C557.5 96 572.6 114.2 568.9 133.9L537.8 299.8C532.1 330.1 505.7 352 474.9 352L171.3 352L176.4 380.3C178.5 391.7 188.4 400 200 400L456 400C469.3 400 480 410.7 480 424C480 437.3 469.3 448 456 448L200.1 448C165.3 448 135.5 423.1 129.3 388.9L77.2 102.6C76.5 98.8 73.2 96 69.3 96L24 96C10.7 96 0 85.3 0 72zM160 528C160 501.5 181.5 480 208 480C234.5 480 256 501.5 256 528C256 554.5 234.5 576 208 576C181.5 576 160 554.5 160 528zM384 528C384 501.5 405.5 480 432 480C458.5 480 480 501.5 480 528C480 554.5 458.5 576 432 576C405.5 576 384 554.5 384 528z"/>
            </svg>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @include('partials.footer')
</body>
</html>

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

                <div class="mt-4 sm:mt-0 sm:ml-6 w-full sm:w-96">
                    <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2 w-full">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                            class="w-full px-4 py-3 rounded-lg bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-400 shadow-sm transition-all">
                    </form>
                </div>
            </div>
        </div>
    </section>

    <main class="container mx-auto px-4 sm:px-6 pb-16">

        {{-- Category Filter --}}

        {{-- MOBILE DROPDOWN --}}
        <div class="block sm:hidden mb-6">
            <form method="GET" action="{{ route('products.index') }}">
                <input type="hidden" name="search" value="{{ request('search') }}">

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


        {{-- DESKTOP BUTTONS --}}
        <div class="hidden sm:flex flex-wrap justify-center gap-2 sm:gap-3 mb-10">
            <a href="{{ route('products.index', request()->only('search')) }}"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ !request('category') ? 'bg-amber-300 text-black shadow-sm' : 'bg-white/80 text-gray-700 hover:bg-amber-50 hover:text-amber-700 border border-gray-200' }}">
                All
            </a>

            @foreach($filterCategories as $cat)
                <a href="{{ route('products.index', array_merge(request()->only('search'), ['category' => $cat])) }}"
                    class="px-4 py-2 rounded-lg text-sm font-semibold capitalize transition-all {{ (request('category') ?? '') === $cat ? 'bg-amber-300 text-black shadow-sm' : 'bg-white/80 text-gray-700 hover:bg-amber-50 hover:text-amber-700 border border-gray-200' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        {{-- Products Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
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
                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute bottom right-3 z-10">
                                @csrf
                                <button type="submit" class="w-10 h-10 bg-white/90 backdrop-blur-sm border border-gray-200 rounded-full flex items-center justify-center hover:bg-red-50 hover:border-red-200 hover:scale-110 transition-all duration-200 shadow-sm">
                                    <svg class="w-5 h-5 transition-colors duration-200 {{ $isWishlisted ? 'text-red-500 fill-red-500' : 'text-gray-400 hover:text-red-500' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
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

                            <a href="{{ route('cart.add', $product->id) }}" class="flex items-center gap-2 bg-amber-300 hover:bg-amber-400 text-black px-4 py-2 sm:px-5 sm:py-2.5 rounded-xl font-semibold text-md transition-colors shadow-sm">
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
                    <p class="text-gray-500 text-lg mb-4">No products found.</p>
                    <a href="{{ route('collection') }}" class="text-amber-600 hover:text-amber-700 font-semibold">View all products</a>
                </div>
            @endforelse
        </div>

        <div class="mt-12 flex justify-center">
                {{ $products->links() }}
            </div>
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
            const toast = document.getElementById('cartToast');
            if (!toast) {
                return;
            }

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
            });

            window.setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
            }, 2600);
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

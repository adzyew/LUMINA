<!doctype html>
<html lang="en">
<head>
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
        <div class="container mx-auto px-4">
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
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-10">
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
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($products as $product)
        <div class="bg-white rounded-[2rem] p-3 sm:p-4 border border-amber-100/60 hover:border-amber-200 shadow-md hover:shadow-xl hover:shadow-amber-200/40 transition-all duration-300 flex flex-col h-full group relative">

            <div class="relative w-full aspect-[4/5] sm:aspect-square rounded-[1.5rem] overflow-hidden bg-amber-50/50 mb-4">
                <a href="{{ route('products.show', $product) }}" class="block w-full h-full">
                    @if($product->image_url ?? null)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-amber-300">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                        </div>
                    @endif
                </a>

                @auth
                    @php $isWishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3 z-10">
                        @csrf
                        <button type="submit" class="w-10 h-10 bg-white/90 backdrop-blur-sm border border-gray-200 rounded-full flex items-center justify-center hover:bg-red-50 hover:border-red-200 hover:scale-110 transition-all duration-200 shadow-sm">
                            <svg class="w-5 h-5 transition-colors duration-200 {{ $isWishlisted ? 'text-red-500 fill-red-500' : 'text-gray-400 hover:text-red-500' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </form>
                @endauth
            </div>

            <div class="flex-1 flex flex-col px-1 sm:px-2">
                <a href="{{ route('products.show', $product) }}">
                    <h3 class="text-lg font-playfair font-bold text-gray-900 mb-2 line-clamp-1" title="{{ $product->name }}">{{ $product->name }}</h3>
                </a>

                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="px-3 py-1 rounded-full border border-amber-200 bg-amber-50 text-xs font-medium text-amber-700 capitalize">
                        {{ $product->category ?? 'Jewelry' }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium border {{ ($product->stock_quantity ?? 0) > 0 ? 'bg-green-50 text-green-700 border-green-200' : 'bg-red-50 text-red-700 border-red-200' }}">
                        {{ ($product->stock_quantity ?? 0) > 0 ? 'In Stock' : 'Sold Out' }}
                    </span>
                </div>

                <p class="text-sm text-gray-500 mb-6 line-clamp-2 leading-relaxed">
                    {{ $product->description ?? 'Discover the perfect piece to elevate your style. Add a touch of elegance to any outfit.' }}
                </p>

                <div class="mt-auto flex items-center justify-between pt-2">
                    <span class="text-2xl font-black text-amber-600">
                        ₱{{ number_format($product->price ?? 0, 2) }}
                    </span>

                    <a href="{{ route('products.show', $product) }}" class="flex items-center gap-2 bg-amber-300 hover:bg-amber-400 text-black px-4 py-2 sm:px-5 sm:py-2.5 rounded-full font-bold text-sm transition-colors shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 sm:w-5 sm:h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                        <span class="hidden sm:inline">Add</span>
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

    @include('partials.footer')
</body>
</html>

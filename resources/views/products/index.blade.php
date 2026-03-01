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
        .text-gold { color: #fbbf24; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white relative antialiased transition-colors">

    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="Luxury background" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-gray-900/20 dark:bg-black/40 backdrop-blur-sm transition-colors"></div>
        <div class="absolute inset-0 bg-linear-to-b from-amber-300/10 via-white/50 to-gray-100/80 dark:from-amber-300/20 dark:via-black/70 dark:to-black/90 transition-colors"></div>
    </div>

    @include('partials.navbar')

    <section class="relative min-h-48 pt-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="sm:flex-1 text-left">
                    <h1 class="text-4xl sm:text-5xl font-playfair font-bold mt-4">
                        Explore <span class="text-gold">Our Collection</span>
                    </h1>
                    <p class="mt-4 text-gray-700 dark:text-white">Discover the finest handcrafted jewelry.</p>
                </div>

                <div class="mt-4 sm:mt-0 sm:ml-6 w-full sm:w-96">
                    <form method="GET" action="{{ route('products.index') }}" class="flex items-center gap-2 w-full">
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                            class="w-full px-4 py-3 rounded-lg bg-gray-800/80 text-white placeholder-gray-400 border border-white/10 focus:outline-none focus:ring-2 focus:ring-amber-300 transition-all">
                    </form>
                </div>
            </div>
        </div>
    </section>

    <main class="container mx-auto px-4 sm:px-6 pb-16">

        {{-- Category Filter --}}
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-10">
            <a href="{{ route('products.index', request()->only('search')) }}"
                class="px-4 py-2 rounded-lg text-sm font-semibold transition-all {{ !request('category') ? 'bg-amber-300 text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                All
            </a>
            @foreach($filterCategories as $cat)
                <a href="{{ route('products.index', array_merge(request()->only('search'), ['category' => $cat])) }}"
                    class="px-4 py-2 rounded-lg text-sm font-semibold capitalize transition-all {{ (request('category') ?? '') === $cat ? 'bg-amber-300 text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        {{-- Category Cards (visual filter) --}}
        

        {{-- Products Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @forelse($products as $product)
        <div class="bg-gray-900/60 rounded-[2rem] p-3 sm:p-4 border border-white/5 hover:border-amber-300/30 shadow-sm hover:shadow-2xl hover:shadow-amber-500/10 transition-all duration-300 flex flex-col h-full group relative">
            
            <div class="relative w-full aspect-[4/5] sm:aspect-square rounded-[1.5rem] overflow-hidden bg-gray-800/50 mb-4">
                <a href="{{ route('products.show', $product) }}" class="block w-full h-full">
                    @if($product->image_url ?? null)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-600">
                            <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                        </div>
                    @endif
                </a>

                @auth
                    @php $isWishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3 z-10">
                        @csrf
                        <button type="submit" class="w-10 h-10 bg-black/40 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center hover:bg-black/70 hover:scale-110 transition-all duration-200 shadow-lg">
                            <svg class="w-5 h-5 transition-colors duration-200 {{ $isWishlisted ? 'text-red-500 fill-red-500' : 'text-white hover:text-red-400' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </form>
                @endauth
            </div>

            <div class="flex-1 flex flex-col px-1 sm:px-2">
                <a href="{{ route('products.show', $product) }}">
                    <h3 class="text-lg font-playfair font-bold text-white mb-2 line-clamp-1" title="{{ $product->name }}">{{ $product->name }}</h3>
                </a>

                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="px-3 py-1 rounded-full border border-white/10 text-xs font-medium text-amber-300 capitalize">
                        {{ $product->category ?? 'Jewelry' }}
                    </span>
                    <span class="px-3 py-1 rounded-full text-xs font-medium border {{ ($product->stock_quantity ?? 0) > 0 ? 'bg-green-500/10 text-green-400 border-green-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20' }}">
                        {{ ($product->stock_quantity ?? 0) > 0 ? 'In Stock' : 'Sold Out' }}
                    </span>
                </div>

                <p class="text-sm text-gray-400 mb-6 line-clamp-2 leading-relaxed">
                    {{ $product->description ?? 'Discover the perfect piece to elevate your style. Add a touch of elegance to any outfit.' }}
                </p>

                <div class="mt-auto flex items-center justify-between pt-2">
                    <span class="text-2xl font-black text-amber-300">
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
            <p class="text-gray-400 text-lg mb-4">No products found.</p>
            <a href="{{ route('collection') }}" class="text-amber-300 hover:text-amber-200 font-semibold">View all products</a>
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

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
        <div class="absolute inset-0 bg-gradient-to-b from-amber-300/10 via-white/50 to-gray-100/80 dark:from-amber-300/20 dark:via-black/70 dark:to-black/90 transition-colors"></div>
    </div>

    @include('partials.navbar')

    <section class="relative min-h-[12rem] pt-20 flex items-center justify-center">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl sm:text-5xl font-playfair font-bold leading-tight">
                Explore <span class="text-gold">Our Collection</span>
            </h1>
            <p class="mt-4 text-gray-700 dark:text-white">Discover the finest handcrafted jewelry.</p>
        </div>
    </section>

    <main class="container mx-auto px-4 sm:px-6 pb-16">
        {{-- Search --}}
        <form method="GET" action="{{ route('products.index') }}" class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto mb-8">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                class="flex-1 px-5 py-3 rounded-2xl bg-gray-800/80 text-white placeholder-gray-400 border border-white/10 focus:outline-none focus:ring-2 focus:ring-amber-300 transition-all">
            <button type="submit" class="px-6 py-3 bg-amber-300 text-black rounded-2xl font-bold hover:bg-amber-400 transition-colors">
                Search
            </button>
        </form>

        {{-- Category Filter --}}
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-10">
            <a href="{{ route('products.index', request()->only('search')) }}"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ !request('category') ? 'bg-amber-300 text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                All
            </a>
            @foreach($filterCategories as $cat)
                <a href="{{ route('products.index', array_merge(request()->only('search'), ['category' => $cat])) }}"
                    class="px-4 py-2 rounded-xl text-sm font-semibold capitalize transition-all {{ (request('category') ?? '') === $cat ? 'bg-amber-300 text-black' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border border-white/10' }}">
                    {{ $cat }}
                </a>
            @endforeach
</div>

        {{-- Category Cards (visual filter) --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-12">
            @foreach($filterCategories as $cat)
                <a href="{{ route('products.index', array_merge(request()->only('search'), ['category' => $cat])) }}"
                    class="group bg-white/5 p-4 rounded-2xl border border-white/10 transition-all duration-300 hover:-translate-y-1 hover:border-amber-300/40 hover:shadow-lg hover:shadow-amber-500/10">
                    <h3 class="text-sm font-playfair font-bold text-amber-300 mb-2 capitalize">{{ $cat }}</h3>
                    @php
                        $img = match($cat) {
                            'watches' => 'Watches.jpg',
                            'rings' => 'Ring.jpg',
                            'bracelets' => 'Bracelet.jpg',
                            'necklaces' => 'Necklace.jpg',
                            'earrings' => 'Earrings.jpg',
                            default => 'Ring.jpg',
                        };
                    @endphp
                    <img src="{{ asset('IMAGES/' . $img) }}" alt="{{ $cat }}" class="rounded-xl w-full h-24 object-cover mb-2">
                    <p class="text-xs text-gray-400">View collection</p>
                </a>
                    @endforeach
            </div>

        {{-- Products Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse($products as $product)
                <div class="group bg-gray-900/60 rounded-2xl overflow-hidden border border-white/5 hover:border-amber-300/30 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-amber-500/10 relative">
                    @auth
                        @php $isWishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3 z-10">
                            @csrf
                            <button type="submit" class="w-10 h-10 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-black/70 transition-colors">
                                <svg class="w-5 h-5 {{ $isWishlisted ? 'text-red-500 fill-red-500' : 'text-white' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </form>
                    @endauth
                    <a href="{{ route('products.show', $product) }}" class="block">
                        <div class="relative h-56 bg-gray-800/50 flex items-center justify-center overflow-hidden">
                            @if($product->image_url ?? null)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-600">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors flex items-end justify-center p-4">
                                <span class="py-2 px-4 bg-amber-300 text-black font-bold text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">View Details</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-amber-300 uppercase tracking-widest mb-2">{{ ucfirst($product->category ?? 'Jewelry') }}</p>
                            <h3 class="text-lg font-playfair font-bold text-white mb-2 truncate" title="{{ $product->name }}">{{ $product->name }}</h3>
                        <div class="flex justify-between items-center">
                                <span class="text-xl font-bold text-amber-300">₱{{ number_format($product->price ?? 0, 2) }}</span>
                                <span class="text-xs {{ ($product->stock_quantity ?? 0) > 0 ? 'text-green-400' : 'text-red-500' }}">
                                    {{ ($product->stock_quantity ?? 0) > 0 ? 'In Stock' : 'Sold Out' }}
                            </span>
                        </div>
                    </div>
                    </a>
                </div>
                @empty
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-400 text-lg mb-4">No products found.</p>
                    <a href="{{ route('products.index') }}" class="text-amber-300 hover:text-amber-200 font-semibold">View all products</a>
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

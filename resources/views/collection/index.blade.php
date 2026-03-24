<!doctype html>
<html lang="en">
<head>
    @include('partials.favicon')
    <title>All Collections | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-stone-100 text-gray-900 relative antialiased">

    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-stone-400/30 backdrop-blur-[2px]"></div>
        <div class="absolute inset-0 bg-linear-to-b from-stone-200/70 via-stone-100/50 to-stone-200/80"></div>
    </div>

    @include('partials.navbar')

    <section class="relative min-h-48 pt-20 flex items-center justify-center">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl sm:text-5xl font-playfair font-bold leading-tight">
                All <span class="text-amber-300">Collections</span>
            </h1>
            <p class="mt-4 text-gray-600">Browse our complete catalog of handcrafted jewelry.</p>
        </div>
    </section>

    <main class="container mx-auto px-4 sm:px-6 pb-16">
        {{-- Search --}}
        <form method="GET" action="{{ route('collection') }}" class="flex flex-col sm:flex-row gap-4 max-w-xl mx-auto mb-8">
            <input type="hidden" name="category" value="{{ request('category') }}">
            <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}"
                class="flex-1 px-5 py-3 rounded-2xl bg-white border border-gray-300 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-amber-300 transition-all">
            <button type="submit" class="px-6 py-3 bg-amber-300 text-black rounded-2xl font-bold hover:bg-amber-400 transition-colors">
                Search
            </button>
        </form>

        {{-- Category Filter --}}
        <div class="flex flex-wrap justify-center gap-2 sm:gap-3 mb-10">
            <a href="{{ route('collection', request()->only('search')) }}"
                class="px-4 py-2 rounded-xl text-sm font-semibold transition-all {{ !request('category') ? 'bg-amber-300 text-black' : 'bg-white/80 text-gray-700 border border-gray-200 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-200' }}">
                All Products
            </a>
            @foreach($filterCategories as $cat)
                <a href="{{ route('collection', array_merge(request()->only('search'), ['category' => $cat])) }}"
                    class="px-4 py-2 rounded-xl text-sm font-semibold capitalize transition-all {{ (request('category') ?? '') === $cat ? 'bg-amber-300 text-black' : 'bg-white/80 text-gray-700 border border-gray-200 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-200' }}">
                    {{ $cat }}
                </a>
            @endforeach
        </div>

        {{-- Products Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($products as $product)
                <div class="group bg-white rounded-2xl overflow-hidden border border-amber-100/60 hover:border-amber-200 transition-all duration-300 hover:-translate-y-2 shadow-md hover:shadow-xl hover:shadow-amber-200/40 relative">
                    @auth
                        @php $isWishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-3 right-3 z-10">
                            @csrf
                            <button type="submit" class="w-10 h-10 bg-white/90 backdrop-blur-sm border border-gray-200 rounded-full flex items-center justify-center hover:bg-red-50 hover:border-red-200 transition-colors">
                                <svg class="w-5 h-5 {{ $isWishlisted ? 'text-red-500 fill-red-500' : 'text-gray-400' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </form>
                    @endauth
                    <a href="{{ route('products.show', $product) }}" class="block">
                        <div class="relative h-56 bg-amber-50/50 flex items-center justify-center overflow-hidden">
                            @if($product->image_url ?? null)
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-600">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0 bg-amber-300/0 group-hover:bg-amber-300/10 transition-colors flex items-end justify-center p-4">
                                <span class="py-2 px-4 bg-amber-300 text-black font-bold text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">View Details</span>
                            </div>
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-amber-300 uppercase tracking-widest mb-2">{{ ucfirst($product->category ?? 'Jewelry') }}</p>
                            <h3 class="text-lg font-playfair font-bold text-gray-900 mb-2 truncate" title="{{ $product->name }}">{{ $product->name }}</h3>
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

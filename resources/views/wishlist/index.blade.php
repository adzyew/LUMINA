<!doctype html>
<html lang="en">
<head>
    <title>My Wishlist | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white relative antialiased transition-colors">

    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-gray-900/20 dark:bg-black/40 backdrop-blur-sm"></div>
    </div>

    @include('partials.navbar')

    <section class="relative min-h-[12rem] pt-20 flex items-center justify-center">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl sm:text-5xl font-playfair font-bold leading-tight">
                My <span class="text-amber-300">Wishlist</span>
            </h1>
            <p class="mt-4 text-gray-300">Your saved favorites</p>
        </div>
    </section>

    <main class="container mx-auto px-4 sm:px-6 pb-16">
        @if($wishlistItems->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlistItems as $item)
                    <div class="group bg-gray-900/60 rounded-2xl overflow-hidden border border-white/5 hover:border-amber-300/30 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-amber-500/10 relative">
                        <form action="{{ route('wishlist.toggle', $item->product) }}" method="POST" class="absolute top-3 right-3 z-10">
                            @csrf
                            <button type="submit" class="w-10 h-10 bg-black/50 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-black/70 transition-colors">
                                <svg class="w-5 h-5 text-red-500 fill-red-500" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </form>
                        <a href="{{ route('products.show', $item->product) }}" class="block">
                            <div class="relative h-56 bg-gray-800/50 flex items-center justify-center overflow-hidden">
                                @if($item->product->image_url ?? null)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-amber-300 uppercase tracking-widest mb-2">{{ ucfirst($item->product->category ?? 'Jewelry') }}</p>
                                <h3 class="text-lg font-playfair font-bold text-white mb-2 truncate" title="{{ $item->product->name }}">{{ $item->product->name }}</h3>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-amber-300">₱{{ number_format($item->product->price ?? 0, 2) }}</span>
                                    <span class="text-xs {{ ($item->product->stock_quantity ?? 0) > 0 ? 'text-green-400' : 'text-red-500' }}">
                                        {{ ($item->product->stock_quantity ?? 0) > 0 ? 'In Stock' : 'Sold Out' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $wishlistItems->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <p class="text-gray-400 text-lg mb-4">Your wishlist is empty</p>
                <a href="{{ route('products.index') }}" class="text-amber-300 hover:text-amber-200 font-semibold">Start adding items to your wishlist</a>
            </div>
        @endif
    </main>

    @include('partials.footer')
</body>
</html>

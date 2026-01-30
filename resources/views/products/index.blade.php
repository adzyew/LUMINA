<!doctype html>
<html>
<head>
    <title>Shop Collection | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .font-playfair {
            font-family: 'Playfair Display', serif;
        }
        .text-gold {
            color: #fbbf24;
        }
        .hover-lift {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(251, 191, 36, 0.3);
        }
    </style>
</head>
<body class="text-white relative antialiased">

    <!-- Background -->
    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="Luxury background" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
        <!-- Dark luxury overlay -->
        <div class="absolute inset-0 bg-linear-to-b from-amber-300/20 via-black/70 to-black/90"></div>
    </div>

    <!-- Navigation / Header -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-opacity-90 backdrop-blur-md border-b border-amber-300">
        <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4 flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <div class="flex space-x-1">
                    <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full transform -rotate-12"></div>
                    <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full"></div>
                    <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full transform rotate-12"></div>
                </div>
                <span class="font-playfair font-black text-2xl sm:text-3xl">Lumina</span>
            </div>

            <!-- Navigation Links -->
            <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                <a href="{{ route('home') }}" class="text-white hover:text-amber-300 transition-colors duration-300 lg:text-lg font-playfair font-semibold">Home</a>
                <a href="{{ route('products.index') }}" class="text-white hover:text-amber-300 transition-colors duration-300 lg:text-lg font-playfair font-semibold">Collections</a>
                <a href="#features" class="text-white hover:text-amber-300 transition-colors duration-300 lg:text-lg font-playfair font-semibold">About</a>
                <a href="#contact" class="text-white hover:text-amber-300 transition-colors duration-300 lg:text-lg font-playfair font-semibold">Contact</a>
            </div>

            <!-- Icons -->
            <div class="flex items-center space-x-4 sm:space-x-6">
                <!-- Search Icon -->
                <button class="hidden sm:block text-gray-300 hover:text-amber-300 transition-colors duration-300">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                <!-- Account Icon -->
                <button class="hidden sm:block text-gray-300 hover:text-amber-300 transition-colors duration-300">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </button>
                <!-- Cart Icon -->
                <button class="text-gray-300 hover:text-amber-300 transition-colors duration-300 relative">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="absolute -top-1 -right-1 sm:-top-2 sm:-right-2 bg-amber-300 text-black text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center font-semibold">3</span>
                </button>
            </div>
        </div>
    </nav>

    <!-- Hero Section / Header Text -->
    <section class="relative min-h-[300px] pt-20 flex items-center justify-center">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-5xl sm:text-6xl font-playfair font-bold leading-tight">
                Explore <span class="text-gold">Our Collection</span>
            </h1>
            <p class="mt-4 text-white text-lg">Discover the finest handcrafted jewelry.</p>
        </div>
    </section>

    <main class="flex-1 min-w-0">
    <div class="mb-8">
        <form method="GET" action="{{ route('products.index') }}" class="flex items-center w-full max-w-md mx-auto">
            <input 
                type="text" 
                name="search" 
                placeholder="Search products..." 
                value="{{ request('search') }}" 
                class="w-full px-5 py-3 rounded-l-2xl bg-gray-800 text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-amber-300 focus:ring-offset-1 transition-all duration-300"
            >
            <button 
                type="submit" 
                class="bg-amber-300 text-black px-6 py-3 rounded-r-2xl font-bold hover:bg-white transition-colors duration-300"
            >
                Search
            </button>
        </form>
    </div>
    <aside class="w-full shrink-0">
                        
        <div class="grid grid-cols-1 sm:grid-cols-4 lg:grid-cols-5 gap-5 items-stretch px-4 lg:px-8">

    <!-- WATCHES -->
    <a href="{{ route('products.index', ['category' => 'watches']) }}" class="group h-full bg-white/5 p-5 rounded-2xl border border-white/10
                transition-all duration-300
                hover:-translate-y-1
                hover:border-amber-300/40
                hover:shadow-xl
                hover:shadow-amber-500/20">
        <h3 class="text-lg font-playfair font-bold text-amber-300 mb-3">Watches</h3>
        <img src="{{ asset('IMAGES/Watches.jpg') }}"
             class="rounded-xl w-full h-36 object-cover mb-4" alt="Watches">
        <p class="text-sm font-bold text-white">LUMINA Watches</p>
        <p class="text-xs text-gray-400">Elegant craftsmanship for every occasion.</p>
    </a>

    <!-- RINGS -->
    <a href="{{ route('products.index', ['category' => 'rings']) }}" class="group h-full bg-white/5 p-5 rounded-2xl border border-white/10
                transition-all duration-300
                hover:-translate-y-1
                hover:border-amber-300/40
                hover:shadow-xl
                hover:shadow-amber-500/20">
        <h3 class="text-lg font-playfair font-bold text-amber-300 mb-3">Rings</h3>
        <img src="{{ asset('IMAGES/Ring.jpg') }}"
             class="rounded-xl w-full h-36 object-cover mb-4" alt="Rings">
        <p class="text-sm font-bold text-white">LUMINA Rings</p>
        <p class="text-xs text-gray-400">Timeless designs that shine bright.</p>
    </a>

    <!-- BRACELETS -->
    <a href="{{ route('products.index', ['category' => 'bracelets']) }}" class="group h-full bg-white/5 p-5 rounded-2xl border border-white/10
                transition-all duration-300
                hover:-translate-y-1
                hover:border-amber-300/40
                hover:shadow-xl
                hover:shadow-amber-500/20">
        <h3 class="text-lg font-playfair font-bold text-amber-300 mb-3">Bracelets</h3>
        <img src="{{ asset('IMAGES/Bracelet.jpg') }}"
             class="rounded-xl w-full h-36 object-cover mb-4" alt="Bracelets">
        <p class="text-sm font-bold text-white">LUMINA Bracelets</p>
        <p class="text-xs text-gray-400">Sophisticated elegance on your wrist.</p>
    </a>

    <!-- NECKLACES -->
    <a href="{{ route('products.index', ['category' => 'necklaces']) }}" class="group h-full bg-white/5 p-5 rounded-2xl border border-white/10
                transition-all duration-300
                hover:-translate-y-1
                hover:border-amber-300/40
                hover:shadow-xl
                hover:shadow-amber-500/20">
        <h3 class="text-lg font-playfair font-bold text-amber-300 mb-3">Necklaces</h3>
        <img src="{{ asset('IMAGES/Necklace.jpg') }}"
             class="rounded-xl w-full h-36 object-cover mb-4" alt="Necklaces">
        <p class="text-sm font-bold text-white">LUMINA Necklaces</p>
        <p class="text-xs text-gray-400">Make a statement with every look.</p>
    </a>

    <!-- EARRINGS -->
    <a href="{{ route('products.index', ['category' => 'earrings']) }}" class="group h-full bg-white/5 p-5 rounded-2xl border border-white/10
                transition-all duration-300
                hover:-translate-y-1
                hover:border-amber-300/40
                hover:shadow-xl
                hover:shadow-amber-500/20">
        <h3 class="text-lg font-playfair font-bold text-amber-300 mb-3">Earrings</h3>
        <img src="{{ asset('IMAGES/Earrings.jpg') }}"
             class="rounded-xl w-full h-36 object-cover mb-4" alt="Earrings">
        <p class="text-sm font-bold text-white">LUMINA Earrings</p>
        <p class="text-xs text-gray-400">Delicate details for a refined touch.</p>
    </a>
</div>
    </div>
</aside>
                    </main>
                    @foreach($categories as $cat)
                    <li>
                        <a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="text-gray-300 hover:text-white transition-colors flex items-center justify-between">
                            {{ $cat->name }} 
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <main class="flex-1">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($products as $product)
                <div class="group bg-gray-900 rounded-2xl overflow-hidden border border-white/5 hover:border-amber-300/30 transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl hover:shadow-amber-500/10">
                    <div class="relative h-64 bg-gray-800 flex items-center justify-center overflow-hidden">
                        <img src="{{ asset($product->image_path) }}" 
                             alt="{{ $product->name }}" 
                             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        
                        <div class="absolute bottom-0 left-0 right-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                             <a href="{{ route('products.show', $product->id) }}" class="block w-full py-3 bg-amber-300 text-black text-center font-bold text-sm rounded-lg hover:bg-white transition-colors">
                                View Details
                             </a>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <p class="text-xs text-amber-300 uppercase tracking-widest mb-2">{{ $product->category->name ?? 'Jewelry' }}</p>
                        <h3 class="text-lg font-playfair font-bold text-white mb-2 truncate">{{ $product->name }}</h3>
                        <div class="flex justify-between items-center">
                            <span class="text-xl font-bold text-gray-200">${{ number_format($product->price, 2) }}</span>
                            <span class="text-xs text-gray-500">
                                @if($product->stock_quantity > 0) In Stock @else <span class="text-red-500">Sold Out</span> @endif
                            </span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center py-20">
                </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $products->links() }} 
            </div>
        </main>
    </div>

    @include('partials.footer')
</body>
</html>
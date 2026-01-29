<!doctype html>
<html>
<head>
    <title>Shop Collection | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-black text-white font-sans antialiased">
    
    @include('partials.navbar') 

    <div class="relative bg-gray-900 py-16 sm:py-24">
        <div class="absolute inset-0 overflow-hidden">
             <img src="{{ asset('IMAGES/header-bg.jpg') }}" class="w-full h-full object-cover opacity-20" alt="">
             <div class="absolute inset-0 bg-linear-to-t from-black to-transparent"></div>
        </div>
        <div class="relative container mx-auto px-4 text-center">
            <h1 class="text-4xl sm:text-5xl font-playfair font-bold text-white mb-4">Our Collection</h1>
            <p class="text-amber-300 text-lg">Discover timeless elegance.</p>
        </div>
    </div>

    <div class="container mx-auto px-4 py-12 flex flex-col md:flex-row gap-8">
        
        <aside class="w-full md:w-64 shrink-0">
            <div class="bg-white/5 p-6 rounded-2xl border border-white/10 sticky top-24">
                <h3 class="text-xl font-playfair font-bold mb-4 text-amber-300">Categories</h3>
                <ul class="space-y-3">
                    <li>
                        <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white transition-colors flex items-center justify-between">
                            All Jewelry <span class="text-xs bg-gray-800 px-2 py-1 rounded-full text-gray-400">View</span>
                        </a>
                    </li>
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
                    <p class="text-gray-500 text-lg">No products found in this category.</p>
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
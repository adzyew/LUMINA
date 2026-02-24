<!doctype html>
<html>
<head>
    <title>My Wishlist | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        footer { transition: opacity 0.4s ease, transform 0.4s ease; opacity: 0; transform: translateY(20px); pointer-events: none; }
        footer.visible { opacity: 1; transform: translateY(0); pointer-events: auto; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white font-sans antialiased flex flex-col min-h-screen transition-colors">

    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-gray-900/20 dark:bg-black/40 backdrop-blur-sm"></div>
    </div>

    @include('partials.navbar')

    <div class="grow container mx-auto px-4 sm:px-6 pb-20 flex flex-col items-center justify-center">

        @if(isset($wishlistItems) && $wishlistItems->count() > 0)
            <div class="flex flex-col lg:flex-row gap-8 max-w-6xl w-full">

                {{-- Wishlist Items --}}
                <div class="lg:w-2/3">
                    <h2 class="text-2xl font-playfair font-bold text-gray-900 dark:text-white mb-6 text-center">Saved Items</h2>
                    <div class="space-y-4">
                        @php $total = 0; @endphp
                        @foreach($wishlistItems as $item)
                            @php $product = $item->product; $price = $product->price ?? 0; $total += $price; @endphp
                            <div class="group bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-5 sm:p-6 hover:border-amber-300/40 dark:hover:border-amber-300/40 transition-all duration-300 hover:shadow-lg dark:hover:shadow-amber-500/5">
                                <div class="flex flex-col sm:flex-row gap-5 items-center">
                                    {{-- Product Image --}}
                                    <div class="w-full sm:w-28 h-28 flex-shrink-0">
                                        <img src="{{ $product->image_url ?? asset('IMAGES/Bracelet.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-xl border border-gray-200 dark:border-white/10">
                                    </div>

                                    {{-- Product Info --}}
                                    <div class="flex-1 flex flex-col justify-between text-center sm:text-left">
                                        <div>
                                            <h3 class="text-lg sm:text-xl font-playfair font-bold text-gray-900 dark:text-white mb-2">{{ $product->name }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">Category: <span class="font-mono text-gray-500 dark:text-gray-400">{{ ucfirst($product->category ?? 'Jewelry') }}</span></p>
                                        </div>
                                        <div class="flex items-center justify-center sm:justify-start gap-4 mt-4 sm:mt-0">
                                            <span class="text-lg font-playfair font-bold text-amber-600 dark:text-amber-300">₱{{ number_format($price, 2) }}</span>
                                        </div>
                                    </div>

                                    {{-- Actions --}}
                                    <div class="flex flex-col items-center sm:items-end justify-between w-full sm:w-auto">
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="text-xs px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors border border-red-200 dark:border-red-500/30">Remove</button>
                                            </form>
                                            <form action="{{ route('cart.add') }}" method="POST" class="ml-2">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $product->id }}" />
                                                <input type="hidden" name="quantity" value="1" />
                                                <button type="submit" class="text-xs px-4 py-2 bg-amber-300 hover:bg-amber-400 text-black font-bold rounded-lg transition-all">Add to Cart</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Summary / Saved Info --}}
                <div class="lg:w-1/3">
                    <div class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-6">
                        <h2 class="text-lg font-semibold text-gray-700 dark:text-gray-200 mb-4">Wishlist Summary</h2>
                        <div class="space-y-4 mb-6">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Items saved</span>
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">{{ $wishlistItems->count() }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Estimated Total</span>
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">₱{{ number_format($total, 2) }}</span>
                            </div>
                        </div>

                        <a href="{{ route('products.index') }}" class="block w-full py-3 px-6 text-center text-amber-600 dark:text-amber-400 font-semibold border border-amber-200 dark:border-amber-500/30 rounded-xl hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-colors">Continue Shopping</a>
                    </div>
                </div>

            </div>
        @else
            <div class="w-full flex items-center justify-center min-h-screen">
                <div class="max-w-md w-full">
                    <div class="text-center mb-12">
                        <h1 class="text-4xl sm:text-5xl font-playfair font-bold leading-tight mb-3">Your <span class="text-amber-300">Wishlist</span></h1>
                        <p class="text-gray-600 dark:text-gray-400">Save items you love and add them to cart later.</p>
                    </div>
                    <div class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 dark:bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-playfair font-bold text-gray-900 dark:text-white mb-3">No saved items yet</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-8">Explore our luxury collection and add favorites to your wishlist.</p>
                        <a href="{{ route('products.index') }}" class="inline-block px-8 py-4 bg-amber-300 hover:bg-amber-400 text-black font-bold rounded-xl transition-all shadow-lg shadow-amber-300/30 hover:shadow-amber-300/40 transform hover:-translate-y-1">Explore Collection</a>
                    </div>
                </div>
            </div>
        @endif

    </div>

    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const footer = document.querySelector('footer');
            let scrollTimeout;
            window.addEventListener('scroll', function() {
                footer.classList.add('visible');
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(function() {
                    if(window.scrollY < 50) {
                        footer.classList.remove('visible');
                    }
                }, 200);
            });
        });
    </script>

</body>
</html>

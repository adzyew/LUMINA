<!doctype html>
<html>
<head>
    <title>Your Cart | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        
        footer {
            transition: opacity 0.4s ease, transform 0.4s ease;
            opacity: 0;
            transform: translateY(20px);
            pointer-events: none;
        }
        
        footer.visible {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white font-sans antialiased flex flex-col min-h-screen transition-colors">

    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-gray-900/20 dark:bg-black/40 backdrop-blur-sm"></div>
    </div>

    @include('partials.navbar')

    <div class="grow container mx-auto px-4 sm:px-6 pb-20 flex flex-col items-center justify-center">

        @if(session('cart') && count(session('cart')) > 0)
            <div class="flex flex-col lg:flex-row gap-8 max-w-6xl w-full">
                
                {{-- Cart Items --}}
                <div class="lg:w-2/3">
                    <h2 class="text-2xl font-playfair font-bold text-gray-900 dark:text-white mb-6 text-center">Your Items</h2>
                    <div class="space-y-4">
                        @php $total = 0; @endphp
                        @foreach(session('cart') as $id => $details)
                            @php $itemTotal = $details['price'] * $details['quantity']; $total += $itemTotal; @endphp
                            <div class="group bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-5 sm:p-6 hover:border-amber-300/40 dark:hover:border-amber-300/40 transition-all duration-300 hover:shadow-lg dark:hover:shadow-amber-500/5">
                                <div class="flex flex-col sm:flex-row gap-5 items-center">
                                    {{-- Product Image --}}
                                    <div class="w-full sm:w-28 h-28 flex-shrink-0">
                                        <img src="{{ asset($details['image']) }}" alt="{{ $details['name'] }}" class="w-full h-full object-cover rounded-xl border border-gray-200 dark:border-white/10">
                                    </div>

                                    {{-- Product Info --}}
                                    <div class="flex-1 flex flex-col justify-between text-center sm:text-left">
                                        <div>
                                            <h3 class="text-lg sm:text-xl font-playfair font-bold text-gray-900 dark:text-white mb-2">{{ $details['name'] }}</h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">SKU: <span class="font-mono text-gray-500 dark:text-gray-500">#{{ str_pad($id, 5, '0', STR_PAD_LEFT) }}</span></p>
                                        </div>
                                        <div class="flex items-center justify-center sm:justify-start gap-4 mt-4 sm:mt-0">
                                            <span class="text-lg font-playfair font-bold text-amber-600 dark:text-amber-300">₱{{ number_format($details['price'], 2) }}</span>
                                        </div>
                                    </div>

                                    {{-- Quantity & Actions --}}
                                    <div class="flex flex-col items-center sm:items-end justify-between w-full sm:w-auto">
                                        <div class="flex items-center gap-2 bg-gray-100 dark:bg-white/10 rounded-lg p-1 border border-gray-200 dark:border-white/20">
                                            <button class="px-3 py-1 text-gray-600 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-300 transition-colors" disabled>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                            </button>
                                            <span class="px-4 font-bold text-gray-900 dark:text-white min-w-12 text-center">{{ $details['quantity'] }}</span>
                                            <button class="px-3 py-1 text-gray-600 dark:text-gray-300 hover:text-amber-600 dark:hover:text-amber-300 transition-colors" disabled>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                            </button>
                                        </div>
                                        <div class="text-center mt-4">
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Subtotal</p>
                                            <p class="text-xl sm:text-2xl font-playfair font-bold text-gray-900 dark:text-white mb-3">₱{{ number_format($itemTotal, 2) }}</p>
                                            <form action="{{ route('cart.remove') }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="submit" class="text-xs px-4 py-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors border border-red-200 dark:border-red-500/30">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Order Summary --}}
                <div class="lg:w-1/3">
                    <div class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 r text-center">Order Summary</h2>
                        
                        {{-- Summary Details --}}
                        <div class="space-y-4 mb-8 pb-8 border-b border-gray-200 dark:border-white/10">
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal</span>
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Shipping</span>
                                <span class="text-lg font-semibold text-green-600 dark:text-green-400">FREE</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600 dark:text-gray-400">Tax</span>
                                <span class="text-lg font-semibold text-gray-900 dark:text-white">Calculated at checkout</span>
                            </div>
                        </div>

                        {{-- Total --}}
                        <div class="bg-gradient-to-r from-amber-50 dark:from-amber-500/10 to-amber-100/50 dark:to-amber-600/10 rounded-xl p-4 mb-8 border border-amber-200 dark:border-amber-500/20 text-center">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Total Amount</p>
                            <p class="text-3xl sm:text-4xl font-playfair font-bold text-amber-600 dark:text-amber-300">₱{{ number_format($total, 2) }}</p>
                        </div>

                        {{-- Checkout Button --}}
                        @auth
                        <a href="{{ route('checkout') }}" class="block w-full py-4 px-6 bg-amber-300 hover:bg-amber-400 text-black font-bold rounded-xl transition-all duration-300 text-center shadow-lg shadow-amber-300/30 hover:shadow-amber-300/40 transform hover:-translate-y-1">
                            Proceed to Checkout
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="block w-full py-4 px-6 bg-amber-300 hover:bg-amber-400 text-black font-bold rounded-xl transition-all duration-300 text-center shadow-lg shadow-amber-300/30 hover:shadow-amber-300/40 transform hover:-translate-y-1">
                            Login to Checkout
                        </a>
                        @endauth
                        
                        {{-- Continue Shopping --}}
                        <a href="{{ route('products.index') }}" class="block w-full py-3 mt-3 px-6 text-center text-amber-600 dark:text-amber-400 font-semibold border border-amber-200 dark:border-amber-500/30 rounded-xl hover:bg-amber-50 dark:hover:bg-amber-500/10 transition-colors">
                            Continue Shopping
                        </a>

                        {{-- Trust Badges --}}
                        <div class="mt-8 pt-6 border-t border-gray-200 dark:border-white/10 space-y-3 text-xs text-gray-600 dark:text-gray-400 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Secure checkout
                            </div>
                            <div class="flex items-center justify-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                Free shipping on orders
                            </div>
                            <div class="flex items-center justify
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                                30-day returns policy
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        @else
            <div class="w-full flex items-center justify-center min-h-screen">
                <div class="max-w-md w-full">
                    <div class="text-center mb-12">
                        <h1 class="text-4xl sm:text-5xl font-playfair font-bold leading-tight mb-3">
                            Your <span class="text-amber-300">Shopping Cart</span>
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400">Review and manage your luxury selections</p>
                    </div>
                    
                    <div class="bg-white dark:bg-white/5 border border-gray-200 dark:border-white/10 rounded-2xl p-12 text-center">
                        <div class="w-20 h-20 bg-gray-100 dark:bg-white/10 rounded-full flex items-center justify-center mx-auto mb-6">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        </div>
                        <h2 class="text-2xl font-playfair font-bold text-gray-900 dark:text-white mb-3">Your Cart is Empty</h2>
                        <p class="text-gray-600 dark:text-gray-400 mb-8">Explore our luxury collection and add your favorite pieces to your cart.</p>
                        <a href="{{ route('products.index') }}" class="inline-block px-8 py-4 bg-amber-300 hover:bg-amber-400 text-black font-bold rounded-xl transition-all shadow-lg shadow-amber-300/30 hover:shadow-amber-300/40 transform hover:-translate-y-1">
                            Explore Collection
                        </a>
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
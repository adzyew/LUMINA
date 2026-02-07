    <!doctype html>
    <html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        @vite('resources/css/app.css')
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
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
            /* Carousel Customization */
            .swiper-pagination-bullet-active { background-color: #fbbf24 !important; }
            .swiper-button-next, .swiper-button-prev { color: #fbbf24 !important; opacity: 0.7; }
            .swiper-button-next:after, .swiper-button-prev:after { font-size: 1.5rem; }
        </style>
    </head>
    <body class="text-white relative antialiased ">

        <div class="fixed inset-0 -z-50 overflow-hidden">
            <img src="{{ asset('IMAGES\BG.png') }}" alt="Luxury background" class="w-full h-full object-cover"/>

    <!-- Dark luxury overlay -->
    <div class="absolute inset-0 bg-linear-to-b from-amber-300/20 via-black/70 to-black/90"></div>
        </div>
        <!-- Navigation -->
        <nav class="fixed top-0 left-0 right-0 z-50 bg-opacity-90 backdrop-blur-md border-b border-amber-300">
            <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4">
                <div class="flex items-center justify-between">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="flex space-x-1">
                            <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full transform -rotate-12"></div>
                            <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full"></div>
                            <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full transform rotate-12"></div>
                        </div>
                        <span class="font-serif font-black text-2xl sm:text-3xl">Lumina</span>
                    </div>

                    <!-- Navigation Links - Desktop -->
                    <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                        <a href="#" class="text-white hover:text-amber-300 transition-colors duration-300 lg:text-lg font-playfair font-semibold">Home</a>
                        <a href="{{ route('products.index') }}" class="text-white hover:text-amber-300 transition-colors duration-300 lg:text-lg  font-playfair font-semibold">Collections</a>
                        <a href="#features" class="text-white hover:text-amber-300 transition-colors duration-300 lg:text-lg  font-playfair font-semibold">About</a>
                        <a href="#" class="text-white hover:text-amber-300 transition-colors duration-300  lg:text-lg font-playfair font-semibold">Contact</a>
                    </div>

                    <!-- Icons -->
                    
                    <div class="flex items-center space-x-4 sm:space-x-6">
                        <button class="hidden sm:block text-gray-300 hover:text-amber-300 transition-colors duration-300">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                        <button class="hidden sm:block text-gray-300 hover:text-amber-300 transition-colors duration-300">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </button>
                        <a href="{{ route('cart.index') }}" class="relative text-gray-300 hover:text-amber-300 transition-colors group">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                            </svg>

                            @if(session('cart') && count(session('cart')) > 0)
                                <span class="absolute -top-1 -right-1 sm:-top-2 sm:-right-2 bg-amber-300 text-black text-[10px] sm:text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center font-bold animate-pulse">
                                    {{ count(session('cart')) }}
                                </span>
                            @endif
                        </a>

                        <!---->
                        <div class="hidden md:flex items-center space-x-4">
                            @auth
                                @if(Auth::user()->is_admin)
                                    <a href="{{ route('admin.admin_dashboard') }}" class="text-amber-300 hover:text-white font-medium transition-colors">
                                        Admin Panel
                                    </a>
                                @else
                                    <a href="{{ route('dashboard') }}" class="text-amber-300 hover:text-white font-medium transition-colors">
                                        My Account
                                    </a>
                                @endif
                            @else
                                <a href="{{ route('login.form') }}" class="text-amber-300 hover:text-white font-bold transition-colors border px-5 py-2 rounded-sm">
                                    Log In
                                </a>
                                <a href="{{ route('register.form') }}" class="bg-linear-to-r from-amber-200 to-amber-500 text-black hover:text-white font-bold transition-color px-5 py-2 rounded-xl">
                                    Sign Up
                                </a>
                            @endauth
                        </div>
                        
                        <!-- Mobile Menu Button -->
                        
                
                <!-- Mobile Menu -->
                <div id="mobileMenu" class="hidden md:hidden mt-4 pb-4 border-t border-gray-800 pt-4">
                    <div class="flex flex-col space-y-4">
                        <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300">Home</a>
                        <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300">Collections</a>
                        <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300">About</a>
                        <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300">Contact</a>
                        <div class="flex items-center space-x-6 pt-4 border-t border-gray-800">
                            @auth
                            <a href="{{ url('/dashboard') }}" class="text-amber-300">My Account</a>
                        @else
                            <a href="{{ route('login.form') }}" class="text-gray-300 hover:text-amber-300">Login</a>
                            <a href="{{ route('register.form') }}" class="text-center w-full py-3 bg-amber-300 text-black font-bold rounded-lg">Sign Up</a>
                        @endauth
                            <button class="text-gray-300 hover:text-amber-300 transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </button>
                            <button class="text-gray-300 hover:text-amber-300 transition-colors duration-300">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative min-h-screen pt-20 overflow-hidden">
            <div class="container mx-auto px-4 sm:px-6 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative">
        <!-- LEFT — LUXURY PRODUCT CAROUSEL -->
                    <div class="order-1 relative w-full flex items-center justify-center px-4 sm:px-6 lg:px-0">
    
    <div class="swiper heroSwiper w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl">
        <div class="swiper-wrapper">
            <div class="swiper-slide flex items-center justify-center">
                <div class="relative group w-full flex items-center justify-center py-6">
                    <div class="absolute inset-0 bg-amber-300/20 blur-3xl opacity-40 group-hover:opacity-80 transition duration-700"></div>
                    <img src="{{ asset('IMAGES/Bracelet.jpg') }}" class="relative z-10 w-full max-h-[280px] sm:max-h-[320px] md:max-h-[360px] lg:max-h-[420px] object-contain drop-shadow-2xl hover:scale-105 transition duration-700 rounded-xl">
                </div>
            </div>

            <div class="swiper-slide flex items-center justify-center">
                <div class="relative group w-full flex items-center justify-center py-6">
                    <div class="absolute inset-0 bg-amber-300/20 blur-3xl opacity-40 group-hover:opacity-80 transition duration-700"></div>
                    <img src="{{ asset('IMAGES/Necklace.jpg') }}" class="relative z-10 w-full max-h-[280px] sm:max-h-[320px] md:max-h-[360px] lg:max-h-[420px] object-contain drop-shadow-2xl hover:scale-105 transition duration-700 rounded-xl">
                </div>
            </div>

            <div class="swiper-slide flex items-center justify-center">
                <div class="relative group w-full flex items-center justify-center py-6">
                    <div class="absolute inset-0 bg-amber-300/20 blur-3xl opacity-40 group-hover:opacity-80 transition duration-700"></div>
                    <img src="{{ asset('IMAGES/Earrings.jpg') }}" class="relative z-10 w-full max-h-[280px] sm:max-h-[320px] md:max-h-[360px] lg:max-h-[420px] object-contain drop-shadow-2xl hover:scale-105 transition duration-700 rounded-xl">
                </div>
            </div>

            <div class="swiper-slide flex items-center justify-center">
                <div class="relative group w-full flex items-center justify-center py-6">
                    <div class="absolute inset-0 bg-amber-300/20 blur-3xl opacity-40 group-hover:opacity-80 transition duration-700"></div>
                    <img src="{{ asset('IMAGES/group-jewelry.jpg') }}" onerror="this.src='{{ asset('IMAGES/ring_1.jpeg') }}'" class="relative z-10 w-full max-h-[280px] sm:max-h-[320px] md:max-h-[360px] lg:max-h-[420px] object-contain drop-shadow-2xl hover:scale-105 transition duration-700 rounded-xl">
                </div>
            </div>

        </div>

        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-pagination"></div>

    </div>
</div>

        <!-- RIGHT — TEXT CONTENT -->
        <div class="space-y-8 order-2 text-center lg:text-left">
            <!-- Heading -->
                        <div class=" order-2 text-center items-center justify-center"></div>
                        <div>
                            <h1 class="text-5xl md:text-6xl lg:text-7xl font-playfair font-bold leading-tight text-white">
                                Your dream<br>
                                <span class="text-transparent bg-clip-text bg-linear-to-r from-amber-200 to-amber-500">Jewelry</span>
                            </h1>
                            <p class="mt-6 text-gray-300 text-lg max-w-md mx-auto lg:mx-0">
                                Discover the finest collection of handcrafted jewelry that tells your unique story with elegance and sophistication.
                            </p>
                        </div>

            <!-- Buttons -->
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('products.index') }}"
                            class="px-10 py-4 bg-linear-to-r from-amber-200 to-amber-500 text-black font-bold rounded-full hover:scale-105 transition duration-300 shadow-lg shadow-amber-300/20">
                                Explore Now
                            </a>

                            <a href="{{ route('products.index', ['category' => 'necklaces']) }}"
                            class="px-10 py-4 border border-amber-300/40 text-white rounded-full hover:bg-amber-300/10 transition">
                                View Collections
                            </a>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Featured Products Section -->
                <div class="mt-16 sm:mt-20 lg:mt-24 space-y-6 sm:space-y-8">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <h2 class="text-2xl sm:text-3xl font-playfair font-bold">Featured Collections</h2>
                        <a href="{{ route('products.index') }}" class="text-amber-300 hover:underline flex items-center gap-2 text-sm sm:text-base">
                            View All
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <!-- Product Cards Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                        @foreach($featuredProducts as $product)
                            <div class="bg-gray-900 rounded-2xl overflow-hidden hover-lift cursor-pointer group border border-white/5">
                                <div class="relative h-56 bg-linear-to-br from-gray-800 to-gray-900 flex items-center justify-center p-6">
                                    <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition"></div>

                                    <img
                                        src="{{ $product->image_url }}"
                                        class="w-full h-full object-cover rounded-xl"
                                    />
                                </div>

                                <div class="p-6">
                                    <h3 class="text-transparent bg-clip-text bg-linear-to-r from-amber-200 to-amber-500 text-xl font-playfair font-semibold mb-2">
                                        {{ $product->name }}
                                    </h3>

                                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                                        {{ $product->description }}
                                    </p>

                                <div class="flex items-center justify-between">
                                    <span class="text-2xl font-bold text-gold">
                                        ${{ number_format($product->price, 0) }}
                                    </span>

                                        <a href="{{ route('cart.add', $product->id) }}"
                                        class="bg-amber-300 text-black px-4 py-2 rounded-lg text-sm font-semibold hover:bg-opacity-90">
                                            Add to Cart
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Background Decorations -->
            <div class="absolute top-0 left-0 w-64 h-64 sm:w-96 sm:h-96 bg-amber-300 opacity-5 rounded-full blur-3xl -z-10"></div>
            <div class="absolute bottom-0 right-0 w-64 h-64 sm:w-96 sm:h-96 bg-amber-300 opacity-5 rounded-full blur-3xl -z-10"></div>
        </section>

        

        <!-- Features Section -->
        <section id="features" class="py-12 sm:py-16 lg:py-20 border-t border-gray-800">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sm:gap-10 lg:gap-12">
                    <div class="text-center space-y-3 sm:space-y-4">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto bg-amber-300 bg-opacity-10 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-playfair font-semibold">Premium Quality</h3>
                        <p class="text-gray-400 text-sm sm:text-base">Handcrafted with the finest materials and gemstones</p>
                    </div>
                    <div class="text-center space-y-3 sm:space-y-4">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto bg-amber-300 bg-opacity-10 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-playfair font-semibold">Lifetime Warranty</h3>
                        <p class="text-gray-400 text-sm sm:text-base">Every piece comes with our lifetime craftsmanship guarantee</p>
                    </div>
                    <div class="text-center space-y-3 sm:space-y-4">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto bg-amber-300 bg-opacity-10 rounded-full flex items-center justify-center">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 " fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg sm:text-xl font-playfair font-semibold">Free Shipping</h3>
                        <p class="text-gray-400 text-sm sm:text-base">Complimentary worldwide shipping on all orders</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer id="contact" class="bg-gray-900 border-t border-gray-800 py-8 sm:py-12">
            <div class="container mx-auto px-4 sm:px-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div>
                        <div class="flex items-center space-x-2 mb-4">
                            <div class="flex space-x-1">
                                <div class="w-2 h-6 bg-amber-300 rounded-full transform -rotate-12"></div>
                                <div class="w-2 h-6 bg-amber-300 rounded-full"></div>
                                <div class="w-2 h-6 bg-amber-300 rounded-full transform rotate-12"></div>
                            </div>
                            <span class="text-lg sm:text-xl font-playfair font-semibold text-gold">Lumina</span>
                        </div>
                        <p class="text-gray-400 text-sm">Crafting dreams into reality, one jewel at a time.</p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-3 sm:mb-4 text-base sm:text-lg">Quick Links</h4>
                        <ul class="space-y-2 text-gray-400 text-sm item">
                            <li><a href="#" class="hover:text-amber-300 transition-colors">About Us</a></li>
                            <li><a href="#" class="hover:text-amber-300 transition-colors">Collections</a></li>
                            <li><a href="#" class="hover:text-amber-300 transition-colors">Custom Design</a></li>
                            <li><a href="#" class="hover:text-amber-300 transition-colors">Contact</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-3 sm:mb-4 text-base sm:text-lg">Customer Care</h4>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-amber-300 transition-colors">Shipping Info</a></li>
                            <li><a href="#" class="hover:text-amber-300 transition-colors">Returns</a></li>
                            <li><a href="#" class="hover:text-amber-300 transition-colors">Warranty</a></li>
                            <li><a href="#" class="hover:text-amber-300 transition-colors">FAQ</a></li>
                        </ul>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-3 sm:mb-4 text-base sm:text-lg">Newsletter</h4>
                        <p class="text-gray-400 text-sm mb-4">Subscribe for exclusive offers</p>
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input type="email" placeholder="Your email" class="flex-1 px-3 sm:px-4 py-2 bg-gray-800 border border-gray-700 rounded-lg sm:rounded-l-lg sm:rounded-r-none focus:outline-none focus:border-amber-300 text-sm">
                            <button class="px-4 sm:px-6 py-2 bg-amber-300 text-black font-semibold rounded-lg sm:rounded-l-none sm:rounded-r-lg hover:bg-opacity-90 transition-all text-sm whitespace-nowrap">
                                Subscribe
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-8 sm:mt-12 pt-6 sm:pt-8 border-t border-gray-800 text-center text-gray-400 text-xs sm:text-sm">
                    <p>&copy; 2026 Lumina Accessories. All rights reserved.</p>
                </div>
            </div>
        </footer>
        <footer id="contact" class="bg-[#faf7ef] border-t border-gray-200 py-10">
    <div class="container mx-auto px-4 sm:px-6 text-center">

    <footer id="contact" class="bg-amber-200 border-t border-gray-200 py-8 sm:py-10">
    <div class="container mx-auto px-4 sm:px-6 text-center">
        <div>
            <div class="flex items-center space-x-2 mb-4">
                <div class="flex space-x-1">
                    <div class="w-2 h-6 bg-amber-300 rounded-full transform -rotate-12"></div>
                    <div class="w-2 h-6 bg-amber-300 rounded-full"></div>
                    <div class="w-2 h-6 bg-amber-300 rounded-full transform rotate-12"></div>
                </div>
                    <span class="text-lg sm:text-xl font-playfair font-semibold text-gold">Lumina</span>
            </div>
                <p class="text-gray-400 text-sm">Crafting dreams into reality, one jewel at a time.</p>
        </div>

        <!-- Title -->
        <h4 class="text-base sm:text-lg font-medium text-gray-800 mb-4 sm:mb-5">
        Quick links
        </h4>

        <!-- Links -->
        <ul
        class="flex flex-col sm:flex-row flex-wrap items-center justify-center gap-y-2 gap-x-4 sm:gap-x-6 text-xs sm:text-sm text-gray-600">
        <li><a href="#" class="hover:text-gray-900 transition-colors">About Us</a></li>
        <li><a href="#" class="hover:text-gray-900 transition-colors">Collections</a></li>
        <li><a href="#" class="hover:text-gray-900 transition-colors">Custom Design</a></li>
        <li><a href="#" class="hover:text-gray-900 transition-colors">Contact</a></li>
        </ul>

        <h4 class="text-base sm:text-lg font-medium text-gray-800 mb-4 sm:mb-5">
        Customer Care
        </h4>

        <!-- Links -->
        <ul
        class="flex flex-col sm:flex-row flex-wrap items-center justify-center gap-y-2 gap-x-4 sm:gap-x-6 text-xs sm:text-sm text-gray-600">
        <li><a href="#" class="hover:text-gray-900 transition-colors">Shipping Info</a></li>
        <li><a href="#" class="hover:text-gray-900 transition-colors">Returns</a></li>
        <li><a href="#" class="hover:text-gray-900 transition-colors">Warranty</a></li>
        <li><a href="#" class="hover:text-gray-900 transition-colors">FAQ</a></li>
        </ul>

    </div>
    </footer>

                <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
                <script>
                    function toggleMobileMenu() {
                        const menu = document.getElementById('mobileMenu');
                        menu.classList.toggle('hidden');
                    }

                    // Initialize Carousel
                    document.addEventListener('DOMContentLoaded', function () {
                    
                        var swiper = new Swiper(".heroSwiper", {
                        loop: true,
                        effect: "fade", 
                        fadeEffect: { crossFade: true },
                        speed: 1000,
                        autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                        },
                    pagination: {
                        el: ".swiper-pagination",
                        clickable: true,
                    },
                    navigation: {
                        nextEl: ".swiper-button-next",
                        prevEl: ".swiper-button-prev",
                    },
                });
            });
                </script>

    </body>
    </html>
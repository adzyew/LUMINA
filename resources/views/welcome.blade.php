<!doctype html>
<html>
  <head>
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
                    <button class="text-gray-300 hover:text-amber-300 transition-colors duration-300 relative">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 sm:-top-2 sm:-right-2 bg-amber-300 text-black text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center font-semibold">3</span>
                    </button>

                    <!---->
                    <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-amber-300">My Account</a>
                        @else
                            <button onclick="openAuthModal('login')" class="text-white hover:text-amber-300 transition-colors">
                                Login
                            </button>
                            <button onclick="openAuthModal('register')" class="px-4 py-2 bg-amber-300 text-black rounded-lg hover:bg-opacity-90 transition-colors">
                                Sign Up
                            </button>
                        @endauth
                    </div>
                    
                    <!-- Mobile Menu Button -->
                    <button class="md:hidden text-gray-300 hover:text-amber-300 transition-colors duration-300" onclick="toggleMobileMenu()">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
            
            <!-- Mobile Menu -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 pb-4 border-t border-gray-800 pt-4">
                <div class="flex flex-col space-y-4">
                    <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300">Home</a>
                    <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300">Collections</a>
                    <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300">About</a>
                    <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300">Contact</a>
                    <div class="flex items-center space-x-6 pt-4 border-t border-gray-800">
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
        <div class="container mx-auto px-4 sm:px-6 py-8 ">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center relative">
    <!-- LEFT — LUXURY PRODUCT CAROUSEL -->
                <div class="order-1 relative">
                    <div class="swiper heroSwiper">
                        <div class="swiper-wrapper">
                            @foreach($featuredProducts as $product)
                            <div class="swiper-slide flex justify-center">
                                <div class="relative group">

                                    <!-- Glow Background -->
                                    <div class="absolute inset-0 bg-linear-to-tr from-amber-300/20 via-transparent to-transparent blur-2xl opacity-60 group-hover:opacity-100 transition"></div>

                                    <!-- Product Image -->
                                    <img src="{{ asset('storage/' . $product->image) }}" class="relative z-10 w-[320px] lg:w-105 object-contain drop-shadow-[0_20px_40px_rgba(0,0,0,0.6)] transition duration-700 group-hover:scale-105">
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <!-- Arrows -->
                        <div class="swiper-button-next text-amber-300!"></div>
                        <div class="swiper-button-prev text-amber-300!"></div>

                        <!-- Dots -->
                        <div class="swiper-pagination"></div>

                    </div>

                    <!-- Luxury Floating Glow -->
                    <div class="absolute -top-10 -right-10 w-40 h-40 bg-amber-300/10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-10 -left-10 w-52 h-52 bg-amber-300/10 rounded-full blur-3xl"></div>

                </div>


    <!-- RIGHT — TEXT CONTENT -->
                    <div class="space-y-8 order-2">

        <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <div class="flex space-x-1">
                            <div class="w-2 h-6 bg-amber-300 rounded-full -rotate-12"></div>
                            <div class="w-2 h-6 bg-amber-300 rounded-full"></div>
                            <div class="w-2 h-6 bg-amber-300 rounded-full rotate-12"></div>
                        </div>
                        <span class="text-xl font-playfair font-semibold text-gold">Lumina</span>
                    </div>

        <!-- Heading -->
                    <div>
                        <h1 class="text-5xl md:text-6xl lg:text-7xl font-playfair font-bold leading-tight">
                            Your dream<br>
                            <span class="text-gold">Jewelry</span>
                        </h1>

                        <p class="mt-6 text-white text-lg max-w-md">
                            Discover the finest collection of handcrafted jewelry that tells your unique story with elegance and sophistication.
                        </p>
                    </div>

        <!-- Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('products.index') }}"
                        class="px-10 py-4 bg-amber-300 text-black font-bold rounded-full hover:scale-105 transition duration-300 shadow-lg shadow-amber-300/20">
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
                    
                    <!-- Product Card 1 -->
                    <div class="bg-gray-900 rounded-2xl overflow-hidden hover-lift cursor-pointer group">
                        <div class="relative h-48 sm:h-56 md:h-64 bg-linear-to-br from-gray-800 to-gray-900 flex items-center justify-center p-6 sm:p-8">
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                            <!-- Product Image -->
                            <img class="w-full h-full object-cover" src="{{ asset('IMAGES/ring_1.jpeg') }}" alt="Classic Gold Ring">
                        </div>
                        <div class="p-4 sm:p-6">
                            <h3 class="text-lg sm:text-xl font-playfair font-semibold mb-2">Classic Gold Ring</h3>
                            <p class="text-gray-400 text-xs sm:text-sm mb-3 sm:mb-4">Timeless elegance in 18K gold</p>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xl sm:text-2xl font-bold text-gold">$2,499</span>
                                <button class="bg-amber-300 text-black px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold hover:bg-opacity-90 transition-all">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="bg-gray-900 rounded-2xl overflow-hidden hover-lift cursor-pointer group">
                        <div class="relative h-48 sm:h-56 md:h-64 bg-linear-to-br from-gray-800 to-gray-900 flex items-center justify-center p-6 sm:p-8">
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                            <!-- Product Image -->
                            <svg class="w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 text-gold" fill="currentColor" viewBox="0 0 100 100">
                                <line x1="50" y1="10" x2="50" y2="40" stroke="currentColor" stroke-width="2" fill="none"/>
                                <path d="M35 40 L50 70 L65 40 Z" fill="currentColor"/>
                                <circle cx="50" cy="50" r="8" fill="#DC2626"/>
                            </svg>
                        </div>
                        <div class="p-4 sm:p-6">
                            <h3 class="text-lg sm:text-xl font-playfair font-semibold mb-2">Luxury Watch</h3>
                            <p class="text-gray-400 text-xs sm:text-sm mb-3 sm:mb-4">Swiss craftsmanship excellence</p>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xl sm:text-2xl font-bold text-gold">$3,899</span>
                                <button class="bg-amber-300 text-black px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold hover:bg-opacity-90 transition-all">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="bg-gray-900 rounded-2xl overflow-hidden hover-lift cursor-pointer group">
                        <div class="relative h-48 sm:h-56 md:h-64 bg-linear-to-br from-gray-800 to-gray-900 flex items-center justify-center p-6 sm:p-8">
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                            <!-- Diamond Ring Icon -->
                            <svg class="w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 text-gold" fill="currentColor" viewBox="0 0 100 100">
                                <circle cx="50" cy="55" r="25" fill="none" stroke="currentColor" stroke-width="3"/>
                                <path d="M35 30 L50 15 L65 30 L50 45 Z" fill="currentColor"/>
                                <circle cx="50" cy="20" r="3" fill="white"/>
                            </svg>
                        </div>
                        <div class="p-4 sm:p-6">
                            <h3 class="text-lg sm:text-xl font-playfair font-semibold mb-2">Diamond Ring</h3>
                            <p class="text-gray-400 text-xs sm:text-sm mb-3 sm:mb-4">Brilliant cut perfection</p>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xl sm:text-2xl font-bold text-gold">$8,999</span>
                                <button class="bg-amber-300 text-black px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold hover:bg-opacity-90 transition-all">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="bg-gray-900 rounded-2xl overflow-hidden hover-lift cursor-pointer group">
                        <div class="relative h-48 sm:h-56 md:h-64 bg-linear-to-br from-gray-800 to-gray-900 flex items-center justify-center p-6 sm:p-8">
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-500"></div>
                            <!-- Emerald Ring Icon -->
                            <svg class="w-24 h-24 sm:w-28 sm:h-28 md:w-32 md:h-32 text-gold" fill="currentColor" viewBox="0 0 100 100">
                                <circle cx="50" cy="55" r="25" fill="none" stroke="currentColor" stroke-width="3"/>
                                <rect x="40" y="15" width="20" height="20" fill="#059669" rx="2"/>
                                <line x1="40" y1="35" x2="35" y2="45" stroke="currentColor" stroke-width="2"/>
                                <line x1="60" y1="35" x2="65" y2="45" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="p-4 sm:p-6">
                            <h3 class="text-lg sm:text-xl font-playfair font-semibold mb-2">Emerald Luxury Ring</h3>
                            <p class="text-gray-400 text-xs sm:text-sm mb-3 sm:mb-4">Natural emerald statement piece</p>
                            <div class="flex items-center justify-between gap-2">
                                <span class="text-xl sm:text-2xl font-bold text-gold">$6,799</span>
                                <button class="bg-amber-300 text-black px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold hover:bg-opacity-90 transition-all">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

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



                <div id="authModal"
                    class="fixed inset-0 z-50 hidden overflow-y-auto transition-opacity duration-300">


                <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/75" onclick="closeAuthModal()"></div>
                <!-- Modal wrapper -->
                <div class="flex min-h-full items-center justify-center p-4">
                    <div class="relative w-full max-w-md sm:max-w-lg lg:max-w-xl rounded-xl bg-gray-900 border border-gray-700 shadow-xl">

                        <!-- Close button -->
                        <button onclick="closeAuthModal()" class="absolute top-4 right-4 text-gray-400 hover:text-white">
                            ✕
                        </button>
                        
                        <!-- Tabs -->
                    <div class="flex border-b border-gray-700">
                        <button id="tabLogin" onclick="switchTab('login')" class="w-1/2 py-3 sm:py-4 text-sm sm:text-base font-semibold text-amber-300 border-b-2 border-amber-300">
                            Login
                        </button>
                        <button id="tabRegister" onclick="switchTab('register')" class="w-1/2 py-3 sm:py-4 text-sm sm:text-base font-semibold text-gray-400 hover:text-white">
                            Register
                        </button>
                    </div>
                    
        <!-- Content -->
                <div class="p-6 sm:p-8 lg:p-10">

        <!-- LOGIN -->
                    <form id="loginForm" action="{{ route('login') }}" method="POST" class="space-y-4 sm:space-y-5 lg:space-y-6">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-400">Email</label>
                        <input type="email" name="email" required class="mt-1 w-full h-11 sm:h-12 lg:h-14 px-4 text-sm sm:text-base rounded-md bg-gray-800 border-gray-700 text-white focus:border-amber-300 focus:ring-amber-300">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-400">Password</label>
                        <input type="password" name="password" required class="mt-1 w-full h-11 sm:h-12 lg:h-14 px-4 text-sm sm:text-base rounded-md bg-gray-800 border-gray-700 text-white focus:border-amber-300 focus:ring-amber-300">
                    </div>

                    <button class="w-full h-11 sm:h-12 lg:h-14 bg-amber-300 text-black font-bold rounded-lg hover:bg-opacity-90 transition">
                        Login
                    </button>
                    </form>

                    <!-- REGISTER -->
                    <form id="registerForm" action="{{ route('register') }}" method="POST" class="space-y-4 sm:space-y-5 lg:space-y-6 hidden">
                    @csrf
                    <div>
                        <label class="block text-sm text-gray-400">Full Name</label>
                        <input type="text" name="name" required class="mt-1 w-full h-11 sm:h-12 lg:h-14 px-4 text-sm sm:text-base rounded-md bg-gray-800 border-gray-700 text-white focus:border-amber-300 focus:ring-amber-300">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400">Phone</label>
                        <input type="text" name="phone" required class="mt-1 w-full h-11 sm:h-12 lg:h-14 px-4 text-sm sm:text-base rounded-md bg-gray-800 border-gray-700 text-white focus:border-amber-300 focus:ring-amber-300">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400">Email</label>
                        <input type="email" name="email" required class="mt-1 w-full h-11 sm:h-12 lg:h-14 px-4 text-sm sm:text-base rounded-md bg-gray-800 border-gray-700 text-white focus:border-amber-300 focus:ring-amber-300">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400">Password</label>
                        <input type="password" name="password" required class="mt-1 w-full h-11 sm:h-12 lg:h-14 px-4 text-sm sm:text-base rounded-md bg-gray-800 border-gray-700 text-white focus:border-amber-300 focus:ring-amber-300">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-400">Confirm Password</label>
                        <input type="password" name="password_confirmation" required
                        class="mt-1 w-full h-11 sm:h-12 lg:h-14 px-4 text-sm sm:text-base rounded-md bg-gray-800 border-gray-700 text-white focus:border-amber-300 focus:ring-amber-300">
                    </div>

                    <button class="w-full h-11 sm:h-12 lg:h-14 bg-amber-300 text-black font-bold rounded-lg hover:bg-opacity-90 transition">
                        Create Account
                    </button>
                    </form>
                </div>
                </div>
            </div>
            </div>  

            <script>
                function openAuthModal(mode = 'login') {
                const modal = document.getElementById('authModal');
                const card = document.getElementById('authCard');

                modal.classList.remove('hidden');

                setTimeout(() => {
                    card.classList.remove('scale-95', 'opacity-0');
                    card.classList.add('scale-100', 'opacity-100');
                }, 10);

                switchTab(mode);
            }

            function closeAuthModal() {
                const modal = document.getElementById('authModal');
                const card = document.getElementById('authCard');

                card.classList.add('scale-95', 'opacity-0');

                setTimeout(() => {
                    modal.classList.add('hidden');
                }, 300);
            }


                function switchTab(tab) {
                    const loginForm = document.getElementById('loginForm');
                    const registerForm = document.getElementById('registerForm');
                    const tabLogin = document.getElementById('tabLogin');
                    const tabRegister = document.getElementById('tabRegister');

                    if (tab === 'login') {
                        loginForm.classList.remove('hidden');
                        registerForm.classList.add('hidden');

                        tabLogin.classList.add('text-amber-300', 'border-b-2', 'border-amber-300');
                        tabLogin.classList.remove('text-gray-400');

                        tabRegister.classList.remove('text-amber-300', 'border-b-2', 'border-amber-300');
                        tabRegister.classList.add('text-gray-400');
                    } else {
                        loginForm.classList.add('hidden');
                        registerForm.classList.remove('hidden');

                        tabRegister.classList.add('text-amber-300', 'border-b-2', 'border-amber-300');
                        tabRegister.classList.remove('text-gray-400');

                        tabLogin.classList.remove('text-amber-300', 'border-b-2', 'border-amber-300');
                        tabLogin.classList.add('text-gray-400');
                    }

                    @if($errors->any())
                        document.addEventListener('DOMContentLoaded', function(){
                            openAuthModal('login');
                        });
                    @endif
                }

                function toggleMobileMenu() {
                    const menu = document.getElementById('mobileMenu');
                    menu.classList.toggle('hidden');
                }

                document.addEventListener('DOMContentLoaded', function () {

                new Swiper('.heroSwiper', {
                    modules: [
                        SwiperModules.Navigation,
                        SwiperModules.Pagination,
                        SwiperModules.Autoplay
                    ],

                loop: true,

                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },

                speed: 1200,

                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },

                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                },

                grabCursor: true,
                centeredSlides: true,
                slidesPerView: 1,
                });

            });
            </script>

</body>
</html>
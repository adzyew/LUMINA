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
        .snake-pattern {
            background-image: 
                repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(251, 191, 36, 0.05) 10px, rgba(251, 191, 36, 0.05) 20px),
                repeating-linear-gradient(-45deg, transparent, transparent 10px, rgba(251, 191, 36, 0.05) 10px, rgba(251, 191, 36, 0.05) 20px);
        }
    </style>
  </head>
  <body class="bg-white text-white">
    
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black bg-opacity-90 backdrop-blur-md border-b border-gray-800">
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
                    <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300 text-sm lg:text-base">Home</a>
                    <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300 text-sm lg:text-base">Collections</a>
                    <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300 text-sm lg:text-base">About</a>
                    <a href="#" class="text-gray-300 hover:text-amber-300 transition-colors duration-300 text-sm lg:text-base">Contact</a>
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
        <div class="container mx-auto px-4 sm:px-6 py-8 sm:py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">
                
                <!-- Left Content -->
                <div class="space-y-6 sm:space-y-8 z-10 order-2 lg:order-1">
                    <!-- Logo Repeat -->
                    <div class="flex items-center space-x-2">
                        <div class="flex space-x-1">
                            <div class="w-2 h-6 bg-amber-300 rounded-full transform -rotate-12"></div>
                            <div class="w-2 h-6 bg-amber-300 rounded-full"></div>
                            <div class="w-2 h-6 bg-amber-300 rounded-full transform rotate-12"></div>
                        </div>
                        <span class="text-xl font-playfair font-semibold text-gold">Lumina</span>
                    </div>

                    <!-- Main Heading -->
                    <div>
                        <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-playfair font-bold leading-tight">
                            Your dream<br>
                            <span class="text-gold">Jewellery</span>
                        </h1>
                        <p class="mt-4 sm:mt-6 text-gray-400 text-base sm:text-lg max-w-md">
                            Discover the finest collection of handcrafted jewelry that tells your unique story with elegance and sophistication.
                        </p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row flex-wrap gap-3 sm:gap-4">
                        <button class="w-full sm:w-auto px-6 sm:px-8 py-3 bg-amber-300 text-black font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105">
                            Explore Now
                        </button>
                        <button class="w-full sm:w-auto px-6 sm:px-8 py-3 border-2 border-amber-300 text-amber-300 font-semibold rounded-lg hover:bg-amber-300 hover:text-black transition-all duration-300">
                            Collections
                        </button>
                    </div>
                </div>

                <!-- Right Content - Snake Image -->
                <div class="relative order-1 lg:order-2">

  <!-- Glow background -->
  <div class="absolute inset-0 bg-linear-to-t from-amber-300/30 via-transparent to-transparent"></div>

  <!-- Image wrapper -->
  <div class="relative flex justify-center items-center py-12 lg:py-0">
    <img
      src="IMAGES/IMG_0843-removebg-preview.png"
      alt="Luxury Jewelry"
      class="w-64 sm:w-80 md:w-96 lg:w-112.5 xl:w-150 rotate-12 drop-shadow-2xl transition-transform duration-300 hover:scale-105"
    />
  </div>

  <!-- Floating glow elements -->
  <div class="absolute -top-8 -right-8 w-32 h-32 bg-amber-300/10 rounded-full blur-3xl"></div>
  <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-amber-300/10 rounded-full blur-3xl"></div>

</div>
            </div>

            <!-- Featured Products Section -->
            <div class="mt-16 sm:mt-20 lg:mt-24 space-y-6 sm:space-y-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <h2 class="text-2xl sm:text-3xl font-playfair font-bold">Featured Collections</h2>
                    <a href="#" class="text-amber-300 hover:underline flex items-center gap-2 text-sm sm:text-base">
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
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
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
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
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
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
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
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
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
    <section class="py-12 sm:py-16 lg:py-20 border-t border-gray-800">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 sm:gap-10 lg:gap-12">
                <div class="text-center space-y-3 sm:space-y-4">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto bg-amber-300 bg-opacity-10 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg sm:text-xl font-playfair font-semibold">Premium Quality</h3>
                    <p class="text-gray-400 text-sm sm:text-base">Handcrafted with the finest materials and gemstones</p>
                </div>
                <div class="text-center space-y-3 sm:space-y-4">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 mx-auto bg-amber-300 bg-opacity-10 rounded-full flex items-center justify-center">
                        <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
    <footer class="bg-gray-900 border-t border-gray-800 py-8 sm:py-12">
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
                    <ul class="space-y-2 text-gray-400 text-sm">
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

    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
        }
    </script>

</body>
</html>
<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
  </head>
  <body class="bg-black text-white">
    
    <nav class="fixed top-0 left-0 right-0 z-50 bg-black bg-opacity-90 backdrop-blur-md border-b border-gray-800">
        <div class="container mx-auto px-6 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="flex space-x-1">
                        <div class="w-3 h-8 bg-amber-300 rounded-full transform -rotate-12"></div>
                        <div class="w-3 h-8 bg-amber-300 rounded-full"></div>
                        <div class="w-3 h-8 bg-amber-300 rounded-full transform rotate-12"></div>
                    </div>
                    <span class=" font-serif font-black text-3xl ">Lumina</span>
                </div>

                <!-- navi -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#" class="text-gray-300 hover:text-gold transition-colors duration-300">Home</a>
                    <a href="#" class="text-gray-300 hover:text-gold transition-colors duration-300">Collections</a>
                    <a href="#" class="text-gray-300 hover:text-gold transition-colors duration-300">About</a>
                    <a href="#" class="text-gray-300 hover:text-gold transition-colors duration-300">Contact</a>
                </div>

                <!-- Icons -->
                <div class="flex items-center space-x-6">
                    <button class="text-gray-300 hover:text-gold transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </button>
                    <button class="text-gray-300 hover:text-gold transition-colors duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
                    <button class="text-gray-300 hover:text-gold transition-colors duration-300 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <span class="absolute -top-2 -right-2 bg-amber-300 text-black text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold">3</span>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen pt-20 overflow-hidden">
        <div class="container mx-auto px-6 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                
                <!-- Left Content -->
                <div class="space-y-8 z-10">
                    <!-- Logo Repeat -->
                    <div class="flex items-center space-x-2">
                        <div class="flex space-x-1">
                            <div class="w-2 h-6 bg-amber-300 rounded-full transform -rotate-12"></div>
                            <div class="w-2 h-6 bg-amber-300 rounded-full"></div>
                            <div class="w-2 h-6 bg-amber-300 rounded-full transform rotate-12"></div>
                        </div>
                        <span class="text-xl font-playfair font-semibold text-gold">Luminae</span>
                    </div>

                    <!-- Main Heading -->
                    <div>
                        <h1 class="text-5xl md:text-7xl font-playfair font-bold leading-tight">
                            Your dream<br>
                            <span class="text-gold">Jewellery</span>
                        </h1>
                        <p class="mt-6 text-gray-400 text-lg max-w-md">
                            Discover the finest collection of handcrafted jewelry that tells your unique story with elegance and sophistication.
                        </p>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-wrap gap-4">
                        <button class="px-8 py-3 bg-amber-300 text-black font-semibold rounded-lg hover:bg-opacity-90 transition-all duration-300 transform hover:scale-105">
                            Explore Now
                        </button>
                        <button class="px-8 py-3 border-2 border-amber-300 text-gold font-semibold rounded-lg hover:bg-amber-300 hover:text-black transition-all duration-300">
                            Collections
                        </button>
                    </div>
                </div>

                <!-- Right Content - Snake Image -->
                <div class="relative">
                    <div class="relative w-full aspect-square snake-pattern rounded-3xl overflow-hidden border-2 border-gold border-opacity-30">
                        <!-- Snake Skin Texture Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-gray-800 to-black opacity-70"></div>
                        
                        <!-- Decorative Elements -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-4/5 h-4/5 border-2 border-gold border-opacity-20 rounded-full"></div>
                        </div>
                        
                        <!-- Snake/Jewelry Placeholder -->
                        <div class="absolute inset-0 flex items-center justify-center p-12">
                            <div class="relative w-full h-full">
                                <!-- Simulated snake skin pattern with jewels -->
                                <div class="absolute top-1/4 left-1/4 w-16 h-16 bg-red-600 rounded-full opacity-80 shadow-2xl shadow-red-500/50"></div>
                                <div class="absolute bottom-1/3 right-1/4 w-20 h-20 bg-red-700 rounded-full opacity-70 shadow-2xl shadow-red-600/50"></div>
                                
                                <!-- Golden accent -->
                                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 text-gold text-opacity-20">
                                    <svg class="w-64 h-64" viewBox="0 0 100 100" fill="currentColor">
                                        <path d="M50 10 L60 30 L80 30 L65 45 L70 65 L50 50 L30 65 L35 45 L20 30 L40 30 Z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Glow Effect -->
                        <div class="absolute inset-0 bg-gradient-to-t from-gold via-transparent to-transparent opacity-30"></div>
                    </div>

                    <!-- Floating Elements -->
                    <div class="absolute -top-8 -right-8 w-32 h-32 bg-amber-300 opacity-10 rounded-full blur-3xl"></div>
                    <div class="absolute -bottom-8 -left-8 w-40 h-40 bg-amber-300 opacity-10 rounded-full blur-3xl"></div>
                </div>
            </div>

            <!-- Featured Products Section -->
            <div class="mt-24 space-y-8">
                <div class="flex items-center justify-between">
                    <h2 class="text-3xl font-playfair font-bold">Featured Collections</h2>
                    <a href="#" class="text-gold hover:underline flex items-center gap-2">
                        View All
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>

                <!-- Product Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Product Card 1 -->
                    <div class="bg-gray-900 rounded-2xl overflow-hidden hover-lift cursor-pointer group">
                        <div class="relative h-64 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center p-8">
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            <!-- Ring Icon -->
                            <img class=" rounded-3xl object-fill " src="IMAGES\ring_1.jpeg" alt="">
                            <!--<svg class="w-32 h-32 text-gold" fill="currentColor" viewBox="0 0 100 100">
                                <circle cx="50" cy="50" r="20" fill="none" stroke="currentColor" stroke-width="3"/>
                                <path d="M30 50 L50 30 L70 50" fill="none" stroke="currentColor" stroke-width="3"/>
                                <circle cx="50" cy="30" r="5" fill="currentColor"/>
                            </svg>-->
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-playfair font-semibold mb-2">Classic Gold Ring</h3>
                            <p class="text-gray-400 text-sm mb-4">Timeless elegance in 18K gold</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-gold">$2,499</span>
                                <button class="bg-amber-300 text-black px-4 py-2 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Card 2 -->
                    <div class="bg-gray-900 rounded-2xl overflow-hidden hover-lift cursor-pointer group">
                        <div class="relative h-64 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center p-8">
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            <!-- Pendant Icon -->
                            <img src="IMAGES\watch_1.jpeg" alt="">
                            
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-playfair font-semibold mb-2">Ruby Pendant</h3>
                            <p class="text-gray-400 text-sm mb-4">Exquisite ruby centerpiece</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-gold">$3,899</span>
                                <button class="bg-amber-300 text-black px-4 py-2 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Card 3 -->
                    <div class="bg-gray-900 rounded-2xl overflow-hidden hover-lift cursor-pointer group">
                        <div class="relative h-64 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center p-8">
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            <!-- Diamond Ring Icon -->
                            <svg class="w-32 h-32 text-gold" fill="currentColor" viewBox="0 0 100 100">
                                <circle cx="50" cy="55" r="25" fill="none" stroke="currentColor" stroke-width="3"/>
                                <path d="M35 30 L50 15 L65 30 L50 45 Z" fill="currentColor"/>
                                <circle cx="50" cy="20" r="3" fill="white"/>
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-playfair font-semibold mb-2">Diamond Ring</h3>
                            <p class="text-gray-400 text-sm mb-4">Brilliant cut perfection</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-gold">$8,999</span>
                                <button class="bg-amber-300 text-black px-4 py-2 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Product Card 4 -->
                    <div class="bg-gray-900 rounded-2xl overflow-hidden hover-lift cursor-pointer group">
                        <div class="relative h-64 bg-gradient-to-br from-gray-800 to-gray-900 flex items-center justify-center p-8">
                            <div class="absolute inset-0 bg-amber-300 opacity-0 group-hover:opacity-10 transition-opacity duration-300"></div>
                            <!-- Emerald Ring Icon -->
                            <svg class="w-32 h-32 text-gold" fill="currentColor" viewBox="0 0 100 100">
                                <circle cx="50" cy="55" r="25" fill="none" stroke="currentColor" stroke-width="3"/>
                                <rect x="40" y="15" width="20" height="20" fill="#059669" rx="2"/>
                                <line x1="40" y1="35" x2="35" y2="45" stroke="currentColor" stroke-width="2"/>
                                <line x1="60" y1="35" x2="65" y2="45" stroke="currentColor" stroke-width="2"/>
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-playfair font-semibold mb-2">Emerald Luxury Ring</h3>
                            <p class="text-gray-400 text-sm mb-4">Natural emerald statement piece</p>
                            <div class="flex items-center justify-between">
                                <span class="text-2xl font-bold text-gold">$6,799</span>
                                <button class="bg-amber-300 text-black px-4 py-2 rounded-lg font-semibold hover:bg-opacity-90 transition-all">
                                    Add to Cart
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Background Decorations -->
        <div class="absolute top-0 left-0 w-96 h-96 bg-amber-300 opacity-5 rounded-full blur-3xl -z-10"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-amber-300 opacity-5 rounded-full blur-3xl -z-10"></div>
    </section>

    <!-- Features Section -->
    <section class="py-20 border-t border-gray-800">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                <div class="text-center space-y-4">
                    <div class="w-16 h-16 mx-auto bg-amber-300 bg-opacity-10 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-playfair font-semibold">Premium Quality</h3>
                    <p class="text-gray-400">Handcrafted with the finest materials and gemstones</p>
                </div>
                <div class="text-center space-y-4">
                    <div class="w-16 h-16 mx-auto bg-amber-300 bg-opacity-10 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-playfair font-semibold">Lifetime Warranty</h3>
                    <p class="text-gray-400">Every piece comes with our lifetime craftsmanship guarantee</p>
                </div>
                <div class="text-center space-y-4">
                    <div class="w-16 h-16 mx-auto bg-amber-300 bg-opacity-10 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-playfair font-semibold">Free Shipping</h3>
                    <p class="text-gray-400">Complimentary worldwide shipping on all orders</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 border-t border-gray-800 py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="flex space-x-1">
                            <div class="w-2 h-6 bg-amber-300 rounded-full transform -rotate-12"></div>
                            <div class="w-2 h-6 bg-amber-300 rounded-full"></div>
                            <div class="w-2 h-6 bg-amber-300 rounded-full transform rotate-12"></div>
                        </div>
                        <span class="text-xl font-playfair font-semibold text-gold">Lumina</span>
                    </div>
                    <p class="text-gray-400 text-sm">Crafting dreams into reality, one jewel at a time.</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-gold transition-colors">About Us</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">Collections</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">Custom Design</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Customer Care</h4>
                    <ul class="space-y-2 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-gold transition-colors">Shipping Info</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">Returns</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">Warranty</a></li>
                        <li><a href="#" class="hover:text-gold transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Newsletter</h4>
                    <p class="text-gray-400 text-sm mb-4">Subscribe for exclusive offers</p>
                    <div class="flex">
                        <input type="email" placeholder="Your email" class="flex-1 px-4 py-2 bg-gray-800 border border-gray-700 rounded-l-lg focus:outline-none focus:border-gold text-sm">
                        <button class="px-6 py-2 bg-amber-300 text-black font-semibold rounded-r-lg hover:bg-opacity-90 transition-all">
                            Subscribe
                        </button>
                    </div>
                </div>
            </div>
            <div class="mt-12 pt-8 border-t border-gray-800 text-center text-gray-400 text-sm">
                <p>&copy; 2026 Lumina Accessories. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>
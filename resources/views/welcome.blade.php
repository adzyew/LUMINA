    <!doctype html>
<html lang="en" class="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="color-scheme" content="light" />
    @include('partials.theme_init')
        @vite('resources/css/app.css')
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
        <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        .hero-carousel .swiper-slide { height: 100%; }
        .hero-carousel .swiper-slide img { width: 100%; height: 100%; object-fit: cover; }
        
        /* Scroll Animation Styles */
        .scroll-fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .scroll-fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
        .scroll-slide-left {
            opacity: 0;
            transform: translateX(-50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .scroll-slide-left.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .scroll-slide-right {
            opacity: 0;
            transform: translateX(50px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .scroll-slide-right.visible {
            opacity: 1;
            transform: translateX(0);
        }
        .scroll-scale {
            opacity: 0;
            transform: scale(0.9);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }
        .scroll-scale.visible {
            opacity: 1;
            transform: scale(1);
        }
        </style>
    </head>
<body class="bg-gray-50 text-gray-900 antialiased">

    @include('partials.navbar')

    {{-- Hero Section - Carousel background --}}
    <section class="relative pt-24 sm:pt-28 min-h-[85vh] lg:min-h-[90vh] overflow-hidden">
        <div class="absolute inset-0 -z-10 hero-carousel">
            <div class="swiper heroBgSwiper w-full h-full ">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <img src="{{ asset('IMAGES/BG.png') }}" alt="" />
                    </div>
                    @foreach($heroSlides ?? [] as $slide)
                        @if($slide->image_url ?? $slide->image_path ?? null)
                        <div class="swiper-slide">
                            <img src="{{ $slide->image_url ?? $slide->image_path ?? asset('IMAGES/BG.png') }}" alt="{{ $slide->name }}" />
                        </div>
                        @endif
                    @endforeach
                    @if(empty($heroSlides) || $heroSlides->isEmpty())
                        <div class="swiper-slide"><img src="{{ asset('IMAGES/Necklace.jpg') }}" alt="" /></div>
                        <div class="swiper-slide"><img src="{{ asset('IMAGES/Bracelet.jpg') }}" alt="" /></div>
                        <div class="swiper-slide"><img src="{{ asset('IMAGES/Ring.jpg') }}" alt="" /></div>
                    @endif
                </div>
            </div>
        </div>
        <div class="absolute inset-0 -z-[1] bg-black/25"></div>
        <div class="relative flex flex-col justify-center min-h-[75vh] lg:min-h-[80vh] items-start max-w-3xl">
            <div class="flex flex-col justify-center px-6 sm:px-10 lg:px-16 xl:px-24 py-12 lg:py-24">
                <p class="text-sm font-bold text-amber-200 uppercase tracking-widest mb-3">Handcrafted Elegance</p>
                <h1 class="text-4xl sm:text-5xl lg:text-6xl font-playfair font-bold text-white leading-tight mb-6 drop-shadow-lg">
                    Discover Timeless Jewelry for Every Moment
                            </h1>
                <p class="text-gray-200 text-lg mb-8 max-w-md drop-shadow">
                    Curated pieces that tell your story—from everyday sophistication to unforgettable occasions.
                </p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center w-fit px-10 py-4 bg-amber-300 text-gray-900 font-bold text-sm uppercase tracking-wide rounded-lg hover:bg-amber-400 transition-colors shadow-lg">
                    Explore Collection
                            </a>
                        </div>
                    </div>
    </section>

    {{-- Services Footer Section (4 cards) --}}
    <section id="features" class="py-16 bg-white border-t border-gray-100 scroll-fade-in">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left scroll-scale hover:bg-gray-100 transition-colors duration-700 ease-in-out rounded-lg p-4 hover:shadow-md ">
                    <div class="shrink-0 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-playfair font-bold text-gray-900 mb-1">Nationwide Shipping</h3>
                        <p class="text-gray-500 text-sm">Free shipping on all orders</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left scroll-scale hover:bg-gray-100 transition-colors duration-700 ease-in-out rounded-lg p-4 hover:shadow-md pointer-events-auto ">
                    <div class="shrink-0 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-playfair font-bold text-gray-900 mb-1">Money Back Guarantee</h3>
                        <p class="text-gray-500 text-sm">Back guarantee in 7 days</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left scroll-scale hover:bg-gray-100 transition-colors duration-700 ease-in-out rounded-lg p-4 hover:shadow-md ">
                    <div class="shrink-0 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-playfair font-bold text-gray-900 mb-1">Offers And Discounts</h3>
                        <p class="text-gray-500 text-sm">On every order over ₱1,000</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left scroll-scale hover:bg-gray-100 transition-colors duration-700 ease-in-out rounded-lg p-4 hover:shadow-md ">
                    <div class="shrink-0 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg class="w-8 h-8 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-playfair font-bold text-gray-900 mb-1">24/7 Support Services</h3>
                        <p class="text-gray-500 text-sm">Contact us anytime</p>
                    </div>
                </div>
            </div>
                </div>
    </section>

    {{-- Featured Collections --}}
    <section class="py-20 bg-gray-50 scroll-fade-in">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10 scroll-slide-left">
                <h2 class="text-3xl font-playfair font-bold text-gray-900">Featured Collections</h2>
                <a href="{{ route('products.index') }}" class="text-amber-600 hover:text-amber-700 font-semibold flex items-center gap-2">
                    View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts ?? [] as $product)
                    <a href="{{ route('products.show', $product) }}" class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                        <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
                            <img src="{{ $product->image_url ?? asset('IMAGES/Bracelet.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                        <div class="p-5">
                            <h3 class="font-playfair font-semibold text-gray-900 mb-1 group-hover:text-amber-600 transition-colors">{{ $product->name }}</h3>
                            <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ Str::limit($product->description ?? '', 60) }}</p>
                            <p class="text-xl font-bold text-amber-600">₱{{ number_format($product->price ?? 0, 0) }}</p>
                </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Shop by Category --}}
    <section class="py-20 bg-white scroll-fade-in">
        <div class="container mx-auto px-4 sm:px-6">
            <h2 class="text-3xl font-playfair font-bold text-gray-900 text-center mb-10 scroll-scale">Shop by Category</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
                @foreach([['name'=>'Necklaces','img'=>'Necklace.jpg','cat'=>'necklaces'],['name'=>'Bracelets','img'=>'Bracelet.jpg','cat'=>'bracelets'],['name'=>'Earrings','img'=>'Earrings.jpg','cat'=>'earrings'],['name'=>'Rings','img'=>'Ring.jpg','cat'=>'rings'],['name'=>'Watches','img'=>'Watches.jpg','cat'=>'watches']] as $c)
                    <a href="{{ route('products.index', ['category' => $c['cat']]) }}" class="group block bg-gray-50 rounded-xl overflow-hidden border border-gray-100 hover:border-amber-200 transition-all hover:shadow-md">
                        <div class="aspect-square overflow-hidden">
                            <img src="{{ asset('IMAGES/' . $c['img']) }}" alt="{{ $c['name'] }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300" />
                        </div>
                        <div class="p-4 text-center">
                            <h3 class="font-playfair font-semibold text-gray-900 group-hover:text-amber-600">{{ $c['name'] }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    {{-- About Us / Our Story --}}
    <section class="py-20 bg-gray-50 scroll-fade-in">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-4xl font-playfair font-bold text-gray-900 mb-6 scroll-scale">About Lumina</h2>
                <p class="text-lg text-gray-600 leading-relaxed mb-6">
                    Lumina was born from a passion for creating jewelry that transcends trends. Every piece we craft is designed to tell a story—yours. From elegant necklaces to timeless bracelets, our handcrafted collections blend traditional craftsmanship with modern elegance.
                </p>
                <p class="text-lg text-gray-600 leading-relaxed mb-8">
                    We believe that true luxury lies in the details. Each piece undergoes meticulous quality checks, ensuring you receive nothing but the finest. Experience the Lumina difference—where artistry meets excellence, and every jewel becomes a cherished memory.
                </p>
               
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-20 bg-white scroll-fade-in">
        <div class="container mx-auto px-4 sm:px-6">
            <h2 class="text-3xl font-playfair font-bold text-gray-900 text-center mb-12 scroll-scale">Why Choose Lumina</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Premium Materials</h3>
                    <p class="text-gray-600 text-sm">We use only the finest metals and gemstones, sourced ethically from trusted suppliers worldwide.</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Expert Craftsmanship</h3>
                    <p class="text-gray-600 text-sm">Each piece is meticulously handcrafted by skilled artisans with decades of experience in jewelry making.</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Lifetime Warranty</h3>
                    <p class="text-gray-600 text-sm">Every purchase comes with our lifetime craftsmanship guarantee, ensuring your jewelry lasts for generations.</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Fast & Secure Shipping</h3>
                    <p class="text-gray-600 text-sm">Free worldwide shipping with secure packaging and insurance on every order. Track your package in real-time.</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Personalized Service</h3>
                    <p class="text-gray-600 text-sm">Our dedicated team is here to help you find the perfect piece. Custom orders and consultations available.</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Ethical & Sustainable</h3>
                    <p class="text-gray-600 text-sm">Committed to ethical sourcing and sustainable practices. Every purchase supports responsible jewelry production.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- New Arrivals --}}
    @if(isset($latestProducts) && $latestProducts->isNotEmpty())
    <section class="py-20 bg-gray-50 scroll-fade-in">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-10 scroll-slide-right">
                <h2 class="text-3xl font-playfair font-bold text-gray-900">New Arrivals</h2>
                <a href="{{ route('products.index') }}" class="text-amber-600 hover:text-amber-700 font-semibold flex items-center gap-2">
                    View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($latestProducts->take(8) as $product)
                    <a href="{{ route('products.show', $product) }}" class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                        <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
                            <img src="{{ $product->image_url ?? asset('IMAGES/Bracelet.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-amber-600 uppercase tracking-wider mb-1">{{ $product->category ?? 'Jewelry' }}</p>
                            <h3 class="font-playfair font-semibold text-gray-900 mb-2 group-hover:text-amber-600 transition-colors">{{ $product->name }}</h3>
                            <p class="text-xl font-bold text-amber-600">₱{{ number_format($product->price ?? 0, 0) }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- How It Works --}}
    <section class="py-20 bg-white scroll-fade-in">
        <div class="container mx-auto px-4 sm:px-6">
            <h2 class="text-3xl font-playfair font-bold text-gray-900 text-center mb-12 scroll-scale">How It Works</h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 max-w-5xl mx-auto">
                <div class="text-center">
                    <div class="w-16 h-16 bg-amber-200 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-gray-900">1</div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Browse Collection</h3>
                    <p class="text-gray-600 text-sm">Explore our curated selection of handcrafted jewelry pieces.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-amber-200 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-gray-900">2</div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Select Your Piece</h3>
                    <p class="text-gray-600 text-sm">Choose the perfect jewelry that matches your style and occasion.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-amber-200 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-gray-900">3</div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Secure Checkout</h3>
                    <p class="text-gray-600 text-sm">Complete your purchase with our secure and easy checkout process.</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-amber-200 rounded-full flex items-center justify-center mx-auto mb-4 text-2xl font-bold text-gray-900">4</div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Enjoy & Cherish</h3>
                    <p class="text-gray-600 text-sm">Receive your jewelry and create beautiful memories that last forever.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Customer Testimonials --}}
    <section class="py-20 bg-gray-50 scroll-fade-in">
        <div class="container mx-auto px-4 sm:px-6">
            <h2 class="text-3xl font-playfair font-bold text-gray-900 text-center mb-12 scroll-scale">What Our Customers Say</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-6xl mx-auto">
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="flex gap-1 text-amber-400 mb-4">★★★★★</div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"Stunning quality and fast shipping. The necklace I ordered exceeded my expectations. Lumina is now my go-to for special occasions."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center font-bold text-gray-900">MR</div>
                        <div>
                            <p class="font-semibold text-gray-900">Maria Rodriguez</p>
                            <p class="text-sm text-gray-500">Verified Customer</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="flex gap-1 text-amber-400 mb-4">★★★★★</div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"Elegant, timeless pieces that get compliments every time. The craftsmanship is exceptional. Highly recommend Lumina to anyone looking for quality jewelry."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center font-bold text-gray-900">JL</div>
                        <div>
                            <p class="font-semibold text-gray-900">James Lee</p>
                            <p class="text-sm text-gray-500">Verified Customer</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl p-8 shadow-sm border border-gray-100">
                    <div class="flex gap-1 text-amber-400 mb-4">★★★★★</div>
                    <p class="text-gray-600 mb-6 leading-relaxed">"Beautiful jewelry at fair prices. Customer service was excellent and helped me find the perfect gift. Will definitely be ordering again."</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-amber-200 rounded-full flex items-center justify-center font-bold text-gray-900">SM</div>
                        <div>
                            <p class="font-semibold text-gray-900">Sofia Martinez</p>
                            <p class="text-sm text-gray-500">Verified Customer</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Browse More Products --}}
    @if(isset($browseProducts) && $browseProducts->isNotEmpty())
    <section class="py-20 bg-gray-50 scroll-fade-in">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="text-center mb-12 scroll-scale">
                <h2 class="text-3xl font-playfair font-bold text-gray-900 mb-4">Explore Our Complete Collection</h2>
                <p class="text-gray-600 max-w-2xl mx-auto">Discover our full range of handcrafted jewelry. From statement necklaces to delicate earrings, find the perfect piece for every occasion.</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($browseProducts->take(8) as $product)
                    <a href="{{ route('products.show', $product) }}" class="group bg-white rounded-xl overflow-hidden shadow-sm hover:shadow-lg border border-gray-100 transition-all duration-300 hover:-translate-y-1">
                        <div class="aspect-square bg-gray-100 flex items-center justify-center overflow-hidden">
                            <img src="{{ $product->image_url ?? asset('IMAGES/Bracelet.jpg') }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
                        </div>
                        <div class="p-5">
                            <p class="text-xs text-amber-600 uppercase tracking-wider mb-1">{{ $product->category ?? 'Jewelry' }}</p>
                            <h3 class="font-playfair font-semibold text-gray-900 mb-2 group-hover:text-amber-600 transition-colors line-clamp-2">{{ $product->name }}</h3>
                            <p class="text-gray-500 text-sm mb-3 line-clamp-2">{{ Str::limit($product->description ?? '', 60) }}</p>
                            <div class="flex items-center justify-between">
                                <p class="text-xl font-bold text-amber-600">₱{{ number_format($product->price ?? 0, 0) }}</p>
                                <span class="text-sm text-amber-600 font-medium group-hover:underline">View →</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="text-center mt-12">
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-10 py-4 bg-amber-300 text-gray-900 font-bold rounded-lg hover:bg-amber-400 transition-colors">
                    View All Products
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- Newsletter / Subscribe --}}
    <section class="py-20 bg-white border-t border-gray-100 scroll-fade-in">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="max-w-2xl mx-auto text-center scroll-scale">
                <h2 class="text-3xl font-playfair font-bold text-gray-900 mb-4">Join the Lumina Family</h2>
                <p class="text-gray-600 text-lg mb-8">Subscribe to receive exclusive offers, early access to new collections, and timeless style inspiration delivered to your inbox.</p>
                <form class="flex flex-col sm:flex-row gap-3 max-w-md mx-auto">
                    <input type="email" placeholder="Enter your email address" class="flex-1 px-5 py-3 bg-gray-50 border border-gray-200 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:border-amber-300 focus:ring-1 focus:ring-amber-300" />
                    <button type="button" class="px-8 py-3 bg-amber-300 text-gray-900 font-bold rounded-lg hover:bg-amber-400 transition-colors whitespace-nowrap">Subscribe</button>
                </form>
                <p class="text-xs text-gray-500 mt-4">By subscribing, you agree to our Privacy Policy and Terms of Service.</p>
            </div>
        </div>
    </section>

    @include('partials.footer')

                <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
                <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.heroBgSwiper', {
                        loop: true,
                effect: 'fade',
                        fadeEffect: { crossFade: true },
                speed: 1500,
                autoplay: { delay: 4000, disableOnInteraction: false },
                allowTouchMove: false,
            });

            // Scroll Animation Observer
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, observerOptions);

            // Observe all elements with scroll animation classes
            document.querySelectorAll('.scroll-fade-in, .scroll-slide-left, .scroll-slide-right, .scroll-scale').forEach(el => {
                observer.observe(el);
            });
            });
                </script>
    </body>
    </html>

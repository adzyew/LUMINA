    <!doctype html>
<html lang="en" class="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Lumina Jewelry Accessories</title>
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('IMAGES/FinalIcon.png') }}" />
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('IMAGES/FinalIcon.png') }}" />
        <link rel="shortcut icon" href="{{ asset('IMAGES/FinalIcon.png') }}" />
        <link rel="apple-touch-icon" href="{{ asset('IMAGES/FinalIcon.png') }}" />
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
    <section class="relative pt-24 sm:pt-28 min-h-screen lg:min-h-screen overflow-hidden scrollbar-hide">
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
        <div class="absolute inset-0 -z-1 bg-black/25"></div>
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
    <section id="features" class="py-16 bg-white border-t border-gray-100 scroll-fade-in scroll-smooth">
        <div class="container mx-auto px-2 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-10 ">
                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left scroll-scale p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-700 ease-in-out transform hover:scale-103">
                    <div class="shrink-0 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" class="size-10" viewBox="0 0 50 50">
                            <path d="M 0 8 L 0 10 L 28.09375 10 C 28.492188 10 29 10.507813 29 10.90625 L 29 38 L 18.90625 38 C 18.429688 35.164063 15.964844 33 13 33 C 10.035156 33 7.570313 35.164063 7.09375 38 L 3 38 C 2.445313 38 2 37.554688 2 37 L 2 28 L 0 28 L 0 37 C 0 38.644531 1.355469 40 3 40 L 7.09375 40 C 7.570313 42.835938 10.035156 45 13 45 C 15.964844 45 18.429688 42.835938 18.90625 40 L 34.09375 40 C 34.570313 42.835938 37.035156 45 40 45 C 42.964844 45 45.429688 42.835938 45.90625 40 L 47 40 C 47.832031 40 48.5625 39.625 49.09375 39.09375 C 49.625 38.5625 50 37.832031 50 37 L 50 27.40625 C 50 26.28125 49.570313 25.25 49.1875 24.46875 C 48.804688 23.6875 48.40625 23.125 48.40625 23.125 L 48.40625 23.09375 L 44.3125 17.59375 L 44.28125 17.59375 L 44.28125 17.5625 C 43.394531 16.453125 41.972656 15 40 15 L 32 15 C 31.640625 15 31.3125 15.066406 31 15.1875 L 31 10.90625 C 31 9.304688 29.695313 8 28.09375 8 Z M 0 12 L 0 14 L 18 14 L 18 12 Z M 0 16 L 0 18 L 15 18 L 15 16 Z M 32 17 L 36 17 L 36 26 C 36 26.832031 36.375 27.5625 36.90625 28.09375 C 37.4375 28.625 38.167969 29 39 29 L 48 29 L 48 37 C 48 37.167969 47.875 37.4375 47.65625 37.65625 C 47.4375 37.875 47.167969 38 47 38 L 45.90625 38 C 45.429688 35.164063 42.964844 33 40 33 C 37.035156 33 34.570313 35.164063 34.09375 38 L 31 38 L 31 18 C 31 17.832031 31.125 17.5625 31.34375 17.34375 C 31.5625 17.125 31.832031 17 32 17 Z M 38 17 L 40 17 C 40.824219 17 41.972656 17.925781 42.6875 18.8125 L 46.78125 24.28125 L 46.8125 24.3125 C 46.832031 24.339844 47.101563 24.722656 47.40625 25.34375 C 47.660156 25.859375 47.792969 26.472656 47.875 27 L 39 27 C 38.832031 27 38.5625 26.875 38.34375 26.65625 C 38.125 26.4375 38 26.167969 38 26 Z M 0 20 L 0 22 L 12 22 L 12 20 Z M 0 24 L 0 26 L 9 26 L 9 24 Z M 13 35 C 15.222656 35 17 36.777344 17 39 C 17 41.222656 15.222656 43 13 43 C 10.777344 43 9 41.222656 9 39 C 9 36.777344 10.777344 35 13 35 Z M 40 35 C 42.222656 35 44 36.777344 44 39 C 44 41.222656 42.222656 43 40 43 C 37.777344 43 36 41.222656 36 39 C 36 36.777344 37.777344 35 40 35 Z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-playfair font-bold text-gray-900 mb-1">Metro Manila Shipping</h3>
                        <p class="text-gray-500 text-sm">Free shipping on all orders</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left scroll-scale p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-700 ease-in-out transform hover:scale-103">
                    <div class="shrink-0 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" class="size-8" viewBox="0 0 24 24">
                            <path d="M21,6h-3.008c-0.952-1.78-2.884-3-5.117-3c-0.203,0-7.25,0-7.25,0v3H3v1.75h2.625v1.5H3V11h2.625v10H8v-7 c0,0,4.609,0,4.875,0c2.233,0,4.165-1.22,5.117-3H21V9.25h-2.435c0.035-0.246,0.06-0.495,0.06-0.75s-0.024-0.504-0.06-0.75H21V6z M8,5h4.75c0.952,0,1.813,0.383,2.444,1H8V5z M12.75,12H8v-1h7.194C14.563,11.617,13.702,12,12.75,12z M16.165,9.25H8v-1.5h8.165 c0.053,0.242,0.085,0.492,0.085,0.75S16.218,9.008,16.165,9.25z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-playfair font-bold text-gray-900 mb-1">Money Back Guarantee</h3>
                        <p class="text-gray-500 text-sm">Back guarantee in 7 days</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-2 text-center sm:text-left scroll-scale p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-700 ease-in-out transform hover:scale-103">
                    <div class="shrink-0 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" class="size-8" viewBox="0 0 50 50">
                            <path d="M 25 1 A 1.0001 1.0001 0 0 0 24.068359 1.6386719 L 17.902344 17.535156 L 0.94921875 18.400391 A 1.0001 1.0001 0 0 0 0.3671875 20.173828 L 13.568359 30.966797 L 9.2324219 47.34375 A 1.0001 1.0001 0 0 0 10.740234 48.441406 L 25 39.289062 L 39.259766 48.441406 A 1.0001 1.0001 0 0 0 40.767578 47.34375 L 36.431641 30.966797 L 49.632812 20.173828 A 1.0001 1.0001 0 0 0 49.050781 18.400391 L 32.097656 17.535156 L 25.931641 1.6386719 A 1.0001 1.0001 0 0 0 25 1 z M 25 4.7636719 L 30.466797 18.861328 A 1.0001 1.0001 0 0 0 31.349609 19.498047 L 46.359375 20.265625 L 34.667969 29.826172 A 1.0001 1.0001 0 0 0 34.333984 30.855469 L 38.175781 45.369141 L 25.541016 37.257812 A 1.0001 1.0001 0 0 0 24.458984 37.257812 L 11.824219 45.369141 L 15.666016 30.855469 A 1.0001 1.0001 0 0 0 15.332031 29.826172 L 3.640625 20.265625 L 18.650391 19.498047 A 1.0001 1.0001 0 0 0 19.533203 18.861328 L 25 4.7636719 z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-playfair font-bold text-gray-900 mb-1">Offers And Discounts</h3>
                        <p class="text-gray-500 text-sm">On every order over ₱1,000</p>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row items-center sm:items-start gap-4 text-center sm:text-left scroll-scale p-6 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors duration-700 ease-in-out transform hover:scale-103">
                    <div class="shrink-0 w-14 h-14 rounded-full bg-amber-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" class="size-9" viewBox="0 0 48 48">
                            <path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M27.5,42.5c1.1,0,2-0.9,2-2v-2c0-1.1,0.9-2,2-2l0,0c2.8,0,5-2.2,5-5V28c0,0,4-1.1,4-2c0-1.3-4-9-4.5-10.5	c-2-6-6.7-10-13.5-10c-6.3,0-11.2,2.7-13.5,7.2"></path><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M7.6,21.2c0.8,7.3,5.4,7.1,5.9,19.4c0,1.1,0.9,1.9,2,1.9h6"></path><circle cx="21.5" cy="21.5" r="3" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"></circle><path fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M21.5,24.5V25c0,2.5,2,4.5,4.5,4.5h4"></path><circle cx="30.5" cy="29.5" r="2.5"></circle><line x1="21.5" x2="21.5" y1="6" y2="18.5" fill="none" stroke="#000" stroke-linecap="round" stroke-linejoin="round" stroke-width="3"></line>
                        </svg>
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
    <section id="about" class="py-20 bg-gray-50 scroll-fade-in">
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
                            @if($product->description)
                                @php
                                    // Split on either • or newlines, trim, and filter out empty lines
                                    $features = preg_split('/[•\n\r]+/', $product->description);
                                    $features = array_filter(array_map('trim', $features));
                                @endphp
                                <ul class="text-gray-500 text-sm mb-3 list-disc list-inside">
                                    @foreach($features as $feature)
                                        <li>{{ $feature }}</li>
                                    @endforeach
                                </ul>
                            @endif
                            @php
                                $avgRating = (float) ($product->reviews_avg_rating ?? 0);
                                $reviewCount = (int) ($product->reviews_count ?? 0);
                                $filledStars = (int) round($avgRating);
                            @endphp
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex items-center gap-0.5">
                                    @for($star = 1; $star <= 5; $star++)
                                        <svg class="w-4 h-4 {{ $star <= $filledStars ? 'text-amber-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.154c.969 0 1.371 1.24.588 1.81l-3.36 2.441a1 1 0 00-.364 1.118l1.285 3.95c.3.922-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.196-1.539-1.118l1.286-3.95a1 1 0 00-.364-1.118L2.07 9.377c-.783-.57-.38-1.81.588-1.81h4.154a1 1 0 00.95-.69l1.287-3.95z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-500">{{ number_format($avgRating, 1) }} ({{ $reviewCount }})</span>
                            </div>
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
            <h2 class="text-3xl font-playfair font-bold text-gray-900 text-center mb-6 scroll-scale">Shop by Category</h2>
            <div class="flex flex-col gap-4">
                <!-- If you have a search bar, place it here and add mb-2 for spacing -->
                {{-- <div class="mb-2">@include('partials.searchbar')</div> --}}
                <div class="flex gap-3 overflow-x-auto pb-2 -mx-2 px-2 sm:grid sm:grid-cols-3 lg:grid-cols-5 sm:gap-6 sm:overflow-visible sm:mx-0 sm:px-0">
                    @foreach([['name'=>'Necklaces','img'=>'Necklace.jpg','cat'=>'necklaces'],['name'=>'Bracelets','img'=>'Bracelet.jpg','cat'=>'bracelets'],['name'=>'Earrings','img'=>'Earrings.jpg','cat'=>'earrings'],['name'=>'Rings','img'=>'Ring.jpg','cat'=>'rings'],['name'=>'Watches','img'=>'Watches.jpg','cat'=>'watches']] as $c)
                        <a href="{{ route('products.index', ['category' => $c['cat']]) }}" class="group min-w-[140px] max-w-[180px] flex-shrink-0 bg-gray-50 rounded-xl overflow-hidden border border-gray-100 hover:border-amber-200 transition-all hover:shadow-md sm:min-w-0 sm:max-w-none">
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
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors transform hover:scale-103">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Premium Materials</h3>
                    <p class="text-gray-600 text-sm">We use only the finest metals and gemstones, sourced ethically from trusted suppliers worldwide.</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors transform hover:scale-103">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Expert Craftsmanship</h3>
                    <p class="text-gray-600 text-sm">Each piece is meticulously handcrafted by skilled artisans with decades of experience in jewelry making.</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors transform hover:scale-103">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Lifetime Warranty</h3>
                    <p class="text-gray-600 text-sm">Every purchase comes with our lifetime craftsmanship guarantee, ensuring your jewelry lasts for generations.</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors transform hover:scale-103">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Fast & Secure Shipping</h3>
                    <p class="text-gray-600 text-sm">Free worldwide shipping with secure packaging and insurance on every order. Track your package in real-time.</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors transform hover:scale-103">
                    <div class="w-16 h-16 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <h3 class="font-playfair font-bold text-gray-900 mb-2">Personalized Service</h3>
                    <p class="text-gray-600 text-sm">Our dedicated team is here to help you find the perfect piece. Custom orders and consultations available.</p>
                </div>
                <div class="text-center p-6 rounded-xl bg-gray-50 hover:bg-gray-100 transition-colors transform hover:scale-103">
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
                            @php
                                $avgRating = (float) ($product->reviews_avg_rating ?? 0);
                                $reviewCount = (int) ($product->reviews_count ?? 0);
                                $filledStars = (int) round($avgRating);
                            @endphp
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex items-center gap-0.5">
                                    @for($star = 1; $star <= 5; $star++)
                                        <svg class="w-4 h-4 {{ $star <= $filledStars ? 'text-amber-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.154c.969 0 1.371 1.24.588 1.81l-3.36 2.441a1 1 0 00-.364 1.118l1.285 3.95c.3.922-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.196-1.539-1.118l1.286-3.95a1 1 0 00-.364-1.118L2.07 9.377c-.783-.57-.38-1.81.588-1.81h4.154a1 1 0 00.95-.69l1.287-3.95z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-500">{{ number_format($avgRating, 1) }} ({{ $reviewCount }})</span>
                            </div>
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
                            @php
                                $avgRating = (float) ($product->reviews_avg_rating ?? 0);
                                $reviewCount = (int) ($product->reviews_count ?? 0);
                                $filledStars = (int) round($avgRating);
                            @endphp
                            <div class="flex items-center gap-2 mb-3">
                                <div class="flex items-center gap-0.5">
                                    @for($star = 1; $star <= 5; $star++)
                                        <svg class="w-4 h-4 {{ $star <= $filledStars ? 'text-amber-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.154c.969 0 1.371 1.24.588 1.81l-3.36 2.441a1 1 0 00-.364 1.118l1.285 3.95c.3.922-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.196-1.539-1.118l1.286-3.95a1 1 0 00-.364-1.118L2.07 9.377c-.783-.57-.38-1.81.588-1.81h4.154a1 1 0 00.95-.69l1.287-3.95z" />
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-500">{{ number_format($avgRating, 1) }} ({{ $reviewCount }})</span>
                            </div>
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
                    } else {
                        entry.target.classList.remove('visible');
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

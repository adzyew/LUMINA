<!doctype html>
<html lang="en">
<head>
    @include('partials.favicon')
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    <title>{{ $product->name }} | Lumina</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-stone-100 text-gray-900 font-sans antialiased min-h-screen flex flex-col">

    <div class="fixed inset-0 -z-50 overflow-hidden">
        <div class="absolute inset-0 bg-stone-400/30 backdrop-blur-[2px]"></div>
        <div class="absolute inset-0 bg-linear-to-b from-stone-200/70 via-stone-100/50 to-stone-200/80"></div>
    </div>

    @include('partials.navbar')

    <main class="grow max-w-6xl mx-auto px-4 sm:px-6 lg:px-7 py-5 sm:py-7 lg:py-6 w-full">
        <a href="{{ url()->previous() ?? route('products.index') }}" class="inline-flex items-center gap-2 rounded-full bg-white/85 px-3.5 py-1.5 text-gray-600 hover:text-amber-600 text-xs sm:text-sm font-medium mb-6 transition-colors border border-gray-200 shadow-sm group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Collection
        </a>

        <div class="mt-5 rounded-2xl border border-white/70 bg-white/55 backdrop-blur-sm shadow-[0_10px_30px_rgba(0,0,0,0.05)] p-4 sm:p-5 lg:p-6 lg:h-[calc(100dvh-9.5rem)]">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-7 h-full lg:items-stretch">

            {{-- LEFT COL: Product Images (Gallery) --}}
            @php
                $gallery = collect($product->images ?? [])->pluck('image_url')->filter()->values();
                if ($gallery->isEmpty() && ($product->image_url ?? null)) {
                    $gallery = collect([$product->image_url]);
                }
                $mainImg = $gallery->first();
            @endphp

            <div class="lg:sticky lg:top-20 h-max lg:h-full">
                <div class="relative rounded-2xl border border-amber-100 bg-linear-to-b from-white to-stone-50 shadow-md overflow-hidden p-3 sm:p-4 h-max lg:h-full">
                <div class="relative bg-white rounded-xl border border-gray-100 overflow-hidden flex items-center justify-center p-2.5 sm:p-3 aspect-[4/5] max-h-[70dvh] lg:aspect-auto lg:h-full group">
                    @if($mainImg)
                        <img id="mainProductImage" src="{{ $mainImg }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-xl transition-transform duration-700 group-hover:scale-[1.03]">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-600">
                            <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif

                    @if($product->is_featured ?? false)
                        <span class="absolute top-4 left-4 px-3 py-1 bg-amber-300 text-black text-[10px] font-bold rounded-full uppercase tracking-wider shadow-md">Featured</span>
                    @endif

                    @auth
                        @php $isWishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-4 right-4 z-10 js-wishlist-form">
                            @csrf
                            <button type="submit" class="js-wishlist-btn w-10 h-10 bg-white rounded-full flex items-center justify-center shadow-md hover:scale-110 transition-transform duration-200" data-wishlisted="{{ $isWishlisted ? '1' : '0' }}">
                                <svg class="js-wishlist-icon w-5 h-5 transition-colors duration-200 {{ $isWishlisted ? 'text-red-500 fill-red-500' : 'text-gray-900 hover:text-red-500' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                            </button>
                        </form>
                    @endauth
                </div>
                </div>

                @if($gallery->count() > 1)
                    <div class="mt-3 grid grid-cols-5 gap-2 overflow-x-auto pb-1">
                        @foreach($gallery as $img)
                            <button type="button" onclick="setMainProductImage('{{ $img }}')" class="bg-white rounded-xl border border-gray-200 hover:border-amber-400 overflow-hidden aspect-square transition-colors shadow-sm">
                                <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- RIGHT COL: Product Details --}}
            <div class="flex flex-col bg-white rounded-2xl border border-gray-100 shadow-md p-4 sm:p-5 lg:h-full lg:overflow-auto">
                
                <div class="flex flex-wrap gap-1 mb-4">
                    <span class="px-3 py-1 rounded border border-amber-500/80 text-[11px] font-semibold text-amber-700 uppercase tracking-widest bg-amber-50">
                        {{ $product->category ?? 'Jewelry' }}
                    </span>
                    @php $inStock = ($product->stock_quantity ?? 0) > 0; @endphp
                    <span class="px-3 py-1 rounded text-[11px] font-semibold border uppercase tracking-widest {{ $inStock ? 'text-emerald-700 border-emerald-300 bg-emerald-50' : 'bg-red-50 text-red-600 border-red-300' }}">
                        {{ $inStock ? 'In Stock' : 'Sold Out' }}
                    </span>
                </div>


                <div class="mb-6">
                    <h1 class="text-3xl sm:text-4xl font-playfair font-bold text-gray-900 mb-3 leading-tight">{{ $product->name }}</h1>
                    <p class="text-xl sm:text-2xl font-bold text-amber-500">₱ {{ number_format($product->price ?? 0, 2) }}</p>
                    @if($product->description ?? null)
                        @php
                            $rawDescription = html_entity_decode((string) $product->description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            $rawDescription = preg_replace('/<br\s*\/?>/iu', "\n", $rawDescription) ?? $rawDescription;
                            $rawDescription = strip_tags($rawDescription);

                            $features = collect(preg_split('/(?:\r\n|\r|\n|•|â€¢)/u', $rawDescription))
                                ->map(function ($line) {
                                    $normalized = (string) $line;
                                    $normalized = preg_replace('/[\x{00A0}\x{200B}\x{200C}\x{200D}\x{FEFF}]/u', ' ', $normalized) ?? $normalized;
                                    $normalized = preg_replace('/\s+/u', ' ', $normalized) ?? $normalized;
                                    return trim($normalized);
                                })
                                ->filter(fn ($line) => $line !== '' && preg_match('/[\pL\pN]/u', $line));
                        @endphp
                        <ul class="text-gray-800 leading-relaxed text-sm sm:text-base list-disc pl-5 space-y-1">
                            @foreach($features as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <hr class="border-gray-200 mb-5">

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 mb-5">
                    @if($inStock)
                        <a href="{{ route('cart.add', $product->id) }}" class="js-add-to-cart-btn sm:col-span-2 w-full h-12 bg-amber-300 text-black font-bold text-base rounded-xl hover:bg-amber-400 transition-all duration-300 flex items-center justify-center gap-2 shadow-[0_4px_14px_rgba(245,158,11,0.22)] hover:shadow-[0_8px_18px_rgba(245,158,11,0.32)] hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                            Add to Cart
                        </a>
                    @else
                        <span class="sm:col-span-2 w-full h-12 bg-gray-200 text-gray-500 font-bold text-base rounded-xl flex items-center justify-center cursor-not-allowed">
                            Currently Unavailable
                        </span>
                    @endif

                    <a href="{{ route('products.index') }}" class="sm:col-span-2 w-full h-11 bg-white text-black font-semibold rounded-xl hover:bg-gray-50 border border-gray-200 transition-colors flex items-center justify-center">
                        Continue Shopping
                    </a>
                </div>

            </div>
        </div>
        </div>

        <div class="mt-4 bg-linear-to-r from-stone-50 to-white rounded-2xl p-4 border border-gray-200 shadow-sm">
            <p class="text-[11px] uppercase tracking-[0.18em] text-gray-500 font-semibold mb-2.5">Why Shop With Lumina?</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                <div class="flex items-center gap-2.5 text-black rounded-xl border border-amber-100 bg-white px-3 py-2">
                    <svg class="w-6 h-6 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"></path></svg>
                    <span class="text-xs sm:text-sm font-medium">Authentic Lumina Quality</span>
                </div>
                <div class="flex items-center gap-2.5 text-black rounded-xl border border-amber-100 bg-white px-3 py-2">
                    <svg class="w-6 h-6 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"></path></svg>
                    <span class="text-xs sm:text-sm font-medium">Secure & Insured Shipping</span>
                </div>
            </div>
        </div>

        {{-- Reviews Section (Refined) --}}
        <div class="mt-10 bg-white rounded-xl p-8 sm:p-10 border border-gray-100 shadow-md">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-gray-900 mb-3">Customer Reviews</h2>
                    @if($averageRating ?? null)
                        <div class="flex items-center gap-3">
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= round($averageRating) ? 'text-amber-400 fill-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <span class="text-gray-600 font-medium">{{ number_format($averageRating, 1) }} out of 5</span>
                            <span class="text-gray-500">({{ $reviews->total() }} Review(s))</span>
                        </div>
                    @else
                        <p class="text-gray-500">No reviews yet. Be the first to review!</p>
                    @endif
                </div>
                @auth
                    @if($canReview ?? false)
                        <button onclick="document.getElementById('reviewForm').classList.toggle('hidden')" class="w-full sm:w-auto px-8 py-3.5 bg-amber-50 text-amber-700 font-bold rounded-full hover:bg-amber-100 border border-amber-200 transition-colors text-center">
                            Write a Review
                        </button>
                    @else
                        <div class="w-full sm:w-auto px-6 py-3 rounded-full bg-gray-100 border border-gray-200 text-gray-600 text-sm font-semibold text-center">
                            Purchase this product first to leave a review.
                        </div>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-amber-50 text-amber-700 font-bold rounded-full hover:bg-amber-100 border border-amber-200 transition-colors text-center">
                        Login to Review
                    </a>
                @endauth
            </div>

            {{-- Review Form --}}
            @auth
            @if($canReview ?? false)
            <form id="reviewForm" action="{{ route('reviews.store', $product) }}" method="POST" class="hidden mb-12 p-8 bg-white rounded-[1.5rem] border border-gray-100 shadow-sm">
                @csrf
                <h3 class="text-xl font-bold text-gray-900 mb-6">Write Your Review</h3>
                <div class="mb-6">
                    <label class="block text-amber-400 text-sm font-medium mb-3">Rating</label>
                    <div class="flex gap-2" id="ratingStars">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="star-rating w-10 h-10 text-amber-400 hover:text-amber-600 hover:scale-110 transition-all" data-rating="{{ $i }}">
                                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="5" required>
                </div>
                <div class="mb-6">
                    <label for="comment" class="block text-gray-600 text-sm font-medium mb-3">Your Review</label>
                    <textarea name="comment" id="comment" rows="4" class="w-full px-5 py-4 bg-white border border-gray-300 rounded-2xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-amber-300 transition-colors" placeholder="Share your thoughts about this product..."></textarea>
                </div>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <button type="submit" class="w-full sm:w-auto px-8 py-3.5 bg-amber-300 text-black font-bold rounded-full hover:bg-amber-400 transition-colors">Submit Review</button>
                    <button type="button" onclick="document.getElementById('reviewForm').classList.add('hidden')" class="w-full sm:w-auto px-8 py-3.5 bg-transparent text-gray-500 font-semibold rounded-full hover:text-gray-700 transition-colors">Cancel</button>
                </div>
            </form>
            @endif
            @endauth

            {{-- Reviews List --}}
            <div class="space-y-8">
                @forelse($reviews ?? [] as $review)
                    <div class="pb-8 border-b border-gray-100 last:border-0 last:pb-0">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold text-lg border border-amber-200">
                                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-lg">{{ $review->user->name ?? 'Anonymous' }}</p>
                                    <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1 bg-amber-50 px-3 py-1.5 rounded-full border border-amber-200">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="text-gray-600 leading-relaxed pl-0 sm:pl-16">{{ $review->comment }}</p>
                        @endif
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="w-20 h-20 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-4 text-amber-600">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </div>
                        <p class="text-gray-500 text-lg">No reviews yet.</p>
                    </div>
                @endforelse
            </div>

            @if(isset($reviews) && $reviews->hasPages())
                <div class="mt-10">{{ $reviews->links() }}</div>
            @endif
        </div>

        @if(isset($relatedProducts) && $relatedProducts->isNotEmpty())
            <section class="mt-16">
                <div class="flex items-center justify-between gap-4 mb-8">
                    <h2 class="text-2xl font-playfair font-semibold text-gray-900">You may also like</h2>
                    <a href="{{ route('products.index') }}" class="text-amber-600 hover:text-amber-700 font-semibold text-sm">View More</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-2">
                    @foreach($relatedProducts as $relatedProduct)
                        @php
                            $relatedAvgRating = (float) ($relatedProduct->reviews_avg_rating ?? 0);
                            $relatedReviewCount = (int) ($relatedProduct->reviews_count ?? 0);
                            $relatedFilledStars = (int) round($relatedAvgRating);
                        @endphp
                        <a href="{{ route('products.show', $relatedProduct) }}" class="group bg-white rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                            <div class="aspect-square bg-gray-100 overflow-hidden">
                                <img src="{{ $relatedProduct->image_url ?? asset('IMAGES/Bracelet.jpg') }}" alt="{{ $relatedProduct->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                            </div>
                            <div class="p-4">
                                <p class="text-xs text-amber-600 uppercase tracking-wider mb-1">{{ $relatedProduct->category ?? 'Jewelry' }}</p>
                                <h3 class="font-playfair font-semibold text-gray-900 group-hover:text-amber-600 transition-colors line-clamp-1">{{ $relatedProduct->name }}</h3>
                                <div class="flex items-center gap-2 my-2">
                                    <div class="flex items-center gap-0.5">
                                        @for($star = 1; $star <= 5; $star++)
                                            <svg class="w-4 h-4 {{ $star <= $relatedFilledStars ? 'text-amber-400' : 'text-gray-300' }}" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.154c.969 0 1.371 1.24.588 1.81l-3.36 2.441a1 1 0 00-.364 1.118l1.285 3.95c.3.922-.755 1.688-1.538 1.118l-3.36-2.44a1 1 0 00-1.175 0l-3.36 2.44c-.783.57-1.838-.196-1.539-1.118l1.286-3.95a1 1 0 00-.364-1.118L2.07 9.377c-.783-.57-.38-1.81.588-1.81h4.154a1 1 0 00.95-.69l1.287-3.95z" />
                                            </svg>
                                        @endfor
                                    </div>
                                    <span class="text-xs text-gray-500">{{ number_format($relatedAvgRating, 1) }} ({{ $relatedReviewCount }})</span>
                                </div>
                                <p class="text-xl font-bold text-amber-500">₱ {{ number_format($relatedProduct->price ?? 0, 2) }}</p>
                            </div>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    <script>
        function setMainProductImage(url) {
            const img = document.getElementById('mainProductImage');
            if (img) img.src = url;
        }

        function setRating(rating) {
            document.getElementById('ratingInput').value = rating;
            const stars = document.querySelectorAll('.star-rating');
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.add('text-amber-400', 'fill-amber-400');
                    star.querySelector('svg').setAttribute('fill', 'currentColor');
                } else {
                    star.classList.remove('text-amber-400', 'fill-amber-400');
                    star.querySelector('svg').setAttribute('fill', 'none');
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
            function showActionToast(message, tone) {
                let toast = document.getElementById('cartToast');
                if (!toast) {
                    toast = document.createElement('div');
                    toast.id = 'cartToast';
                    toast.className = 'fixed top-24 right-4 sm:right-6 z-50 max-w-sm w-[calc(100vw-2rem)] sm:w-auto px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 opacity-0 translate-y-2 pointer-events-none transition-all duration-300';
                    toast.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        <span class="text-sm font-semibold"></span>
                    `;
                    document.body.appendChild(toast);
                }

                toast.classList.remove('bg-emerald-500', 'bg-red-500', 'bg-amber-300', 'text-white', 'text-black');
                if (tone === 'error') {
                    toast.classList.add('bg-red-500', 'text-white');
                } else if (tone === 'success') {
                    toast.classList.add('bg-emerald-500', 'text-white');
                } else {
                    toast.classList.add('bg-amber-300', 'text-black');
                }

                const messageNode = toast.querySelector('span');
                if (messageNode) {
                    messageNode.textContent = message;
                }

                requestAnimationFrame(() => {
                    toast.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
                });

                window.setTimeout(() => {
                    toast.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
                }, 2400);
            }

            function updateNavbarCartCount(nextCount) {
                const badge = document.getElementById('navbarCartCount');
                if (!badge) return;
                const parsed = Number(nextCount) || 0;
                badge.textContent = String(parsed);
                badge.classList.toggle('hidden', parsed <= 0);
            }

            const addToCartButton = document.querySelector('.js-add-to-cart-btn');
            if (addToCartButton) {
                addToCartButton.addEventListener('click', async function (event) {
                    event.preventDefault();
                    if (addToCartButton.dataset.loading === '1') return;
                    addToCartButton.dataset.loading = '1';
                    addToCartButton.classList.add('opacity-70', 'pointer-events-none');

                    try {
                        const response = await fetch(addToCartButton.href, {
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        });
                        const data = await response.json();
                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Failed to add to cart');
                        }

                        updateNavbarCartCount(data.cart_count);
                        showActionToast(data.message || 'Added to cart!', 'success');
                    } catch (error) {
                        showActionToast('Failed to add to cart.', 'error');
                    } finally {
                        addToCartButton.classList.remove('opacity-70', 'pointer-events-none');
                        addToCartButton.dataset.loading = '0';
                    }
                });
            }

            const wishlistForm = document.querySelector('.js-wishlist-form');
            if (wishlistForm) {
                wishlistForm.addEventListener('submit', async function (event) {
                    event.preventDefault();

                    const button = wishlistForm.querySelector('.js-wishlist-btn');
                    const icon = wishlistForm.querySelector('.js-wishlist-icon');
                    if (!button || !icon) return;
                    if (button.dataset.loading === '1') return;

                    button.dataset.loading = '1';
                    button.classList.add('opacity-70', 'pointer-events-none');

                    try {
                        const response = await fetch(wishlistForm.action, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            body: new FormData(wishlistForm),
                        });
                        const data = await response.json();
                        if (!response.ok || !data.success) {
                            throw new Error(data.message || 'Failed to update wishlist');
                        }

                        const isAdded = Boolean(data.added);
                        button.dataset.wishlisted = isAdded ? '1' : '0';
                        icon.classList.toggle('text-red-500', isAdded);
                        icon.classList.toggle('fill-red-500', isAdded);
                        icon.classList.toggle('text-gray-900', !isAdded);
                        icon.setAttribute('fill', isAdded ? 'currentColor' : 'none');

                        showActionToast(data.message || 'Wishlist updated.', 'success');
                    } catch (error) {
                        showActionToast('Failed to update wishlist.', 'error');
                    } finally {
                        button.classList.remove('opacity-70', 'pointer-events-none');
                        button.dataset.loading = '0';
                    }
                });
            }

            const existingToast = document.getElementById('cartToast');
            if (existingToast) {
                requestAnimationFrame(() => {
                    existingToast.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
                });
                window.setTimeout(() => {
                    existingToast.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
                }, 2600);
            }
        });
    </script>

    @if(session('success'))
        <div id="cartToast" class="fixed top-24 right-4 sm:right-6 z-50 max-w-sm w-[calc(100vw-2rem)] sm:w-auto px-4 py-3 bg-green-500 text-white rounded-xl shadow-lg flex items-center gap-3 opacity-0 translate-y-2 pointer-events-none transition-all duration-300">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @include('partials.toast')
    @include('partials.footer')
</body>
</html>






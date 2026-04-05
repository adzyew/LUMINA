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

    <main class="grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10 lg:py-12 w-full">
        <a href="{{ url()->previous() ?? route('products.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-amber-600 text-sm font-medium mb-10 transition-colors group">
            <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Collection
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">

            {{-- LEFT COL: Product Images (Gallery) --}}
            @php
                $gallery = collect($product->images ?? [])->pluck('image_url')->filter()->values();
                if ($gallery->isEmpty() && ($product->image_url ?? null)) {
                    $gallery = collect([$product->image_url]);
                }
                $mainImg = $gallery->first();
            @endphp

            <div class="lg:sticky lg:top-24 h-max">
                <div class="relative bg-white rounded-2xl border border-amber-200/50 shadow-xl overflow-hidden flex items-center justify-center p-4 aspect-[4/5] sm:aspect-square group">
                    @if($mainImg)
                        <img id="mainProductImage" src="{{ $mainImg }}" alt="{{ $product->name }}" class="w-full h-full object-contain mx-auto transition-transform duration-700 group-hover:scale-105">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-600">
                            <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif

                    @if($product->is_featured ?? false)
                        <span class="absolute top-5 left-5 px-4 py-1.5 bg-amber-300 text-black text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">Featured</span>
                    @endif

                    @auth
                        @php $isWishlisted = auth()->user()->wishlist()->where('product_id', $product->id)->exists(); @endphp
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="absolute top-5 right-5 z-10">
                            @csrf
                            <button type="submit" class="w-11 h-11 bg-white rounded-full flex items-center justify-center shadow-lg hover:scale-110 transition-transform duration-200">
                                <svg class="w-6 h-6 transition-colors duration-200 {{ $isWishlisted ? 'text-red-500 fill-red-500' : 'text-gray-900 hover:text-red-500' }}" fill="{{ $isWishlisted ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                            </button>
                        </form>
                    @endauth
                </div>

                @if($gallery->count() > 1)
                    <div class="mt-4 grid grid-cols-5 gap-3 overflow-x-auto pb-2">
                        @foreach($gallery as $img)
                            <button type="button" onclick="setMainProductImage('{{ $img }}')" class="bg-white/80 rounded-2xl border border-gray-200 hover:border-amber-400/50 overflow-hidden aspect-square transition-colors">
                                <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- RIGHT COL: Product Details --}}
            <div class="flex flex-col pt-2">
                
                <div class="flex flex-wrap gap-2 mb-4">
                    <span class="px-4 py-1.5 rounded-xl border border-amber-600 text-xs font-semibold text-amber-600 uppercase tracking-widest">
                        {{ $product->category ?? 'Jewelry' }}
                    </span>
                    @php $inStock = ($product->stock_quantity ?? 0) > 0; @endphp
                    <span class="px-4 py-1.5 rounded-xl text-xs font-semibold border uppercase tracking-widest {{ $inStock ? ' text-green-400 border-green-400' : 'bg-red-500/10 text-red-400 border-red-400' }}">
                        {{ $inStock ? 'In Stock' : 'Sold Out' }}
                    </span>
                </div>


                <div class="mb-10">
                    <h1 class="text-4xl sm:text-5xl font-playfair font-bold text-black mb-4 leading-tight">{{ $product->name }}</h1>
                    <p class="text-4xl font-black text-amber-400 mb-6">₱{{ number_format($product->price ?? 0, 2) }}</p>
                    @if($product->description ?? null)
                        @php
                            $features = explode('•', $product->description);
                        @endphp
                        <ul class="text-black leading-relaxed text-lg list-disc pl-6">
                            @foreach($features as $feature)
                                @if(trim($feature) !== '')
                                    <li>{{ trim($feature) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @endif
                </div>

                <hr class="border-gray-200 mb-8">

                <div class="flex flex-col gap-4 mb-8">
                    @if($inStock)
                        <a href="{{ route('cart.add', $product->id) }}" class="w-full h-14 bg-amber-300 text-black font-bold text-lg rounded-xl hover:bg-amber-400 transition-all duration-300 flex items-center justify-center gap-2 shadow-[0_0_20px_rgba(252,211,77,0.15)] hover:shadow-[0_0_30px_rgba(252,211,77,0.3)] hover:-translate-y-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                        </svg>
                            Add to Cart
                        </a>
                    @else
                        <span class="w-full h-14 bg-gray-200 text-gray-500 font-bold text-lg rounded-full flex items-center justify-center cursor-not-allowed">
                            Currently Unavailable
                        </span>
                    @endif

                    <a href="{{ route('products.index') }}" class="w-full h-14 bg-white/70 text-black font-semibold rounded-xl hover:bg-white border border-gray-200 transition-colors flex items-center justify-center">
                        Continue Shopping
                    </a>
                </div>

                <div class="mt-auto bg-white rounded-xl p-6 border border-gray-100 shadow-sm space-y-2">
                    <div class="flex items-center gap-4 text-black">
                        <svg class="w-6 h-6 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z"></path></svg>
                        <span class="text-sm">Authentic Lumina Quality</span>
                    </div>
                    <div class="flex items-center gap-4 text-black">
                        <svg class="w-6 h-6 text-amber-300 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12"></path></svg>
                        <span class="text-sm">Secure & Insured Shipping</span>
                    </div>
                </div>

            </div>
        </div>

        {{-- Reviews Section (Refined) --}}
        <div class="mt-14 bg-white rounded-xl p-8 sm:p-10 border border-gray-100 shadow-md">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10">
                <div>
                    <h2 class="text-3xl font-playfair font-bold text-gray-900 mb-3">Customer Reviews</h2>
                    @if($averageRating ?? null)
                        <div class="flex items-center gap-3">
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-6 h-6 {{ $i <= round($averageRating) ? 'text-amber-400 fill-amber-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <span class="text-gray-600 font-medium">{{ number_format($averageRating, 1) }} out of 5</span>
                            <span class="text-gray-500">({{ $reviews->total() }} reviews)</span>
                        </div>
                    @else
                        <p class="text-gray-500">No reviews yet. Be the first to review!</p>
                    @endif
                </div>
                @auth
                    <button onclick="document.getElementById('reviewForm').classList.toggle('hidden')" class="w-full sm:w-auto px-8 py-3.5 bg-amber-50 text-amber-700 font-bold rounded-full hover:bg-amber-100 border border-amber-200 transition-colors text-center">
                        Write a Review
                    </button>
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-3.5 bg-amber-50 text-amber-700 font-bold rounded-full hover:bg-amber-100 border border-amber-200 transition-colors text-center">
                        Login to Review
                    </a>
                @endauth
            </div>

            {{-- Review Form --}}
            @auth
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
                                <p class="text-xl font-bold text-amber-500">₱{{ number_format($relatedProduct->price ?? 0, 2) }}</p>
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
            const toast = document.getElementById('cartToast');
            if (!toast) {
                return;
            }

            requestAnimationFrame(() => {
                toast.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
            });

            window.setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
            }, 2600);
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

    @include('partials.footer')
</body>
</html>

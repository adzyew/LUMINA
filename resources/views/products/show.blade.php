<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    <title>{{ $product->name }} | Lumina</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white font-sans antialiased min-h-screen flex flex-col transition-colors">

    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-black/40 backdrop-blur-sm"></div>
    </div>

    @include('partials.navbar')

    <main class="grow container mx-auto px-4 sm:px-6 py-12">
        <a href="{{ url()->previous() ?? route('products.index') }}" class="inline-flex items-center gap-2 text-gray-400 hover:text-amber-300 text-sm font-medium mb-8 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Collection
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            {{-- Product Images (Gallery) --}}
            @php
                $gallery = collect($product->images ?? [])->pluck('image_url')->filter()->values();
                if ($gallery->isEmpty() && ($product->image_url ?? null)) {
                    $gallery = collect([$product->image_url]);
                }
                $mainImg = $gallery->first();
            @endphp
            <div>
                <div class="relative bg-gray-900/50 rounded-2xl overflow-hidden border border-white/10 aspect-square flex items-center justify-center">
                    @if($mainImg)
                        <img id="mainProductImage" src="{{ $mainImg }}" alt="{{ $product->name }}" class="w-full h-full object-contain p-4">
                    @else
                        <div class="w-full h-full flex items-center justify-center text-gray-600">
                            <svg class="w-32 h-32" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                    @endif
                    @if($product->is_featured ?? false)
                        <span class="absolute top-4 left-4 px-3 py-1 bg-amber-300 text-black text-xs font-bold rounded-full">Featured</span>
                    @endif
                </div>

                @if($gallery->count() > 1)
                    <div class="mt-4 grid grid-cols-5 gap-3">
                        @foreach($gallery as $img)
                            <button type="button" onclick="setMainProductImage('{{ $img }}')" class="bg-white/5 rounded-xl border border-white/10 hover:border-amber-300/40 overflow-hidden aspect-square">
                                <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Product Details --}}
            <div>
                <p class="text-amber-300 text-sm uppercase tracking-widest mb-2">{{ ucfirst($product->category ?? 'Jewelry') }}</p>
                <h1 class="text-3xl sm:text-4xl font-playfair font-bold text-white mb-4">{{ $product->name }}</h1>
                <p class="text-4xl font-bold text-amber-300 mb-6">₱{{ number_format($product->price ?? 0, 2) }}</p>

                @if($product->description ?? null)
                    <div class="mb-6">
                        <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider mb-2">Description</h3>
                        <p class="text-gray-300 leading-relaxed">{{ $product->description }}</p>
                    </div>
                @endif

                <div class="flex flex-wrap gap-4 mb-8">
                    <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-xl border border-white/10">
                        <span class="text-gray-400 text-sm">Category</span>
                        <span class="text-white font-medium">{{ ucfirst($product->category ?? '—') }}</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 bg-white/5 rounded-xl border border-white/10">
                        <span class="text-gray-400 text-sm">Availability</span>
                        @php $inStock = ($product->stock_quantity ?? 0) > 0; @endphp
                        <span class="{{ $inStock ? 'text-green-400' : 'text-red-400' }} font-medium">{{ $inStock ? 'In Stock' : 'Sold Out' }}</span>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 mb-6">
                    @if(($product->stock_quantity ?? 0) > 0)
                        <a href="{{ route('cart.add', $product->id) }}" class="flex-1 px-8 py-4 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            Add to Cart
                        </a>
                    @else
                        <span class="flex-1 px-8 py-4 bg-gray-700 text-gray-400 font-bold rounded-xl text-center">Sold Out</span>
                    @endif
                    @auth
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="px-6 py-4 bg-white/5 text-white font-semibold rounded-xl hover:bg-white/10 border border-white/10 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5 {{ $isWishlisted ?? false ? 'fill-red-500' : '' }}" fill="{{ $isWishlisted ?? false ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                {{ $isWishlisted ?? false ? 'Wishlisted' : 'Wishlist' }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-6 py-4 bg-white/5 text-white font-semibold rounded-xl hover:bg-white/10 border border-white/10 text-center transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            Wishlist
                        </a>
                    @endauth
                </div>
                <a href="{{ route('products.index') }}" class="block w-full px-8 py-4 bg-white/5 text-white font-semibold rounded-xl hover:bg-white/10 border border-white/10 text-center transition-colors">
                    Continue Shopping
                </a>

                @if(session('success'))
                    <p class="mt-4 text-green-400 text-sm">{{ session('success') }}</p>
                @endif
            </div>
        </div>

        {{-- Reviews Section --}}
        <div class="mt-16 bg-gray-900/50 rounded-2xl p-8 border border-white/10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div>
                    <h2 class="text-2xl font-playfair font-bold text-white mb-2">Customer Reviews</h2>
                    @if($averageRating ?? null)
                        <div class="flex items-center gap-2">
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-5 h-5 {{ $i <= round($averageRating) ? 'text-amber-400 fill-amber-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                            <span class="text-gray-400 text-sm">{{ number_format($averageRating, 1) }} out of 5</span>
                            <span class="text-gray-500 text-sm">({{ $reviews->total() }} reviews)</span>
                        </div>
                    @else
                        <p class="text-gray-400 text-sm">No reviews yet. Be the first to review!</p>
                    @endif
                </div>
                @auth
                    <button onclick="document.getElementById('reviewForm').classList.toggle('hidden')" class="px-6 py-3 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors">
                        Write a Review
                    </button>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-3 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors">
                        Login to Review
                    </a>
                @endauth
            </div>

            {{-- Review Form --}}
            @auth
            <form id="reviewForm" action="{{ route('reviews.store', $product) }}" method="POST" class="hidden mb-8 p-6 bg-white/5 rounded-xl border border-white/10">
                @csrf
                <h3 class="text-lg font-bold text-white mb-4">Write Your Review</h3>
                <div class="mb-4">
                    <label class="block text-gray-300 text-sm mb-2">Rating</label>
                    <div class="flex gap-2" id="ratingStars">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" onclick="setRating({{ $i }})" class="star-rating w-8 h-8 text-gray-400 hover:text-amber-400 transition-colors" data-rating="{{ $i }}">
                                <svg class="w-full h-full" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                            </button>
                        @endfor
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="5" required>
                </div>
                <div class="mb-4">
                    <label for="comment" class="block text-gray-300 text-sm mb-2">Your Review</label>
                    <textarea name="comment" id="comment" rows="4" class="w-full px-4 py-3 bg-black/50 border border-white/10 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-amber-300" placeholder="Share your thoughts about this product..."></textarea>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="px-6 py-3 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors">Submit Review</button>
                    <button type="button" onclick="document.getElementById('reviewForm').classList.add('hidden')" class="px-6 py-3 bg-white/5 text-white font-semibold rounded-xl hover:bg-white/10 border border-white/10 transition-colors">Cancel</button>
                </div>
            </form>
            @endauth

            {{-- Reviews List --}}
            <div class="space-y-6">
                @forelse($reviews ?? [] as $review)
                    <div class="pb-6 border-b border-white/10 last:border-0">
                        <div class="flex items-start justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-amber-300 rounded-full flex items-center justify-center text-black font-bold">
                                    {{ strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-white">{{ $review->user->name ?? 'Anonymous' }}</p>
                                    <p class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="text-gray-300 leading-relaxed">{{ $review->comment }}</p>
                        @endif
                    </div>
                @empty
                    <p class="text-gray-400 text-center py-8">No reviews yet. Be the first to review this product!</p>
                @endforelse
            </div>

            @if(isset($reviews) && $reviews->hasPages())
                <div class="mt-8">{{ $reviews->links() }}</div>
            @endif
        </div>
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
    </script>

    @include('partials.footer')
</body>
</html>

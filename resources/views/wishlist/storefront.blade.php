<!doctype html>
<html>
<head>
    @include('partials.favicon')
    <title>My Wishlist | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-stone-100 text-gray-900 font-sans antialiased flex flex-col min-h-screen">

    @include('partials.navbar')

    <div class="grow container mx-auto px-4 sm:px-6 py-24">
        <h1 class="text-3xl sm:text-4xl font-playfair font-bold text-amber-600 mb-8 text-center sm:text-left">My Wishlist</h1>

        @if($wishlistItems->count() > 0)
            <div class="flex flex-col lg:flex-row gap-8">
                <div class="lg:w-3/4">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-lg">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left min-w-[620px]">
                                <thead class="bg-amber-50 text-gray-900 text-lg font-bold tracking-wider border-b border-amber-200">
                                    <tr>
                                        <th class="p-4 sm:p-6">Product</th>
                                        <th class="p-4 sm:p-6 hidden sm:table-cell">Price</th>
                                        <th class="p-4 sm:p-6 hidden md:table-cell">Stock</th>
                                        <th class="p-4 sm:p-6 text-right">Action</th>
                                        <th class="p-4 sm:p-6"></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                    @foreach($wishlistItems as $item)
                                        @php $product = $item->product; @endphp
                                        @if($product)
                                            <tr class="bg-white hover:bg-amber-50/50 transition-colors">
                                                <td class="p-4 sm:p-6">
                                                    <div class="flex items-center gap-4">
                                                        <a href="{{ route('products.show', $product) }}" class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-lg overflow-hidden shrink-0 border border-gray-200">
                                                            @if($product->image_url)
                                                                <img src="{{ $product->image_url }}" class="w-full h-full object-cover" alt="{{ $product->name }}">
                                                            @else
                                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                                                                </div>
                                                            @endif
                                                        </a>
                                                        <div>
                                                            <a href="{{ route('products.show', $product) }}" class="font-bold text-gray-900 text-sm sm:text-base hover:text-amber-600 transition-colors">
                                                                {{ $product->name }}
                                                            </a>
                                                            <p class="text-gray-500 text-xs sm:hidden">₱{{ number_format($product->price ?? 0, 2) }}</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="p-4 sm:p-6 hidden sm:table-cell text-green-600 font-medium">
                                                    ₱{{ number_format($product->price ?? 0, 2) }}
                                                </td>
                                                <td class="p-4 sm:p-6 hidden md:table-cell">
                                                    <span class="text-sm font-semibold {{ ($product->stock_quantity ?? 0) > 0 ? 'text-green-600' : 'text-red-500' }}">
                                                        {{ ($product->stock_quantity ?? 0) > 0 ? 'In Stock' : 'Sold Out' }}
                                                    </span>
                                                </td>
                                                <td class="p-4 sm:p-6 text-right">
                                                    @if(($product->stock_quantity ?? 0) > 0)
                                                        <a href="{{ route('cart.add', $product->id) }}" class="inline-flex items-center px-4 py-2 bg-amber-300 text-black font-semibold rounded-lg hover:bg-amber-400 transition-colors">
                                                            Add to Cart
                                                        </a>
                                                    @else
                                                        <span class="inline-flex items-center px-4 py-2 bg-gray-200 text-gray-500 font-semibold rounded-lg">
                                                            Unavailable
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="p-4 sm:p-6 text-right">
                                                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors" title="Remove from wishlist">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6">
                        {{ $wishlistItems->links() }}
                    </div>
                </div>

                <div class="lg:w-1/4">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-md sticky top-24">
                        <h3 class="font-playfair font-bold text-xl text-gray-900 mb-6">Wishlist Summary</h3>

                        <div class="space-y-3 text-sm border-b border-gray-200 pb-6 mb-6">
                            <div class="flex justify-between text-gray-500">
                                <span>Saved Items</span>
                                <span class="text-gray-900 font-semibold">{{ $wishlistItems->total() }}</span>
                            </div>
                            <div class="flex justify-between text-gray-500">
                                <span>Current Page</span>
                                <span class="text-gray-900 font-semibold">{{ $wishlistItems->count() }}</span>
                            </div>
                        </div>

                        <a href="{{ route('products.index') }}" class="block w-full py-4 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-all shadow-lg shadow-amber-300/20 text-center">
                            Continue Shopping
                        </a>

                        <a href="{{ route('cart.index') }}" class="block text-center mt-4 text-sm text-amber-600 hover:text-amber-700 transition-colors">
                            View Cart
                        </a>
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-6 text-amber-400">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Your wishlist is empty</h2>
                <p class="text-gray-500 mb-8">Save your favorite pieces and come back anytime.</p>
                <a href="{{ route('products.index') }}" class="px-8 py-3 bg-amber-300 text-black font-bold rounded-full hover:bg-amber-400 transition-all">
                    Browse Collection
                </a>
            </div>
        @endif
    </div>

    @include('partials.footer')

</body>
</html>

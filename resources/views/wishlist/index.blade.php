@extends('layouts.customer')

@section('title', 'My Wishlist | Lumina')

@section('content')
    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-stone-400/25 backdrop-blur-[2px]"></div>
        <div class="absolute inset-0 bg-linear-to-b from-stone-200/70 via-stone-100/50 to-stone-200/80"></div>
    </div>

    <section class="relative min-h-48 pt-12 flex items-center justify-center">
        <div class="container mx-auto px-4 sm:px-6 lg:px-10 max-w-7xl text-center">
            <h1 class="text-4xl sm:text-5xl font-playfair font-bold leading-tight">
                My <span class="text-amber-600">Wishlist</span>
            </h1>
            <p class="mt-4 text-gray-600">Your saved favorites</p>
        </div>
    </section>

    <main class="container mx-auto px-4 sm:px-6 lg:px-10 pb-16 max-w-7xl">
        @if($wishlistItems->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($wishlistItems as $item)
                    <div class="group bg-white rounded-2xl overflow-hidden border border-amber-100/60 hover:border-amber-200 shadow-md transition-all duration-300 hover:-translate-y-2 hover:shadow-xl hover:shadow-amber-200/40 relative">
                        <form action="{{ route('wishlist.toggle', $item->product) }}" method="POST" class="absolute top-3 right-3 z-10">
                            @csrf
                            <button type="submit" class="w-10 h-10 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-full flex items-center justify-center hover:bg-red-50 hover:border-red-200 transition-colors">
                                <svg class="w-5 h-5 text-red-500 fill-red-500" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            </button>
                        </form>
                        <a href="{{ route('products.show', $item->product) }}" class="block">
                            <div class="relative h-56 bg-amber-50/50 flex items-center justify-center overflow-hidden">
                                @if($item->product->image_url ?? null)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-600">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="p-5">
                                <p class="text-xs text-amber-600 uppercase tracking-widest mb-2">{{ ucfirst($item->product->category ?? 'Jewelry') }}</p>
                                <h3 class="text-lg font-playfair font-bold text-gray-900 mb-2 truncate" title="{{ $item->product->name }}">{{ $item->product->name }}</h3>
                                <div class="flex justify-between items-center">
                                    <span class="text-xl font-bold text-amber-600">₱{{ number_format($item->product->price ?? 0, 2) }}</span>
                                    <span class="text-xs {{ ($item->product->stock_quantity ?? 0) > 0 ? 'text-green-600' : 'text-red-500' }}">
                                        {{ ($item->product->stock_quantity ?? 0) > 0 ? 'In Stock' : 'Sold Out' }}
                                    </span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="mt-12 flex justify-center">
                {{ $wishlistItems->links() }}
            </div>
        @else
            <div class="text-center py-20">
                <svg class="w-24 h-24 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                </svg>
                <p class="text-gray-500 text-lg mb-4">Your wishlist is empty</p>
                <a href="{{ route('products.index') }}" class="text-amber-600 hover:text-amber-700 font-semibold">Start adding items to your wishlist</a>
            </div>
        @endif
    </main>
@endsection

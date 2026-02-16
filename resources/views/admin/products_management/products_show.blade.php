@extends('admin.admin_layout')

@section('title', "{$product->name} | Lumina Admin")

@section('content')
<div class="max-w-4xl w-full">
    <header class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-black">Product Details</h1>
            <p class="text-gray-600 text-sm mt-1">{{ $product->name }}</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.products.edit', $product) }}" class="px-5 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
                Edit
            </a>
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-white/5 text-gray-300 font-semibold rounded-lg hover:bg-white/10 border border-white/10 transition-colors">
                Back
            </a>
        </div>
    </header>

    <div class="bg-gray-900 border border-white/5 rounded-2xl overflow-hidden">
        <div class="p-6 sm:p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
        {{-- Product Image --}}
        <div>
            @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-80 object-cover rounded-lg border border-white/10 cursor-pointer" onclick="openImageModal()">
            @else
                    <div class="w-full h-80 bg-gray-800 flex items-center justify-center rounded-lg border border-white/10">
                    <span class="text-gray-500">No Image</span>
                </div>
            @endif
        </div>

        {{-- Product Info --}}
            <div class="space-y-6">
            <div>
                    <p class="text-sm text-gray-500 mb-1">Name</p>
                    <p class="text-xl font-bold text-white">{{ $product->name }}</p>
            </div>
            <div>
                    <p class="text-sm text-gray-500 mb-1">Price</p>
                    <p class="text-xl font-bold text-amber-300">₱{{ number_format($product->price, 2) }}</p>
            </div>
            <div>
                    <p class="text-sm text-gray-500 mb-1">Stock</p>
                    <p class="text-lg font-semibold text-white">{{ $product->stock_quantity ?? 0 }}</p>
            </div>
            <div>
                    <p class="text-sm text-gray-500 mb-1">Status</p>
                    @php $inStock = ($product->stock_quantity ?? 0) > 0; @endphp
                    <span class="inline-block px-3 py-1 rounded-full text-sm font-medium {{ $inStock ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                        {{ $inStock ? 'In Stock' : 'Out of Stock' }}
                </span>
            </div>
            <div>
                    <p class="text-sm text-gray-500 mb-1">Description</p>
                    <p class="text-gray-300 leading-relaxed">
                    {{ $product->description ?? 'No description available.' }}
                </p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Image zoom modal --}}
<div id="imageModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="absolute inset-0" onclick="closeImageModal()"></div>
    <div class="relative max-w-4xl max-h-[90vh] p-4 z-10">
        <img src="{{ $product->image_url }}" class="rounded-lg shadow-2xl max-h-[90vh] object-contain" alt="{{ $product->name }}">
        <button onclick="closeImageModal()" class="absolute -top-2 -right-2 bg-gray-900 text-white w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-800 transition-colors text-xl">
            &times;
        </button>
    </div>
</div>

<script>
    function openImageModal() {
        document.getElementById('imageModal').classList.remove('hidden');
        document.getElementById('imageModal').classList.add('flex');
    }
    function closeImageModal() {
        document.getElementById('imageModal').classList.add('hidden');
        document.getElementById('imageModal').classList.remove('flex');
    }
</script>
@endsection

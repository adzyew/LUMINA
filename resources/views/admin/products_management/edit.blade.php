@extends('admin.admin_layout')

@section('title', "Edit Product: {$product->name} | Lumina Admin")

@section('content')
<div class="max-w-4xl w-full">
    <header class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-black">Edit Product</h1>
                <p class="text-gray-600 text-sm mt-1">{{ $product->name }}</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 hover:text-black transition-colors">
                &larr; Back to Products
            </a>
        </div>
    </header>

    @if ($errors->any())
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-gray-900 border border-white/5 rounded-2xl p-6 sm:p-8">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Product Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
        </div>
        <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Category</label>
                    <select name="category" class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
                        <option value="Rings" {{ old('category', $product->category) == 'Rings' ? 'selected' : '' }}>Rings</option>
                        <option value="Necklaces" {{ old('category', $product->category) == 'Necklaces' ? 'selected' : '' }}>Necklaces</option>
                        <option value="Earrings" {{ old('category', $product->category) == 'Earrings' ? 'selected' : '' }}>Earrings</option>
                        <option value="Bracelets" {{ old('category', $product->category) == 'Bracelets' ? 'selected' : '' }}>Bracelets</option>
                        <option value="Watches" {{ old('category', $product->category) == 'Watches' ? 'selected' : '' }}>Watches</option>
                    </select>
                </div>
        </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Price (₱)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
        </div>
        <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
        </div>
        </div>

        <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">{{ old('description', $product->description) }}</textarea>
        </div>

        <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Current Image</label>
            @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-40 rounded-lg border border-white/10 object-cover">
            @else
                <p class="text-gray-500">No image uploaded</p>
            @endif
        </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Change Image</label>
                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image. Max 5MB.</p>
            </div>

        <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Add More Gallery Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">You can upload multiple photos. Max 5MB per image.</p>
        </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-amber-300 focus:ring-amber-300">
                <label for="is_featured" class="text-sm text-gray-300">Featured Product</label>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
                Update Product
            </button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 bg-white/5 text-gray-300 font-semibold rounded-lg hover:bg-white/10 border border-white/10 transition-colors">
                    Cancel
                </a>
        </div>
    </form>
    </div>
</div>
@endsection

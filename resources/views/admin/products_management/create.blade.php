@extends('admin.admin_layout')

@section('title', 'Add Product | Lumina Admin')

@section('content')
<div class="max-w-4xl w-full">
    <header class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-black">Add New Product</h1>
                <p class="text-gray-600 text-sm mt-1">Add items to your collection with images and details.</p>
            </div>
            <a href="{{ route('admin.admin_dashboard') }}" class="text-sm text-gray-600 hover:text-black transition-colors">
                &larr; Back to Dashboard
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
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Product Name</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none" placeholder="e.g. Gold Necklace">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Category</label>
                    <select name="category" class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
                        <option value="Rings" {{ old('category') == 'Rings' ? 'selected' : '' }}>Rings</option>
                        <option value="Necklaces" {{ old('category') == 'Necklaces' ? 'selected' : '' }}>Necklaces</option>
                        <option value="Earrings" {{ old('category') == 'Earrings' ? 'selected' : '' }}>Earrings</option>
                        <option value="Bracelets" {{ old('category') == 'Bracelets' ? 'selected' : '' }}>Bracelets</option>
                        <option value="Watches" {{ old('category') == 'Watches' ? 'selected' : '' }}>Watches</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Price (₱)</label>
                    <input type="number" name="price" step="0.01" required value="{{ old('price') }}" class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 10) }}" class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full bg-gray-800 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none" placeholder="Product details...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Product Images (Gallery)</label>
                <div class="border-2 border-dashed border-white/10 rounded-lg p-8 text-center hover:border-amber-300/50 transition-colors bg-gray-800/50">
                    <input type="file" name="images[]" multiple required accept="image/*" onchange="previewImages(event)" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                    @error('images')
                        <div class="text-red-400 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                    @error('images.*')
                        <div class="text-red-400 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                    <div id="previews" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3 hidden"></div>
                    <p class="text-xs text-gray-500 mt-2">You can upload multiple photos. Max 5MB per image.</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-600 bg-gray-800 text-amber-300 focus:ring-amber-300">
                <label for="is_featured" class="text-sm text-gray-300">Mark as Featured Product</label>
            </div>

            <button type="submit" class="w-full py-4 bg-amber-300 text-black font-bold text-lg rounded-lg hover:bg-amber-400 transition-colors">
                Create Product
            </button>
        </form>
    </div>
</div>

<script>
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
    const maxSize = 5 * 1024 * 1024; // 5MB

    function previewImages(event) {
        const input = event.target;
        const container = document.getElementById('previews');

        container.innerHTML = '';

        if (!input.files || input.files.length === 0) {
            container.classList.add('hidden');
            return;
        }

        for (const file of input.files) {
        if (!allowedTypes.includes(file.type)) {
                alert('Only JPG, PNG, GIF, or WEBP images are allowed.');
            input.value = '';
                container.classList.add('hidden');
            return;
        }
        if (file.size > maxSize) {
                alert('Each image must be smaller than 5MB.');
            input.value = '';
                container.classList.add('hidden');
            return;
        }
        }

        container.classList.remove('hidden');

        Array.from(input.files).forEach((file) => {
            const url = URL.createObjectURL(file);
            const img = document.createElement('img');
            img.src = url;
            img.className = 'w-full h-24 object-cover rounded-lg border border-white/10';
            img.onload = () => URL.revokeObjectURL(url);
            container.appendChild(img);
        });
    }
</script>
@endsection

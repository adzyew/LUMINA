@extends('admin.admin_layout')

@section('title', 'Add Product | Lumina Admin')

@section('content')
<div class="max-w-4xl w-full align-middle mx-auto py-12">
    <header class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Add New Product</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Add items to your collection with images and details.</p>
            </div>
            <a href="{{ route('admin.admin_dashboard') }}" class="text-lg text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors">
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

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-4 sm:p-8 align-middle">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Product Name</label>
                    <input type="text" name="name" required value="{{ old('name') }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-300 outline-none" placeholder="e.g. Gold Necklace">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Category</label>
                    <select name="category" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-300 outline-none">
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
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Price (₱)</label>
                    <input type="number" name="price" step="0.01" required value="{{ old('price') }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-300 outline-none" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', 10) }}" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-300 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-300 outline-none" placeholder="Product details...">{{ old('description') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Product Images (Gallery)</label>
                <div class="border-2 border-dashed border-gray-300 dark:border-white/10 rounded-lg p-8 text-center hover:border-amber-300/50 transition-colors bg-gray-50 dark:bg-gray-800/50">
                    <input type="file" name="image" required accept="image/*" onchange="previewImages(event)" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                    @error('image')
                        <div class="text-red-400 mt-1 text-sm">{{ $message }}</div>
                    @enderror
                    <div id="previews" class="mt-4 grid grid-cols-2 sm:grid-cols-4 gap-3 hidden"></div>
                    <p class="text-xs text-gray-500 mt-2">Upload photo. Max 10MB per image.</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured') ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-800 text-amber-300 focus:ring-amber-300">
                <label for="is_featured" class="text-sm text-gray-700 dark:text-gray-300">Mark as Featured Product</label>
            </div>

            <button type="submit" class="w-full py-4 bg-amber-300 text-black font-bold text-lg rounded-lg hover:bg-amber-400 transition-colors">
                Create Product
            </button>
        </form>
    </div>
</div>

<script>
    const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
const maxSize = 10 * 1024 * 1024;

function previewImages(event) {
        const input = event.target;
        const container = document.getElementById('previews');
        container.innerHTML = '';

        if (!input.files || input.files.length === 0) {
            container.classList.add('hidden');
            return;
        }

        const file = input.files[0]; // single file only

        if (!allowedTypes.includes(file.type)) {
            alert(`"${file.name}" is not allowed. Only JPG, PNG, GIF, or WEBP images are accepted.`);
            input.value = '';
            container.classList.add('hidden');
            return;
        }

        if (file.size > maxSize) {
            alert(`"${file.name}" is too large. Max 10MB allowed.`);
            input.value = '';
            container.classList.add('hidden');
            return;
        }

        const url = URL.createObjectURL(file);
        const img = document.createElement('img');
        img.src = url;
        img.className = 'w-full h-40 object-cover rounded-lg border border-gray-200 dark:border-white/10';
        img.onload = () => URL.revokeObjectURL(url);
        container.classList.remove('hidden');
        container.appendChild(img);
    }
</script>
@endsection

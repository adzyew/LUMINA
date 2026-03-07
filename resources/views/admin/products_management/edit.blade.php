@extends('admin.admin_layout')

@section('title', "Edit Product: {$product->name} | Lumina Admin")

@section('content')
<div class="max-w-4xl w-full">
    <header class="mb-8">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Edit Product</h1>
                <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">{{ $product->name }}</p>
            </div>
            <a href="{{ route('admin.products.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors">
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

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 sm:p-8">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Product Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-300 outline-none">
        </div>
        <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Category</label>
                    <select name="category" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-300 outline-none">
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
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Price (₱)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-300 outline-none">
        </div>
        <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-300 outline-none">
        </div>
        </div>

        <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-300 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-300 outline-none">{{ old('description', $product->description) }}</textarea>
        </div>

        <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Current Image</label>
            @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-40 rounded-lg border border-gray-200 dark:border-white/10 object-cover">
            @else
                <p class="text-gray-500">No image uploaded</p>
            @endif
        </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Change Image</label>
                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image. Max 5MB.</p>
            </div>

        <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Add More Gallery Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">You can upload multiple photos. Max 5MB per image.</p>
        </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-400 dark:border-gray-600 bg-white dark:bg-gray-800 text-amber-300 focus:ring-amber-300">
                <label for="is_featured" class="text-sm text-gray-700 dark:text-gray-300">Featured Product</label>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
                Update Product
            </button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 bg-gray-100 dark:bg-white/5 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-300 dark:border-white/10 transition-colors">
                    Cancel
                </a>
        </div>
    </form>
    <div class="mt-12 bg-gray-50 dark:bg-gray-900 rounded-xl p-6 border border-gray-200 dark:border-white/10">
    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Inventory Movement History
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 dark:border-white/10 text-gray-500 dark:text-gray-400 text-sm tracking-wider uppercase">
                    <th class="py-3 font-medium">Date</th>
                    <th class="py-3 font-medium">Action By</th>
                    <th class="py-3 font-medium">Reason</th>
                    <th class="py-3 font-medium">Previous</th>
                    <th class="py-3 font-medium text-center">Change</th>
                    <th class="py-3 font-medium text-right">New Stock</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 dark:text-gray-300">
                @forelse($product->inventoryLogs as $log)
                    <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                        <td class="py-4 text-sm">{{ $log->created_at->format('M d, Y h:i A') }}</td>
                        <td class="py-4 text-sm">{{ $log->user->name ?? 'System' }}</td>
                        <td class="py-4 text-sm">
                            {{ $log->reason }}
                            @if($log->reference_id)
                                <span class="text-gray-500 text-xs ml-1">({{ $log->reference_id }})</span>
                            @endif
                        </td>
                        <td class="py-4">{{ $log->previous_stock }}</td>
                        <td class="py-4 text-center">
                            <span class="px-2 py-1 rounded-md text-xs font-bold {{ $log->quantity_changed > 0 ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400' }}">
                                {{ $log->quantity_changed > 0 ? '+' : '' }}{{ $log->quantity_changed }}
                            </span>
                        </td>
                        <td class="py-4 text-right font-bold text-gray-900 dark:text-white">{{ $log->new_stock }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-gray-500">No inventory movements recorded yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
    </div>
</div>
@endsection

@extends('admin.admin_layout')

@section('title', "Edit Product: {$product->name} | Lumina Admin")

@section('content')
<div class="max-w-4xl w-full">
    <header class="mb-8">
    @include('partials.favicon')
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-gray-900">Edit Product</h1>
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

    <div class="bg-white border border-gray-200 rounded-2xl p-6 sm:p-8">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Product Name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 outline-none">
        </div>
        <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Category</label>
                    <select name="category" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 outline-none">
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
                    <label class="block text-sm font-medium text-gray-500 mb-2">Price (₱)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 outline-none">
        </div>
        <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 outline-none">
        </div>
        </div>

        <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 outline-none">{{ old('description', $product->description) }}</textarea>
        </div>

        @php
            $specifications = is_array($product->specifications) ? $product->specifications : [];
            $sizeSpec = old('size_spec', (string) ($specifications['size'] ?? ''));
            $specDetails = old('specification_details', implode("\n", collect($specifications['details'] ?? [])->filter()->all()));
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Product Size / Fit</label>
                <input type="text" name="size_spec" value="{{ $sizeSpec }}" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 outline-none" placeholder="e.g. Adjustable ring, 16-18cm, 40mm case">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Specification Details</label>
                <textarea name="specification_details" rows="3" class="w-full bg-gray-50 border border-gray-300 rounded-lg p-3 text-gray-900 focus:border-amber-300 outline-none" placeholder="One detail per line (e.g. Material: Stainless Steel)">{{ $specDetails }}</textarea>
            </div>
        </div>

        <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Current Image</label>
            @if ($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="h-40 rounded-lg border border-gray-200 object-cover">
            @else
                <p class="text-gray-500">No image uploaded</p>
            @endif
        </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Change Image</label>
                <input type="file" name="image" accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">Leave empty to keep current image. Max 5MB.</p>
            </div>

        <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Add More Gallery Images</label>
                <input type="file" name="images[]" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                <p class="text-xs text-gray-500 mt-1">You can upload multiple photos. Max 5MB per image.</p>
        </div>

        <input type="hidden" name="manage_gallery" value="1">
        <div>
            <label class="block text-sm font-medium text-gray-500 mb-2">Current Gallery</label>
            <p class="text-xs text-gray-500 mb-3">Drag to reorder. Click remove to delete an image.</p>
            <div id="galleryManager" class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($product->images as $galleryImage)
                    <div class="relative group rounded-lg border border-gray-200 bg-white overflow-hidden cursor-move" draggable="true" data-image-id="{{ $galleryImage->id }}">
                        <input type="hidden" name="existing_gallery_ids[]" value="{{ $galleryImage->id }}">
                        <img src="{{ $galleryImage->image_url }}" alt="Gallery image" class="h-28 w-full object-cover">
                        <button type="button" class="js-remove-gallery-image absolute top-1.5 right-1.5 h-7 w-7 rounded-full bg-red-500 text-white text-xs font-bold opacity-95 hover:bg-red-600 transition-colors" title="Remove image">
                            ×
                        </button>
                        <div class="absolute bottom-1.5 left-1.5 text-[10px] px-2 py-0.5 rounded bg-black/60 text-white">Drag</div>
                    </div>
                @endforeach
            </div>
            <p id="galleryEmptyText" class="text-sm text-gray-500 mt-2 {{ $product->images->isEmpty() ? '' : 'hidden' }}">No gallery images yet.</p>
        </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-4 h-4 rounded border-gray-400 bg-white text-amber-300 focus:ring-amber-300">
                <label for="is_featured" class="text-sm text-gray-700">Featured Product</label>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="px-6 py-2.5 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors">
                Update Product
            </button>
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 border border-gray-300 transition-colors">
                    Cancel
                </a>
        </div>
    </form>
    <div class="mt-12 bg-gray-50 rounded-xl p-6 border border-gray-200">
    <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
        <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        Inventory Movement History
    </h3>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-gray-200 text-gray-500 text-sm tracking-wider uppercase">
                    <th class="py-3 font-medium">Date</th>
                    <th class="py-3 font-medium">Action By</th>
                    <th class="py-3 font-medium">Reason</th>
                    <th class="py-3 font-medium">Previous</th>
                    <th class="py-3 font-medium text-center">Change</th>
                    <th class="py-3 font-medium text-right">New Stock</th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @forelse($product->inventoryLogs as $log)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
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
                        <td class="py-4 text-right font-bold text-gray-900">{{ $log->new_stock }}</td>
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

<script>
    (function () {
        const manager = document.getElementById('galleryManager');
        const emptyText = document.getElementById('galleryEmptyText');
        if (!manager) return;

        let draggingCard = null;

        const refreshEmptyState = () => {
            if (!emptyText) return;
            emptyText.classList.toggle('hidden', manager.children.length > 0);
        };

        manager.addEventListener('click', function (event) {
            const removeButton = event.target.closest('.js-remove-gallery-image');
            if (!removeButton) return;
            const card = removeButton.closest('[data-image-id]');
            if (!card) return;
            card.remove();
            refreshEmptyState();
        });

        manager.addEventListener('dragstart', function (event) {
            const card = event.target.closest('[data-image-id]');
            if (!card) return;
            draggingCard = card;
            card.classList.add('opacity-60');
            event.dataTransfer.effectAllowed = 'move';
        });

        manager.addEventListener('dragend', function () {
            if (!draggingCard) return;
            draggingCard.classList.remove('opacity-60');
            draggingCard = null;
        });

        manager.addEventListener('dragover', function (event) {
            event.preventDefault();
            if (!draggingCard) return;
            const targetCard = event.target.closest('[data-image-id]');
            if (!targetCard || targetCard === draggingCard) return;

            const targetRect = targetCard.getBoundingClientRect();
            const shouldInsertAfter = event.clientY > targetRect.top + targetRect.height / 2;
            if (shouldInsertAfter) {
                targetCard.after(draggingCard);
            } else {
                targetCard.before(draggingCard);
            }
        });
    })();
</script>
@endsection

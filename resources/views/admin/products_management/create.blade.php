<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Add Product | Lumina Admin</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-black text-white font-sans antialiased">

    <div class="max-w-4xl mx-auto p-6 sm:p-12">
        
        <div class="flex justify-between items-center mb-8 border-b border-white/10 pb-6">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-white">Add New Product</h1>
                <p class="text-gray-400 text-sm mt-1">Add items to your collection with images and details.</p>
            </div>
            <a href="{{ route('admin.admin_dashboard') }}" class="text-sm text-gray-500 hover:text-white transition-colors">
                &larr; Back to Dashboard
            </a>
        </div>

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Product Name</label>
                    <input type="text" name="name" required class="w-full bg-gray-900 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none" placeholder="e.g. Gold Necklace">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Category</label>
                    <select name="category" class="w-full bg-gray-900 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
                        <option value="Rings">Rings</option>
                        <option value="Necklaces">Necklaces</option>
                        <option value="Earrings">Earrings</option>
                        <option value="Bracelets">Bracelets</option>
                        <option value="Watches">Watches</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Price (₱)</label>
                    <input type="number" name="price" step="0.01" required class="w-full bg-gray-900 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-400 mb-2">Stock Quantity</label>
                    <input type="number" name="stock_quantity" value="10" class="w-full bg-gray-900 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Description</label>
                <textarea name="description" rows="4" class="w-full bg-gray-900 border border-white/10 rounded-lg p-3 text-white focus:border-amber-300 outline-none" placeholder="Product details..."></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Product Image</label>
                <div class="border-2 border-dashed border-white/10 rounded-lg p-8 text-center hover:border-amber-300/50 transition-colors bg-gray-900/50">
                    <input type="file" name="image" required onchange="previewImage(event)" class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-amber-300 file:text-black hover:file:bg-amber-400 cursor-pointer">
                    <img id="preview" class="mt-4 hidden w-40 rounded-lg"/>
                    <p class="text-xs text-gray-500 mt-2">Recommended: Square JPG or PNG, max 2MB.</p>
                    
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" class="w-4 h-4 rounded border-gray-700 bg-gray-900 text-amber-300 focus:ring-amber-300">
                <label for="is_featured" class="text-sm text-gray-300">Mark as Featured Product</label>
            </div>

            <button type="submit" class="w-full py-4 bg-amber-300 text-black font-bold text-lg rounded-lg hover:bg-amber-400 transform hover:scale-[1.01] transition-all shadow-lg shadow-amber-300/20">
                Create Product
            </button>
        </form>
    </div>
</body>

<script>
function previewImage(event) {
    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.classList.remove('hidden');
}
</script>
</html>
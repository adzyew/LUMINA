<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;


class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        // Admin should be able to see archived products, so remove the storefront global scope here
        $products = Product::withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class);

        if ($filter === 'archived') {
            $products->whereNotNull('archived_at');
        } elseif ($filter === 'active') {
            $products->whereNull('archived_at');
        }

        if ($request->filled('category')) {
            $products->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $products->where('name', 'like', "%{$request->input('search')}%");
        }

        $products = $products->paginate(10)->withQueryString();

        return view('admin.products_management.products_index', compact('products', 'filter'));
    }

    public function show(Request $request, Product $product)
    {
        return view('admin.products_management.products_show', compact('product'));
    }
    

    public function create()
    {
        return view('admin.products_management.create');
    }

    public function edit(Product $product)
    {
        return view('admin.products_management.edit', compact('product'));
    }

    public function store(Request $request, CloudinaryService $cloudinary)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'category'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'image'          => 'required|image|max:10240',
        ]);

        $file = $request->file('image');

        if (!$file || !$file->isValid()) {
            return back()
                ->withInput()
                ->withErrors(['image' => 'Image upload failed. Please try again.']);
        }

        try {
            $uploaded = $cloudinary->uploadImage($file->getRealPath());

            Product::create([
                'name'            => $request->name,
                'description'     => $request->description,
                'price'           => $request->price,
                'category'        => $request->category,
                'stock_quantity'  => $request->stock_quantity,
                'is_featured'     => $request->boolean('is_featured'),
                'image_url'       => $uploaded['url'],
                'image_public_id' => $uploaded['public_id'],
            ]);

            return redirect()
                ->route('admin.products.index')
                ->with('success', 'Product created successfully!');

        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['image' => 'Upload failed: ' . $e->getMessage()]);
        }
    }

     public function update(
        Request $request,
        Product $product,
        CloudinaryService $cloudinary
    ) {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'category'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            // Main image replacement (optional)
            'image'          => 'nullable|image|max:5120',
            // Add more gallery images (optional)
            'images'         => 'nullable|array',
            'images.*'       => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            // delete old image
            $cloudinary->deleteImage($product->image_public_id);

            // upload new
            $image = $cloudinary->uploadImage(
                $request->file('image')->getRealPath()
            );

            $product->image_url = $image['url'];
            $product->image_public_id = $image['public_id'];
            $product->save();
        }

        if ($request->hasFile('images')) {
            $start = (int)($product->images()->max('sort_order') ?? -1) + 1;
            foreach (array_values($request->file('images')) as $offset => $file) {
                $img = $cloudinary->uploadImage($file->getRealPath());
                $product->images()->create([
                    'image_url' => $img['url'],
                    'image_public_id' => $img['public_id'],
                    'sort_order' => $start + $offset,
                ]);
            }
        }

        if($request->has('stock_quantity') && $request->stock_quantity ) {

        $oldStock = $product->stock_quantity;
        $newStock = $request->stock_quantity;
        $difference = $newStock - $oldStock;

        InventoryLog::create([
            'product_id' => $product->id,
            
            'quantity_change' => $difference,
            'previous_stock' => $oldStock,
            'new_stock' => $newStock,
            'reason' => 'Stock updated via admin panel',
            'reference_id' => null, // could be order ID or something if related to a specific action
        ]);
        }

        $product->update([
            'name'           => $validated['name'],
            'description'    => $validated['description'] ?? null,
            'price'          => $validated['price'],
            'category'       => $validated['category'] ?? null,
            'stock_quantity' => $validated['stock_quantity'],
            'is_featured'    => $request->boolean('is_featured'),
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product, CloudinaryService $cloudinary)
    {
        $cloudinary->deleteImage($product->image_public_id);

        // delete gallery images too
        $product->loadMissing('images');
        foreach ($product->images as $img) {
            $cloudinary->deleteImage($img->image_public_id);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Archive a product (soft archive)
     */
    public function archive(Product $product)
    {

        $product->delete(); // This will set the deleted_at timestamp due to SoftDeletes trait
        $product->archived_at = now();
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product archived.');
    }

    /**
     * Unarchive a product
     */
    public function unarchive(Product $product)
    {
        $product = Product::withTrashed()->findOrFail($product->id);
        $product->restore(); // This will set the deleted_at timestamp to null

        $product->archived_at = null;
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product unarchived.');
    }
}
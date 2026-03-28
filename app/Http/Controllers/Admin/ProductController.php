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
        $filter   = $request->get('filter', 'all');
        $stock    = $request->get('stock', 'all');
        $search   = trim((string) $request->get('search', ''));
        $category = $request->get('category');

        $products = Product::withTrashed()
            ->withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class);

        if ($filter === 'archived') {
            $products->whereNotNull('archived_at');
        } elseif ($filter === 'active') {
            $products->whereNull('archived_at')->whereNull('deleted_at');
        }

        if (!empty($category)) {
            $products->where('category', $category);
        }

        if ($search !== '') {
            $products->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($stock === 'out_of_stock') {
            $products->where('stock_quantity', '<=', 0);
        } elseif ($stock === 'low_stock') {
            $products->whereBetween('stock_quantity', [1, 5]);
        } elseif ($stock === 'in_stock') {
            $products->where('stock_quantity', '>', 5);
        }

        $categories = Product::withTrashed()
            ->withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class)
            ->select('category')
            ->whereNotNull('category')
            ->where('category', '!=', '')
            ->whereRaw("TRIM(category) != ''")
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $products = $products->latest()->paginate(10)->withQueryString();

        return view('admin.products_management.products_index', compact(
            'products', 'filter', 'stock', 'search', 'category', 'categories'
        ));
    }

    public function show(Request $request, Product $product)
    {
        $product = Product::withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class)
            ->withTrashed()
            ->findOrFail($product->id);
        return view('admin.products_management.products_show', compact('product'));
    }

    /**
     * JSON endpoint for the Edit modal AJAX call.
     * Route: GET /admin/products/{product}/json
     */
    public function showJson(Product $product)
    {
        $product = Product::withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class)
            ->withTrashed()
            ->findOrFail($product->id);
        return response()->json([
            'id'             => $product->id,
            'name'           => $product->name,
            'category'       => $product->category,
            'price'          => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'description'    => $product->description,
            'image_url'      => $product->image_url,
            'is_featured'    => (bool) $product->is_featured,
        ]);
    }

    public function create()
    {
        return view('admin.products_management.create');
    }

    public function edit(Product $product)
    {
        $product = Product::withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class)
            ->withTrashed()
            ->findOrFail($product->id);
        $isArchived = $product->archived_at !== null;
        return view('admin.products_management.edit', compact('product', 'isArchived'));
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

        $product = Product::withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class)
            ->withTrashed()
            ->findOrFail($product->id);

        if ($product->archived_at !== null) {
            // Prevent editing archived products
            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Archived products cannot be edited.'], 403);
            }
            return redirect()->route('admin.products.index')->with('error', 'Archived products cannot be edited.');
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'category'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'image'          => 'nullable|image|max:5120',
            'images'         => 'nullable|array',
            'images.*'       => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('image')) {
            $cloudinary->deleteImage($product->image_public_id);
            $image = $cloudinary->uploadImage($request->file('image')->getRealPath());
            $product->image_url      = $image['url'];
            $product->image_public_id = $image['public_id'];
            $product->save();
        }

        if ($request->hasFile('images')) {
            $start = (int)($product->images()->max('sort_order') ?? -1) + 1;
            foreach (array_values($request->file('images')) as $offset => $file) {
                $img = $cloudinary->uploadImage($file->getRealPath());
                $product->images()->create([
                    'image_url'       => $img['url'],
                    'image_public_id' => $img['public_id'],
                    'sort_order'      => $start + $offset,
                ]);
            }
        }

        if ($request->has('stock_quantity')) {
            $oldStock   = $product->stock_quantity;
            $newStock   = (int) $request->stock_quantity;
            $difference = $newStock - $oldStock;

            if ($difference !== 0) {
                InventoryLog::create([
                    'product_id'      => $product->id,
                    'quantity_change' => $difference,
                    'previous_stock'  => $oldStock,
                    'new_stock'       => $newStock,
                    'reason'          => 'Stock updated via admin panel',
                    'reference_id'    => null,
                ]);
            }
        }

        $product->update([
            'name'           => $validated['name'],
            'description'    => $validated['description'] ?? null,
            'price'          => $validated['price'],
            'category'       => $validated['category'] ?? null,
            'stock_quantity' => $validated['stock_quantity'],
            'is_featured'    => $request->boolean('is_featured'),
        ]);

        // AJAX request from the modal → return JSON
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Product updated successfully!']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');

        $product->update([
            'name'           => $validated['name'],
            'description'    => $validated['description'] ?? null,
            'price'          => $validated['price'],
            'category'       => $validated['category'] ?? null,
            'stock_quantity' => $validated['stock_quantity'],
            'is_featured'    => $request->boolean('is_featured'),
        ]);

        // AJAX request from the modal → return JSON
        if ($request->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Product updated successfully!']);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id, CloudinaryService $cloudinary)
    {
        // Must use withTrashed because archived products are soft-deleted
        $product = Product::withTrashed()->findOrFail($id);

        $cloudinary->deleteImage($product->image_public_id);

        $product->loadMissing('images');
        foreach ($product->images as $img) {
            $cloudinary->deleteImage($img->image_public_id);
        }

        $product->forceDelete();

        return redirect()->route('admin.products.index')->with('success', 'Product permanently deleted!');
    }

    public function archive(Product $product)
    {
        $product->delete();          // sets deleted_at (SoftDeletes)
        $product->archived_at = now();
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product archived.');
    }

    /**
     * Unarchive — MUST resolve with withTrashed because the product is soft-deleted.
     * We accept the raw $id and look it up manually instead of relying on route
     * model binding, which would 404 on soft-deleted records by default.
     */
    public function unarchive($id)
    {
        $product = Product::withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class)
            ->withTrashed()
            ->findOrFail($id);
        $product->restore();         // clears deleted_at
        $product->archived_at = null;
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product unarchived.');
    }
}
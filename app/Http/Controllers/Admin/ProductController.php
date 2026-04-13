<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private function normalizeSpecifications(Request $request): ?array
    {
        $size = trim((string) $request->input('size_spec', ''));
        $detailsText = (string) $request->input('specification_details', '');

        $details = collect(preg_split('/\r\n|\r|\n/', $detailsText) ?: [])
            ->map(fn ($line) => trim((string) $line))
            ->filter(fn ($line) => $line !== '')
            ->values()
            ->all();

        if ($size === '' && empty($details)) {
            return null;
        }

        return [
            'size' => $size !== '' ? $size : null,
            'details' => $details,
        ];
    }

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
        return view('admin.products_management.products_show', compact('product'));
    }

    /**
     * JSON endpoint for the Edit modal AJAX call.
     * Route: GET /admin/products/{product}/json
     */
    public function showJson(Product $product)
    {
        $specifications = is_array($product->specifications) ? $product->specifications : [];

        return response()->json([
            'id'             => $product->id,
            'name'           => $product->name,
            'category'       => $product->category,
            'price'          => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'description'    => $product->description,
            'image_url'      => $product->image_url,
            'is_featured'    => (bool) $product->is_featured,
            'specifications' => $specifications,
        ]);
    }

    public function create()
    {
        return view('admin.products_management.create');
    }

    public function edit(Product $product)
    {
        $product->loadMissing('images');
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
            'images'         => 'nullable|array|max:8',
            'images.*'       => 'nullable|image|max:10240',
            'size_spec'      => 'nullable|string|max:120',
            'specification_details' => 'nullable|string|max:4000',
        ]);

        $file = $request->file('image');

        if (!$file || !$file->isValid()) {
            return back()->withInput()->withErrors(['image' => 'Image upload failed. Please try again.']);
        }

        try {
            $uploaded = $cloudinary->uploadImage($file->getRealPath());

            $product = Product::create([
                'name'            => $request->name,
                'description'     => $request->description,
                'price'           => $request->price,
                'category'        => $request->category,
                'stock_quantity'  => $request->stock_quantity,
                'is_featured'     => $request->boolean('is_featured'),
                'image_url'       => $uploaded['url'],
                'image_public_id' => $uploaded['public_id'],
                'specifications'  => $this->normalizeSpecifications($request),
            ]);

            if ($request->hasFile('images')) {
                foreach (array_values($request->file('images')) as $offset => $extraImage) {
                    if (!$extraImage || !$extraImage->isValid()) {
                        continue;
                    }
                    $uploadedExtra = $cloudinary->uploadImage($extraImage->getRealPath());
                    $product->images()->create([
                        'image_url' => $uploadedExtra['url'],
                        'image_public_id' => $uploadedExtra['public_id'],
                        'sort_order' => $offset,
                    ]);
                }
            }

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');

        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['image' => 'Upload failed: ' . $e->getMessage()]);
        }
    }

    public function update(Request $request, Product $product, CloudinaryService $cloudinary)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'category'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'image'          => 'nullable|image|max:5120',
            'images'         => 'nullable|array|max:8',
            'images.*'       => 'nullable|image|max:5120',
            'size_spec'      => 'nullable|string|max:120',
            'specification_details' => 'nullable|string|max:4000',
            'manage_gallery' => 'nullable|boolean',
            'existing_gallery_ids' => 'nullable|array',
            'existing_gallery_ids.*' => 'integer|exists:product_images,id',
        ]);

        if ($request->hasFile('image')) {
            $cloudinary->deleteImage($product->image_public_id);
            $image = $cloudinary->uploadImage($request->file('image')->getRealPath());
            $product->image_url       = $image['url'];
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

        if ($request->boolean('manage_gallery')) {
            $submittedIds = collect($request->input('existing_gallery_ids', []))
                ->map(fn ($id) => (int) $id)
                ->filter(fn ($id) => $id > 0)
                ->values();

            $currentImages = $product->images()->get();

            $imagesToDelete = $currentImages->filter(
                fn ($img) => !$submittedIds->contains((int) $img->id)
            );

            foreach ($imagesToDelete as $imageToDelete) {
                if (!empty($imageToDelete->image_public_id)) {
                    $cloudinary->deleteImage($imageToDelete->image_public_id);
                }
                $imageToDelete->delete();
            }

            foreach ($submittedIds as $index => $imageId) {
                $product->images()
                    ->where('id', $imageId)
                    ->update(['sort_order' => $index]);
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
            'specifications' => $this->normalizeSpecifications($request),
        ]);

        // AJAX request from the modal → return updated product as JSON
        if ($request->expectsJson()) {
            $product->refresh();
            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully!',
                'product' => [
                    'id'             => $product->id,
                    'name'           => $product->name,
                    'category'       => $product->category,
                    'price'          => $product->price,
                    'stock_quantity' => $product->stock_quantity,
                    'image_url'      => $product->image_url,
                ],
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }
    

    public function destroy($id, CloudinaryService $cloudinary)
    {
        // Include soft-deleted + archived records (archived items are hidden by global scope)
        $product = Product::withTrashed()
            ->withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class)
            ->findOrFail($id);

        $cloudinary->deleteImage($product->image_public_id);

        $product->loadMissing('images');
        foreach ($product->images as $img) {
            $cloudinary->deleteImage($img->image_public_id);
        }

        $product->forceDelete();

        return redirect()->route('admin.products.index')->with('success', 'Product permanently deleted!');
    }

    public function archive($id)
    {
        $product = Product::withTrashed()
            ->withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class)
            ->findOrFail($id);
            
        $product->delete();
        $product->archived_at = now();
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product archived.');
    }

    /**
     * Unarchive — accepts raw $id because route model binding excludes
     * soft-deleted records by default, causing archived products to 404.
     */
    public function unarchive($id)
    {
        $product = Product::withTrashed()
            ->withoutGlobalScope(\App\Models\Scopes\NotArchivedScope::class)
            ->findOrFail($id);
        $product->restore();
        $product->archived_at = null;
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product unarchived.');
    }
}

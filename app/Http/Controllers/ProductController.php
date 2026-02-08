<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();

        if ($request->filled('category')) {
            $products->where('category', $request->input('category'));
        }

        if ($request->filled('search')) {
            $products->where('name', 'like', "%{$request->input('search')}%");
        }

        $products = $products->paginate(10);

        return view('admin.products_management.products_index', compact('products'));
    }

    public function create()
    {
        return view('admin.products_management.create');
    }

    public function store(Request $request, CloudinaryService $cloudinary)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'price'          => 'required|numeric|min:0',
            'category'       => 'nullable|string|max:255',
            'description'    => 'nullable|string',
            'stock_quantity' => 'required|integer|min:0',
            'image'          => 'required|image|max:2048',
        ]);

        try {
            $image = $cloudinary->uploadImage(
                $request->file('image')->getRealPath()
            );
            


            Product::create([
                'name'           => $validated['name'],
                'description'    => $validated['description'] ?? null,
                'price'          => $validated['price'],
                'category'       => $validated['category'] ?? null,
                'stock_quantity' => $validated['stock_quantity'],
                'is_featured'    => $request->boolean('is_featured'),
                'image_url'      => $image['url'],
                'image_public_id'=> $image['public_id'],
            ]);

            return redirect()
                ->route('products.index')
                ->with('success', 'Product created successfully!');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['image' => 'Upload failed. Please try again.']);
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
            'image'          => 'nullable|image|max:2048',
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

        $product->update([
            'name'           => $validated['name'],
            'description'    => $validated['description'] ?? null,
            'price'          => $validated['price'],
            'category'       => $validated['category'] ?? null,
            'stock_quantity' => $validated['stock_quantity'],
            'is_featured'    => $request->boolean('is_featured'),
        ]);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product, CloudinaryService $cloudinary)
    {
        $cloudinary->deleteImage($product->image_public_id);

        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
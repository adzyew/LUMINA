<?php

namespace App\Http\Controllers;

use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use App\Models\Product;
use App\Models\Category; // Assuming you made this model

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        $categories = []; 
        return view('products.index', compact('products', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string',
            'price'    => 'required|numeric',
            'category' => 'nullable|string',
            'image'    => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // ✅ THIS IS THE CORRECT METHOD FOR v3
        $uploadedFile = Cloudinary::uploadImage(
            $request->file('image')->getRealPath(),
            [
                'folder'        => 'products',
                'upload_preset' => config('cloudinary.upload_preset'),
            ]
        );

        dd([
            'secure_url' => $uploadedFile->getSecurePath(),
            'public_id'  => $uploadedFile->getPublicId(),
        ]);
        dd(config('cloudinary.cloud_url'));

        Product::create([
            'name' => $validated['name'],
            'description' => $request->description,
            'price' => $validated['price'],
            'category' => $validated['category'] ?? null,
            'stock_quantity' => $request->stock_quantity ?? 0,
            'is_featured' => $request->boolean('is_featured'),
            'image_path' => $uploadedFile->getSecurePath(),
        ]);

        return redirect()
            ->route('admin.admin_dashboard')
            ->with('success', 'Product created successfully!');

            
    }

    
    public function create()
    {
        return view('admin.products_management.create');
    }

    public function edit(Product $product)
    {
        return view('admin.products_management.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validateRequest = $request->validate([
            'name' => ['sometimes','required', 'max:255'],
            'image' => ['sometimes','required', 'image', 'max:2048'],
            'price' => ['sometimes','required', 'numeric'],
            'description' => ['sometimes','required'],
        ]);

        if($request->hasFile('image')){
            Cloudinary::destroy($product->image_public_id);
            $cloudinaryImage = $request->file('image')->storeOnCloudinary('products');
            $url = $cloudinaryImage->getSecurePath();
            $public_id = $cloudinaryImage->getPublicId();

            $product->update([
                'image_url' => $url,
                'image_public_id' => $public_id,
            ]);

        }

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ]);

        return redirect()->route('products.index')->with('message', 'Updated successfully');
    }

    public function destroy(Product $product)
    {
        Cloudinary::destroy($product->image_public_id);
        $product->delete();

        return redirect()->route('products.index')->with('message', 'Deleted Successfully');

    }
}
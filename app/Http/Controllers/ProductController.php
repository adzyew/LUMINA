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
        $request->validate([
            'name' => 'required',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:2048' 
        ]);

        // Create the product first
        $product = Product::create($request->only('name', 'price'));

        // Loop through photos if they exist
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                
                // Upload each to the 'products' folder
                $result = $photo->storeOnCloudinary('products');

                // Save relationship
                $product->images()->create([
                    'url'       => $result->getSecurePath(),
                    'public_id' => $result->getPublicId(),
                ]);
            }
        }

        return back()->with('success', 'Product and gallery uploaded!');
    }
    
    public function create()
    {
        return view('create');
    }

    public function edit(Product $product)
    {
        return view('edit', compact('product'));
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
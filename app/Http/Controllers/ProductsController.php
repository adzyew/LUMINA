<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query();

        if ($request->filled('category')) {
            $products->whereRaw('LOWER(category) = ?', [strtolower($request->category)]);
        }

        if ($request->filled('search')) {
            $products->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $products->latest()->paginate(12)->withQueryString();

        $filterCategories = ['watches', 'rings', 'bracelets', 'necklaces', 'earrings'];

        return view('products.index', compact('products', 'filterCategories'));
    }

    public function show(Product $product)
    {
        $product->load(['reviews.user', 'wishlistedBy', 'images']);
        $reviews = $product->reviews()->with('user')->latest()->paginate(5);
        $averageRating = $product->reviews()->avg('rating');
        $isWishlisted = auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists();
        
        return view('products.show', compact('product', 'reviews', 'averageRating', 'isWishlisted'));
    }
}
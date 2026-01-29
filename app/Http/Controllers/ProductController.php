<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category; // Assuming you made this model
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // 1. Catalog Page
    public function index(Request $request)
    {
        $query = Product::query();

        // Optional: Filter by Category
        if ($request->has('category')) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Fetch products (12 per page)
        $products = $query->latest()->paginate(12);
        
        // Get categories for the filter sidebar
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    // 2. Single Product Page
    public function show($id)
    {
        // Load product with its extra images (if you added that relationship)
        $product = Product::with('images')->findOrFail($id);

        // Fetch related products (e.g., same category, excluding current one)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $id)
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    
}
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $product->load(['wishlistedBy', 'images']);

        $reviews = $product->reviews()
            ->with('user')
            ->where(function ($query) {
                $query->where('status', 'approved');

                if (Auth::check()) {
                    $query->orWhere(function ($ownQuery) {
                        $ownQuery->where('user_id', Auth::id())
                            ->where('status', 'pending');
                    });
                }
            })
            ->latest()
            ->paginate(5);

        $averageRating = (float) ($product->reviews()->where('status', 'approved')->avg('rating') ?? 0);
        /** @var \App\Models\User|null $currentUser */
        $currentUser = Auth::user();
        $isWishlisted = $currentUser ? $currentUser->wishlist()->where('product_id', $product->id)->exists() : false;
        
        return view('products.show', compact('product', 'reviews', 'averageRating', 'isWishlisted'));
    }
}
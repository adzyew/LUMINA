<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    /**
     * Display the homepage with featured products
     */
    public function index(): View
    {
        $featuredProducts = [
            [
                'id' => 1,
                'name' => 'Classic Gold Ring',
                'description' => 'Timeless elegance in 18K gold',
                'price' => 2499,
                'image' => 'ring',
                'category' => 'rings'
            ],
            [
                'id' => 2,
                'name' => 'Ruby Pendant',
                'description' => 'Exquisite ruby centerpiece',
                'price' => 3899,
                'image' => 'public\IMAGES\watch_1',
                'category' => 'pendants'
            ],
            [
                'id' => 3,
                'name' => 'Diamond Ring',
                'description' => 'Brilliant cut perfection',
                'price' => 8999,
                'image' => 'diamond-1.jpg',
                'category' => 'rings'
            ],
            [
                'id' => 4,
                'name' => 'Emerald Luxury Ring',
                'description' => 'Natural emerald statement piece',
                'price' => 6799,
                'image' => 'emerald-1.jpg',
                'category' => 'rings'
            ]
        ];

        return view('welcome', compact('featuredProducts'));
    }

    /**
     * Display all collections
     */
    public function collections(): View
    {
        $products = [
            // Add more products here
        ];

        return view('collections', compact('products'));
    }

    /**
     * Show individual product
     */
    public function show($id): View
    {
        // Fetch product from database
        $product = [
            'id' => $id,
            'name' => 'Product Name',
            'description' => 'Product Description',
            'price' => 2999,
            'images' => [],
            'specifications' => []
        ];

        return view('product', compact('product'));
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:1'
        ]);

        // Add to cart logic here
        // You might want to use session or database

        return response()->json([
            'success' => true,
            'message' => 'Product added to cart successfully'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
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

        $products = $products->latest()->paginate(16)->withQueryString();
        $filterCategories = ['watches', 'rings', 'bracelets', 'necklaces', 'earrings'];

        return view('collection.index', compact('products', 'filterCategories'));
    }
}

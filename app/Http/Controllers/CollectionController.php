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

        if ($request->filled('material')) {
            $products->whereHas('features', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->material . '%'); 
            });
        }

        if ($request->filled('sort')){
            if($request->sort === 'price_asc'){
                $products->orderBy('price', 'asc');
            } elseif($request->sort === 'price_desc'){
                $products->orderBy('price', 'desc');
            }else {
                $products->latest();
            }
        }else {
            $products->latest();
        }

        $products = $products->latest()->paginate(16)->withQueryString();
        $filterCategories = ['watches', 'rings', 'bracelets', 'necklaces', 'earrings'];

        $materials = ['gold', 'silver', 'platinum', 'diamond', 'gemstone'];

        return view('collection.index', compact('products', 'filterCategories', 'materials'));
    }
}

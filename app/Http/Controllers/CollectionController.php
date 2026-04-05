<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'search'   => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
            'material' => 'nullable|string|max:100',
            'sort'     => 'nullable|in:price_asc,price_desc,latest',
            'price_range' => 'nullable|in:100-500,500-1000,1000-2000,2000-5000,5000+',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|min:0',
        ]);

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

        if ($request->filled('min_price')) {
            $products->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $products->where('price', '<=', (float) $request->max_price);
        } elseif ($request->filled('price_range')) {
            $range = (string) $request->price_range;
            if ($range === '100-500') {
                $products->whereBetween('price', [100, 500]);
            } elseif ($range === '500-1000') {
                $products->whereBetween('price', [500, 1000]);
            } elseif ($range === '1000-2000') {
                $products->whereBetween('price', [1000, 2000]);
            } elseif ($range === '2000-5000') {
                $products->whereBetween('price', [2000, 5000]);
            } elseif ($range === '5000+') {
                $products->where('price', '>=', 5000);
            }
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

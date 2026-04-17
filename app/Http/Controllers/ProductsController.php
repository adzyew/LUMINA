<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductsController extends Controller
{
    private const REVIEWABLE_ORDER_STATUSES = [
        'pending',
        'confirmed',
        'processing',
        'shipped',
        'delivered',
    ];

    public function index(Request $request)
    {
        $request->validate([
            'search'   => 'nullable|string|max:100',
            'category' => 'nullable|string|max:100',
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

        if ($request->filled('min_price')) {
            $products->where('price', '>=', (float) $request->min_price);
        }

        if ($request->filled('max_price')) {
            $products->where('price', '<=', (float) $request->max_price);
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
                $query->where('status', 'approved')
                    ->where('is_flagged', false);

                if (Auth::check()) {
                    $query->orWhere(function ($ownQuery) {
                        $ownQuery->where('user_id', Auth::id())
                            ->where('status', 'pending');
                    });
                }
            })
            ->latest()
            ->paginate(5);

        $averageRating = (float) ($product->reviews()
            ->where('status', 'approved')
            ->where('is_flagged', false)
            ->avg('rating') ?? 0);
        /** @var \App\Models\User|null $currentUser */
        $currentUser = Auth::user();
        $isWishlisted = $currentUser ? $currentUser->wishlist()->where('product_id', $product->id)->exists() : false;
        $canReview = $currentUser
            ? $currentUser->orders()
                ->whereIn('status', self::REVIEWABLE_ORDER_STATUSES)
                ->whereHas('items', function ($query) use ($product) {
                    $query->where('product_id', $product->id);
                })
                ->exists()
            : false;

        $relatedProducts = Product::query()
            ->where('id', '!=', $product->id)
            ->when(!empty($product->category), function ($query) use ($product) {
                $query->whereRaw('LOWER(category) = ?', [strtolower((string) $product->category)]);
            })
            ->withAvg(['reviews' => function ($query) {
                $query->where('status', 'approved')
                    ->where('is_flagged', false);
            }], 'rating')
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 'approved')
                    ->where('is_flagged', false);
            }])
            ->inRandomOrder()
            ->take(4)
            ->get();

        if ($relatedProducts->count() < 4) {
            $fallback = Product::query()
                ->where('id', '!=', $product->id)
                ->whereNotIn('id', $relatedProducts->pluck('id'))
                ->withAvg(['reviews' => function ($query) {
                    $query->where('status', 'approved')
                        ->where('is_flagged', false);
                }], 'rating')
                ->withCount(['reviews' => function ($query) {
                    $query->where('status', 'approved')
                        ->where('is_flagged', false);
                }])
                ->inRandomOrder()
                ->take(4 - $relatedProducts->count())
                ->get();

            $relatedProducts = $relatedProducts->concat($fallback);
        }

        return view('products.show', compact('product', 'reviews', 'averageRating', 'isWishlisted', 'relatedProducts', 'canReview'));
    }
}

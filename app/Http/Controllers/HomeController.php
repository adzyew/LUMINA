<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product; // <--- IMPORT THIS LINE
use App\Models\Review;

class HomeController extends Controller
{
    public function home()
    {
        $featuredProducts = Product::where('is_featured', 1)
            ->withAvg(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }], 'rating')
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->latest()
            ->take(6)
            ->get();
        $heroSlides = Product::whereNotNull('image_url')->inRandomOrder()->take(6)->get();
        $customerReviews = Review::query()
            ->approved()
            ->whereNotNull('comment')
            ->whereRaw("TRIM(comment) <> ''")
            ->with(['user:id,name', 'product:id,name'])
            ->latest('moderated_at')
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact('featuredProducts', 'heroSlides', 'customerReviews'));
    }
    /**
     * Display the homepage with featured products
     */
    public function index(): View
    {
        $featuredProducts = Product::where('is_featured', 1)
            ->withAvg(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }], 'rating')
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->latest()
            ->take(8)
            ->get();
        if ($featuredProducts->isEmpty()) {
            $featuredProducts = Product::withAvg(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }], 'rating')
                ->withCount(['reviews' => function ($query) {
                    $query->where('status', 'approved');
                }])
                ->inRandomOrder()
                ->take(8)
                ->get();
        }
        $latestProducts = Product::withAvg(['reviews' => function ($query) {
            $query->where('status', 'approved');
        }], 'rating')
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->latest()
            ->take(8)
            ->get();
        $browseProducts = Product::withAvg(['reviews' => function ($query) {
            $query->where('status', 'approved');
        }], 'rating')
            ->withCount(['reviews' => function ($query) {
                $query->where('status', 'approved');
            }])
            ->inRandomOrder()
            ->take(12)
            ->get();
        $heroSlides = Product::whereNotNull('image_url')->inRandomOrder()->take(6)->get();
        $customerReviews = Review::query()
            ->approved()
            ->whereNotNull('comment')
            ->whereRaw("TRIM(comment) <> ''")
            ->with(['user:id,name', 'product:id,name'])
            ->latest('moderated_at')
            ->latest()
            ->take(3)
            ->get();

        return view('welcome', compact('featuredProducts', 'latestProducts', 'browseProducts', 'heroSlides', 'customerReviews'));
    }

    /**
     * Display all collections
     */
    public function collections(): View
    {
        // Fetch ALL products, or paginate them (12 per page)
        $products = Product::paginate(12);

        return view('collections', compact('products'));
    }

    /**
     * Show individual product
     */
    public function show($id): View
    {
        // Fetch the specific product by ID, or show 404 error if missing
        $product = Product::findOrFail($id);

        return view('products.show', compact('product')); 
        // Note: Make sure your view file is named 'products/show.blade.php' 
        // or update this to 'product' if your file is 'product.blade.php'
    }

    /**
     * Add product to cart
     * (It is cleaner to keep this in CartController, but here is how to do it properly)
     */
    public function addToCart(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id', // Check if ID exists in DB
            'quantity' => 'required|integer|min:1'
        ]);

        $product = Product::find($validated['product_id']);
        
        // Save to Session
        $cart = session()->get('cart', []);

        if(isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $validated['quantity'];
        } else {
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $validated['quantity'],
                "price" => $product->price,
                "image" => $product->image_path // Use the DB column name
            ];
        }

        session()->put('cart', $cart);

        // Redirect back so the user sees the page reload
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }
}

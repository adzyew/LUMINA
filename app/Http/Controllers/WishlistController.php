<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function toggle(Request $request, Product $product)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('info', 'Please login to add items to your wishlist.');
        }

        $wishlist = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($wishlist) {
            $wishlist->delete();
            $message = 'Removed from wishlist';
            $added = false;
        } else {
            Wishlist::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
            ]);
            $message = 'Added to wishlist';
            $added = true;
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'added' => $added]);
        }

        return back()->with('success', $message);
    }

    public function index()
    {
        $wishlistItems = Wishlist::query()
            ->where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->paginate(12);

        return view('wishlist.index', compact('wishlistItems'));
    }

    public function storefront()
    {
        $wishlistItems = Wishlist::query()
            ->where('user_id', Auth::id())
            ->with('product')
            ->latest()
            ->paginate(12);

        return view('wishlist.storefront', compact('wishlistItems'));
    }
}

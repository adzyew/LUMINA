<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // 1. SHOW CART PAGE (This fixes the error)
    public function index()
    {
        return view('cart.shoppingcart');
    }

    // 2. ADD TO CART
    public function addToCart($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $product->image_path 
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Added to cart!');
    }

    // 3. REMOVE FROM CART
    public function remove(Request $request)
    {
        if($request->id) {
            $cart = session()->get('cart');
            if(isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return redirect()->back()->with('success', 'Removed!');
        }
    }
}
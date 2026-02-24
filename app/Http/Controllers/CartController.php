<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
                "image" => $product->image_url ?? $product->image_path ?? '',
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
            if ($request->ajax() || $request->wantsJson()) {
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                return response()->json(['success' => true, 'message' => 'Removed!', 'total' => number_format($total,2)]);
            }
            return redirect()->back()->with('success', 'Removed!');
        }
    }

    /**
     * Update item quantity in the cart.
     * Accepts `id` and `quantity` (int). If quantity <= 0 it removes the item.
     */
    public function updateQuantity(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'quantity' => 'required|integer',
        ]);

        $cart = session()->get('cart', []);
        $id = $request->id;
        $quantity = (int) $request->quantity;

        if (!isset($cart[$id])) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Item not found in cart.'], 404);
            }
            return redirect()->back()->with('error', 'Item not found in cart.');
        }

        if ($quantity <= 0) {
            unset($cart[$id]);
            session()->put('cart', $cart);
            if ($request->ajax() || $request->wantsJson()) {
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                return response()->json(['success' => true, 'message' => 'Removed from cart.', 'removed' => true, 'total' => number_format($total,2)]);
            }
            return redirect()->back()->with('success', 'Removed from cart.');
        }

        $cart[$id]['quantity'] = $quantity;
        session()->put('cart', $cart);

        if ($request->ajax() || $request->wantsJson()) {
            $itemSubtotal = $cart[$id]['price'] * $cart[$id]['quantity'];
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            return response()->json([
                'success' => true,
                'message' => 'Cart updated.',
                'quantity' => $cart[$id]['quantity'],
                'item_subtotal' => number_format($itemSubtotal, 2),
                'total' => number_format($total, 2),
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        return view('cart.checkout');
    }

    public function placeOrder(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'contact_phone' => 'required|string|max:20',
            'shipping_street' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_province' => 'required|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        $total = 0;
        foreach ($cart as $id => $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $addressParts = array_filter([
            $request->shipping_street,
            $request->shipping_city,
            $request->shipping_province,
            $request->shipping_postal_code,
            $request->shipping_country,
        ]);
        $fullAddress = implode(', ', $addressParts);

        $order = Order::create([
            'user_id' => auth()->id(),
            'total_price' => $total,
            'status' => 'pending',
            'shipping_address' => $fullAddress,
            'contact_phone' => $request->contact_phone,
            'shipping_street' => $request->shipping_street,
            'shipping_city' => $request->shipping_city,
            'shipping_province' => $request->shipping_province,
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_country' => $request->shipping_country,
            'notes' => $request->notes,
        ]);

        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'unit_price' => $item['price'],
            ]);
        }

        session()->forget('cart');

        try {
            $order->load(['user', 'items.product']);
            Mail::to($order->user->email)->send(new OrderPlacedMail($order));
        } catch (\Throwable $e) {
            // Log mail failure but don't block order completion
            report($e);
        }

        return redirect()->route('dashboard')->with('success', 'Order #' . $order->id . ' placed successfully! Check your email for confirmation.');
    }
}
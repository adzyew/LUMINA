<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Services\PaymongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;


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

        return view('cart.checkout', [
            'shippingRates' => $this->shippingRates(),
        ]);
    }

    public function placeOrder(Request $request, PaymongoService $paymongoService)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'contact_phone' => 'required|string|max:20',
            'contact_email' => 'required|email|max:255',
            'payment_method' => 'required|in:cod,paymongo',
            'shipping_street' => 'required|string|max:255',
            'shipping_city' => 'required|string|max:100',
            'shipping_province' => 'required|string|max:100',
            'shipping_postal_code' => 'nullable|string|max:20',
            'shipping_country' => 'required|string|max:100',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            // ✨ START TRANSACTION ✨
            // We pass the $request and $cart into the transaction using "use"
            $order = DB::transaction(function () use ($request, $cart) {

                $subtotal = $this->calculateSubtotal($cart);
                $shippingFee = $this->shippingFeeForCity($request->shipping_city);
                $total = $subtotal + $shippingFee;

                /** @var \App\Models\User $user */
                $user = Auth::user();
                $pointsUsed = 0;
                $discountAmount = 0;

                if($request->has('use_points') && $request->use_points == '1' && $user->points_balance > 0){
                    $discountAmount = min($total, $user->points_balance);
                    $pointsUsed = $discountAmount; 

                    $total -= $discountAmount;
                    $user->decrement('points_balance', $pointsUsed);
                }

                $addressParts = array_filter([
                    $request->shipping_street,
                    $request->shipping_city,
                    $request->shipping_province,
                    $request->shipping_postal_code,
                    $request->shipping_country,
                ]);
                $fullAddress = implode(', ', $addressParts);

                // 1. Create Order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => $total,
                    'points_used' => $pointsUsed,
                    'discount_amount' => $discountAmount,
                    'status' => $request->payment_method === 'paymongo' ? 'awaiting_payment' : 'pending',
                    'payment_method' => $request->payment_method,
                    'payment_status' => 'pending',
                    'shipping_address' => $fullAddress,
                    'contact_phone' => $request->contact_phone,
                    'contact_email' => $request->contact_email,
                    'shipping_street' => $request->shipping_street,
                    'shipping_city' => $request->shipping_city,
                    'shipping_province' => $request->shipping_province,
                    'shipping_postal_code' => $request->shipping_postal_code,
                    'shipping_country' => $request->shipping_country,
                    'notes' => $request->notes,
                ]);

                // 2. Process Items & Inventory
                foreach ($cart as $productId => $item) {
                    // FIXED BUG: Changed $id to $productId here!
                    $product = Product::find($productId);

                    if($product) {
                        $oldStock = $product->stock_quantity;
                        $quantityBought = $item['quantity'];

                        OrderItem::create([
                            'order_id' => $order->id,
                            'product_id' => $productId,
                            'quantity' => $quantityBought,
                            'unit_price' => $item['price'],
                        ]);

                        // For PayMongo, finalize stock changes only after successful payment webhook.
                        if ($request->payment_method !== 'paymongo') {
                            $product->decrement('stock_quantity', $quantityBought);

                            InventoryLog::create([
                                'product_id' => $productId,
                                'user_id' => Auth::id(),
                                'quantity_change' => -$quantityBought,
                                'previous_stock' => $oldStock,
                                'new_stock' => $oldStock - $quantityBought,
                                'reason' => 'Order placed',
                                'reference_id' => $order->id,
                            ]);
                        }
                    }
                }

                // Return the order out of the transaction so the email can use it
                return $order; 
            }); 
            // ✨ END TRANSACTION ✨

            if ($order->payment_method === 'paymongo') {
                $order->loadMissing(['user', 'items.product']);

                $checkoutSession = $paymongoService->createCheckoutSession(
                    $order,
                    route('payments.paymongo.success', ['order' => $order->id]),
                    route('payments.paymongo.cancel', ['order' => $order->id])
                );

                $order->update([
                    'paymongo_checkout_session_id' => data_get($checkoutSession, 'id'),
                    'paymongo_reference' => data_get($checkoutSession, 'attributes.reference_number'),
                ]);

                return redirect()->away(data_get($checkoutSession, 'attributes.checkout_url'));
            }

            session()->forget('cart');

            // Send Email (We do this outside the transaction so if the email fails, 
            // the order isn't deleted!)
            try {
                $order->load(['user', 'items.product']);
                Mail::to($order->user->email)->send(new OrderPlacedMail($order));
            } catch (\Throwable $e) {
                report($e);
            }

            return redirect()->route('dashboard')->with('success', 'Order #' . $order->display_order_number . ' placed successfully! Check your email for confirmation.');

        } catch (\Exception $e) {
            // IF ANYTHING ABOVE FAILED, IT COMES HERE AND NOTHING IS SAVED!
            Log::error('Checkout Failed: ' . $e->getMessage());
            return redirect()->route('cart.index')->with('error', 'There was an issue processing your order. Please try again.');
        }
    }

    private function calculateSubtotal(array $cart): float
    {
        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        return (float) $subtotal;
    }

    private function shippingFeeForCity(?string $city): float
    {
        $rates = $this->shippingRates();

        if (!$city) {
            return (float) ($rates['default'] ?? 0);
        }

        return (float) ($rates[$city] ?? $rates['default'] ?? 0);
    }

    private function shippingRates(): array
    {
        return [
            'City of Manila' => 0,
            'Quezon City' => 0,
            'City of Caloocan' => 0,
            'City of Makati' => 0,
            'City of Taguig' => 0,
            'City of Pasig' => 0,
            'City of Parañaque' => 0,
            'City of Las Piñas' => 0,
            'City of Mandaluyong' => 0,
            'City of Marikina' => 0,
            'City of Navotas' => 0,
            'City of Malabon' => 0,
            'City of Valenzuela' => 0,
            'City of San Juan' => 0,
            'City of Muntinlupa' => 0,
            'Pasay City' => 0,
            'Pateros' => 0,
            'default' => 0,
        ];
    }
}

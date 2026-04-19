<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedMail;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\InventoryLog;
use App\Models\Promo;
use App\Models\PromoClaim;
use App\Models\Product;
use App\Services\PaymongoService;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use RuntimeException;


class CartController extends Controller
{
    private const CART_CACHE_TTL_DAYS = 30;
    private const PROMO_MIN_DISCOUNT_PERCENT = 5.00;
    private const PROMO_CLAIM_WINDOW_MINUTES = 60;

    private function cartCacheKey(int $userId): string
    {
        return 'cart_user_' . $userId;
    }

    private function syncPersistentCart(array $cart): void
    {
        if (!Auth::check()) {
            return;
        }

        $userId = (int) Auth::id();
        if ($userId <= 0) {
            return;
        }

        Cache::put($this->cartCacheKey($userId), $cart, now()->addDays(self::CART_CACHE_TTL_DAYS));
    }

    private function clearPersistentCart(): void
    {
        if (!Auth::check()) {
            return;
        }

        $userId = (int) Auth::id();
        if ($userId <= 0) {
            return;
        }

        Cache::forget($this->cartCacheKey($userId));
    }

    private function activePromoSessionClaimId(): ?int
    {
        $claimId = (int) session('checkout_promo_claim_id', 0);
        return $claimId > 0 ? $claimId : null;
    }

    private function clearPromoSession(): void
    {
        session()->forget('checkout_promo_claim_id');
    }

    private function getSessionPromoClaimForUser(int $userId): ?PromoClaim
    {
        $claimId = $this->activePromoSessionClaimId();
        if (!$claimId) {
            return null;
        }

        $claim = PromoClaim::with('promo')
            ->where('id', $claimId)
            ->where('user_id', $userId)
            ->first();

        if (!$claim) {
            $this->clearPromoSession();
            return null;
        }

        if ($claim->used_at !== null) {
            $this->clearPromoSession();
            return null;
        }

        $now = now();
        $promo = $claim->promo;
        if (
            !$promo ||
            !$promo->is_active ||
            ($promo->starts_at && $promo->starts_at->isFuture()) ||
            $promo->expires_at->isPast() ||
            !$claim->expires_at ||
            $claim->expires_at->isPast()
        ) {
            $this->clearPromoSession();
            return null;
        }

        if ((float) $promo->discount_percent < self::PROMO_MIN_DISCOUNT_PERCENT) {
            $this->clearPromoSession();
            return null;
        }

        return $claim;
    }

    private function promoViewDataFromClaim(?PromoClaim $claim, float $discountableTotal): array
    {
        if (!$claim || !$claim->promo) {
            return [
                'hasPromo' => false,
                'code' => null,
                'discount_percent' => 0.0,
                'discount_amount' => 0.0,
                'expires_at' => null,
            ];
        }

        $percent = max((float) $claim->promo->discount_percent, self::PROMO_MIN_DISCOUNT_PERCENT);
        $amount = round(($discountableTotal * $percent) / 100, 2);

        return [
            'hasPromo' => true,
            'code' => $claim->promo->code,
            'discount_percent' => $percent,
            'discount_amount' => $amount,
            'expires_at' => optional($claim->expires_at)?->toIso8601String(),
        ];
    }

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
        $this->syncPersistentCart($cart);

        if (request()->ajax() || request()->wantsJson()) {
            $cartCount = collect($cart)->sum(function ($item) {
                return (int) ($item['quantity'] ?? 0);
            });

            return response()->json([
                'success' => true,
                'message' => 'Added to cart!',
                'cart_count' => $cartCount,
            ]);
        }

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
                $this->syncPersistentCart($cart);
            }
            if ($request->ajax() || $request->wantsJson()) {
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                $cartCount = collect($cart)->sum(function ($item) {
                    return (int) ($item['quantity'] ?? 0);
                });
                return response()->json([
                    'success' => true,
                    'message' => 'Removed!',
                    'total' => number_format($total, 2),
                    'cart_count' => $cartCount,
                ]);
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
            $this->syncPersistentCart($cart);
            if ($request->ajax() || $request->wantsJson()) {
                $total = 0;
                foreach ($cart as $item) {
                    $total += $item['price'] * $item['quantity'];
                }
                $cartCount = collect($cart)->sum(function ($item) {
                    return (int) ($item['quantity'] ?? 0);
                });
                return response()->json([
                    'success' => true,
                    'message' => 'Removed from cart.',
                    'removed' => true,
                    'total' => number_format($total, 2),
                    'cart_count' => $cartCount,
                ]);
            }
            return redirect()->back()->with('success', 'Removed from cart.');
        }

        $cart[$id]['quantity'] = $quantity;
        session()->put('cart', $cart);
        $this->syncPersistentCart($cart);

        if ($request->ajax() || $request->wantsJson()) {
            $itemSubtotal = $cart[$id]['price'] * $cart[$id]['quantity'];
            $total = 0;
            foreach ($cart as $item) {
                $total += $item['price'] * $item['quantity'];
            }
            $cartCount = collect($cart)->sum(function ($item) {
                return (int) ($item['quantity'] ?? 0);
            });
            return response()->json([
                'success' => true,
                'message' => 'Cart updated.',
                'quantity' => $cart[$id]['quantity'],
                'item_subtotal' => number_format($itemSubtotal, 2),
                'total' => number_format($total, 2),
                'cart_count' => $cartCount,
            ]);
        }

        return redirect()->back()->with('success', 'Cart updated.');
    }

    public function applyPromo(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to apply a promo code.');
        }

        $validated = $request->validate([
            'promo_code' => 'required|string|max:50',
        ]);

        $userId = (int) Auth::id();
        $code = strtoupper(trim((string) $validated['promo_code']));

        $promo = Promo::query()
            ->whereRaw('UPPER(code) = ?', [$code])
            ->first();

        if (!$promo) {
            return back()->with('error', 'Promo code not found.');
        }

        if ((float) $promo->discount_percent < self::PROMO_MIN_DISCOUNT_PERCENT) {
            return back()->with('error', 'Promo discount must be at least 5%.');
        }

        $now = now();
        if (!$promo->is_active || ($promo->starts_at && $promo->starts_at->isFuture()) || $promo->expires_at->isPast()) {
            return back()->with('error', 'This promo code is not active.');
        }

        $existingUsedClaim = PromoClaim::query()
            ->where('promo_id', $promo->id)
            ->where('user_id', $userId)
            ->whereNotNull('used_at')
            ->exists();

        if ($existingUsedClaim) {
            return back()->with('error', 'You already used this promo code.');
        }

        $claimExpiry = CarbonImmutable::now()->addMinutes(self::PROMO_CLAIM_WINDOW_MINUTES);
        $promoExpiry = CarbonImmutable::instance($promo->expires_at);
        if ($claimExpiry->greaterThan($promoExpiry)) {
            $claimExpiry = $promoExpiry;
        }

        $claim = PromoClaim::query()->updateOrCreate(
            [
                'promo_id' => $promo->id,
                'user_id' => $userId,
            ],
            [
                'claimed_at' => now(),
                'expires_at' => $claimExpiry,
                'used_at' => null,
                'order_id' => null,
            ]
        );

        session(['checkout_promo_claim_id' => $claim->id]);

        return back()->with('success', sprintf(
            'Promo %s applied. You have %d minutes to use it.',
            $promo->code,
            self::PROMO_CLAIM_WINDOW_MINUTES
        ));
    }

    public function removePromo()
    {
        $this->clearPromoSession();
        return back()->with('info', 'Promo code removed.');
    }

    public function checkout()
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $userId = $user ? (int) $user->id : 0;

        $subtotal = $this->calculateSubtotal($cart);
        $shippingFee = 0.0;
        $discountableTotal = $subtotal + $shippingFee;
        $promoClaim = $userId > 0 ? $this->getSessionPromoClaimForUser($userId) : null;
        $promoView = $this->promoViewDataFromClaim($promoClaim, $discountableTotal);

        return view('cart.checkout', [
            'shippingRates' => $this->shippingRates(),
            'promoView' => $promoView,
        ]);
    }

    public function confirmation(Order $order)
    {
        if ((int) $order->user_id !== (int) Auth::id()) {
            abort(403);
        }

        $order->loadMissing(['items.product']);

        return view('cart.confirmation', [
            'order' => $order,
        ]);
    }

    public function placeOrder(Request $request, PaymongoService $paymongoService)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Keep checkout resilient across environments if hidden fields are not posted.
        $request->merge([
            'shipping_province' => $request->input('shipping_province') ?: 'Metro Manila',
            'shipping_country' => $request->input('shipping_country') ?: 'Philippines',
        ]);

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
            $order = DB::transaction(function () use ($request, $cart) {
                $subtotal = $this->calculateSubtotal($cart);
                $shippingFee = $this->shippingFeeForCity($request->shipping_city);
                $total = $subtotal + $shippingFee;

                $promoDiscountAmount = 0.0;
                $promoDiscountPercent = null;
                $promoCode = null;
                $promoId = null;

                /** @var \App\Models\User $user */
                $user = Auth::user();
                $pointsUsed = 0;
                $pointsDiscountAmount = 0.0;

                $promoClaim = $this->getSessionPromoClaimForUser((int) $user->id);
                if ($promoClaim && $promoClaim->promo) {
                    $promoDiscountPercent = max((float) $promoClaim->promo->discount_percent, self::PROMO_MIN_DISCOUNT_PERCENT);
                    $promoDiscountAmount = round(($total * $promoDiscountPercent) / 100, 2);
                    $total = max(0, $total - $promoDiscountAmount);
                    $promoCode = $promoClaim->promo->code;
                    $promoId = (int) $promoClaim->promo->id;
                }

                if ($request->has('use_points') && $request->use_points == '1' && $user->points_balance > 0) {
                    $pointsDiscountAmount = min($total, (float) $user->points_balance);
                    $pointsUsed = (int) round($pointsDiscountAmount);
                    $total -= $pointsDiscountAmount;
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

                $orderData = [
                    'user_id' => Auth::id(),
                    'total_price' => round($total, 2),
                    'points_used' => $pointsUsed,
                    'discount_amount' => round($promoDiscountAmount + $pointsDiscountAmount, 2),
                    'promo_id' => $promoId,
                    'promo_code' => $promoCode,
                    'promo_discount_percent' => $promoDiscountPercent,
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
                ];

                if (Schema::hasColumn('orders', 'payment_channel')) {
                    $orderData['payment_channel'] = $request->payment_method === 'paymongo' ? 'online' : 'cod';
                }

                $order = Order::create($orderData);

                foreach ($cart as $productId => $item) {
                    $product = Product::find($productId);

                    if ($product) {
                        $oldStock = $product->stock_quantity;
                        $quantityBought = (int) $item['quantity'];

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

                if ($promoClaim) {
                    $promoClaim->forceFill([
                        'used_at' => now(),
                        'order_id' => $order->id,
                    ])->save();
                }

                return $order;
            });

            $this->clearPromoSession();

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

                $checkoutUrl = (string) data_get($checkoutSession, 'attributes.checkout_url', '');
                if ($checkoutUrl === '') {
                    throw new RuntimeException('PayMongo checkout URL is missing from response.');
                }

                return redirect()->away($checkoutUrl);
            }

            session()->forget('cart');
            $this->clearPersistentCart();

            // Send email outside the transaction so order persistence is never rolled back by mail failure.
            try {
                $order->load(['user', 'items.product']);
                Log::info('Order confirmation email send attempt (COD)', [
                    'order_id' => $order->id,
                    'order_number' => $order->display_order_number,
                    'user_id' => $order->user?->id,
                    'recipient' => $order->user?->email,
                    'payment_method' => $order->payment_method,
                ]);
                Mail::to($order->user->email)->send(new OrderPlacedMail($order));
                Log::info('Order confirmation email sent (COD)', [
                    'order_id' => $order->id,
                    'order_number' => $order->display_order_number,
                    'recipient' => $order->user?->email,
                ]);
            } catch (\Throwable $e) {
                Log::error('Order confirmation email failed (COD)', [
                    'order_id' => $order->id,
                    'order_number' => $order->display_order_number,
                    'recipient' => $order->user?->email,
                    'error' => $e->getMessage(),
                ]);
                report($e);
            }

            return redirect()
                ->route('checkout.confirmation', $order)
                ->with('success', 'Order #' . $order->display_order_number . ' placed successfully! Check your email for confirmation.');

        } catch (\Exception $e) {
            Log::error('Checkout Failed: ' . $e->getMessage());

            // Keep user on checkout payment step with a clear, field-scoped error.
            return redirect()
                ->route('checkout')
                ->withInput()
                ->withErrors([
                    'payment_method' => 'Unable to initialize PayMongo checkout right now. Please try again.',
                ]);
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
            'City of ParaÃ±aque' => 0,
            'City of Las PiÃ±as' => 0,
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


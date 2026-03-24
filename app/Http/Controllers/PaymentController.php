<?php

namespace App\Http\Controllers;

use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\Product;
use App\Services\PaymongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class PaymentController extends Controller
{
    public function paymongoSuccess(Request $request, PaymongoService $paymongoService)
    {
        $orderId = (int) $request->query('order');
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('dashboard')->with('error', 'Order not found.');
        }

        // Webhooks can be delayed/missed in some deployments.
        // As a fallback, verify checkout session directly when user lands on success URL.
        if ($order->payment_status !== 'paid' && $order->paymongo_checkout_session_id) {
            try {
                $checkoutSession = $paymongoService->getCheckoutSession((string) $order->paymongo_checkout_session_id);
                $payments = data_get($checkoutSession, 'attributes.payments', []);

                if (is_array($payments) && isset($payments[0])) {
                    $firstPayment = $payments[0];
                    $paymentStatus = (string) (data_get($firstPayment, 'attributes.status') ?? data_get($firstPayment, 'status', ''));

                    if ($paymentStatus === '' || in_array($paymentStatus, ['paid', 'succeeded', 'captured'], true)) {
                        $paymentId = (string) (data_get($firstPayment, 'id') ?? '');
                        $paymentChannel = $this->resolvePaymentChannel(is_array($firstPayment) ? $firstPayment : []);
                        $this->finalizePaidOrder($order, $paymentId, $paymentChannel);
                        $order->refresh();
                    }
                }
            } catch (\Throwable $e) {
                Log::warning('PayMongo success fallback sync failed', [
                    'order_id' => $order->id,
                    'message' => $e->getMessage(),
                ]);
            }
        }

        $message = $order->payment_status === 'paid'
            ? 'Payment received for Order #' . $order->display_order_number . '.'
            : 'Payment is being processed for Order #' . $order->display_order_number . '. We will update your order shortly.';

        if ($order->payment_status === 'paid') {
            $request->session()->forget('cart');
        }

        if ($order->status === 'awaiting_payment') {
            return redirect()->route('orders.index')
                ->with('info', 'Waiting for payment confirmation. The order will appear once payment is completed.');
        }

        return redirect()->route('orders.show', $order)->with('success', $message);
    }

    public function paymongoCancel(Request $request)
    {
        $orderId = (int) $request->query('order');
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('dashboard')->with('error', 'Order not found.');
        }

        if ($order->status === 'awaiting_payment' && $order->payment_status === 'pending') {
            $order->items()->delete();
            $order->delete();

            return redirect()->route('checkout')
                ->with('error', 'Payment was canceled. Your order was not placed.');
        }

        return redirect()->route('orders.show', $order)
            ->with('error', 'Payment was canceled.');
    }

    public function paymongoWebhook(Request $request, PaymongoService $paymongoService)
    {
        $payload = $request->getContent();
        $signature = $request->header('Paymongo-Signature') ?? $request->header('paymongo-signature');

        if (!$paymongoService->isValidWebhookSignature($payload, $signature)) {
            Log::warning('Invalid PayMongo webhook signature.');
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        /** @var array<string,mixed> $event */
        $event = $request->json()->all();
        $eventType = (string) data_get($event, 'data.attributes.type', '');
        $checkoutSessionId = (string) data_get($event, 'data.attributes.data.id', '');

        if ($checkoutSessionId === '') {
            return response()->json(['message' => 'No checkout session id in payload'], 202);
        }

        $order = Order::where('paymongo_checkout_session_id', $checkoutSessionId)->first();
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 202);
        }

        if (str_contains($eventType, 'paid')) {
            try {
                $firstPayment = data_get($event, 'data.attributes.data.attributes.payments.0', []);
                $paymentId = (string) data_get($firstPayment, 'id', '');
                $paymentChannel = $this->resolvePaymentChannel(is_array($firstPayment) ? $firstPayment : []);

                $this->finalizePaidOrder($order, $paymentId, $paymentChannel);
            } catch (\Throwable $e) {
                Log::error('Failed to finalize paid PayMongo order', [
                    'order_id' => $order->id,
                    'message' => $e->getMessage(),
                ]);

                return response()->json(['message' => 'Finalize failed'], 202);
            }
        } elseif (str_contains($eventType, 'failed')) {
            if ($order->status === 'awaiting_payment') {
                $order->items()->delete();
                $order->delete();
            } else {
                $order->update(['payment_status' => 'failed']);
            }
        } elseif (str_contains($eventType, 'canceled') || str_contains($eventType, 'expired')) {
            if ($order->status === 'awaiting_payment') {
                $order->items()->delete();
                $order->delete();
            } else {
                $order->update(['payment_status' => 'canceled']);
            }
        }

        return response()->json(['message' => 'Webhook received'], 200);
    }

    private function finalizePaidOrder(Order $order, string $paymentIntentId, string $paymentChannel = 'online'): void
    {
        DB::transaction(function () use ($order, $paymentIntentId, $paymentChannel): void {
            $order->loadMissing('items.product');

            if ($order->status === 'awaiting_payment') {
                foreach ($order->items as $item) {
                    $product = Product::find($item->product_id);
                    if (!$product) {
                        throw new RuntimeException('Product not found for order item ' . $item->id);
                    }

                    $qty = (int) $item->quantity;
                    if ((int) $product->stock_quantity < $qty) {
                        throw new RuntimeException('Insufficient stock for product ' . $product->id);
                    }

                    $previousStock = (int) $product->stock_quantity;
                    $product->decrement('stock_quantity', $qty);

                    InventoryLog::create([
                        'product_id' => $product->id,
                        'user_id' => $order->user_id,
                        'quantity_change' => -$qty,
                        'previous_stock' => $previousStock,
                        'new_stock' => $previousStock - $qty,
                        'reason' => 'Order paid',
                        'reference_id' => $order->id,
                    ]);
                }
            }

            $order->update([
                'payment_status' => 'paid',
                'payment_channel' => $order->payment_method === 'paymongo'
                    ? ($paymentChannel !== '' ? $paymentChannel : ($order->payment_channel ?: 'online'))
                    : 'cod',
                'status' => $order->status === 'awaiting_payment' || $order->status === 'pending'
                    ? 'processing'
                    : $order->status,
                'paymongo_payment_intent_id' => $paymentIntentId !== '' ? $paymentIntentId : $order->paymongo_payment_intent_id,
            ]);
        });
    }

    /**
     * @param array<string, mixed> $paymentPayload
     */
    private function resolvePaymentChannel(array $paymentPayload): string
    {
        $rawChannel = (string) (
            data_get($paymentPayload, 'attributes.source.type')
            ?? data_get($paymentPayload, 'source.type')
            ?? data_get($paymentPayload, 'attributes.payment_method_used')
            ?? data_get($paymentPayload, 'attributes.payment_method')
            ?? data_get($paymentPayload, 'attributes.channel')
            ?? data_get($paymentPayload, 'attributes.payment_method_type')
            ?? ''
        );

        if ($rawChannel === '') {
            return 'online';
        }

        $normalized = strtolower(trim($rawChannel));
        $normalized = preg_replace('/[^a-z0-9]+/', '_', $normalized) ?? 'online';
        $normalized = trim($normalized, '_');

        return $normalized !== '' ? $normalized : 'online';
    }
}

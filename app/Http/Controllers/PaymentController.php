<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\PaymongoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function paymongoSuccess(Request $request)
    {
        $orderId = (int) $request->query('order');
        $order = Order::where('id', $orderId)
            ->where('user_id', Auth::id())
            ->first();

        if (!$order) {
            return redirect()->route('dashboard')->with('error', 'Order not found.');
        }

        $message = $order->payment_status === 'paid'
            ? 'Payment received for Order #' . $order->display_order_number . '.'
            : 'Payment is being processed for Order #' . $order->display_order_number . '. We will update your order shortly.';

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

        return redirect()->route('orders.show', $order)
            ->with('error', 'Payment was canceled. You can retry payment from your order page.');
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
            $order->update([
                'payment_status' => 'paid',
                'status' => $order->status === 'pending' ? 'processing' : $order->status,
                'paymongo_payment_intent_id' => data_get($event, 'data.attributes.data.attributes.payments.0.id'),
            ]);
        } elseif (str_contains($eventType, 'failed')) {
            $order->update(['payment_status' => 'failed']);
        } elseif (str_contains($eventType, 'canceled') || str_contains($eventType, 'expired')) {
            $order->update(['payment_status' => 'canceled']);
        }

        return response()->json(['message' => 'Webhook received'], 200);
    }
}

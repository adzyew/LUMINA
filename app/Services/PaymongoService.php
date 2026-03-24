<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class PaymongoService
{
    private const BASE_URL = 'https://api.paymongo.com/v1';

    public function createCheckoutSession(Order $order, string $successUrl, string $cancelUrl): array
    {
        $secretKey = (string) config('services.paymongo.secret_key');
        if ($secretKey === '') {
            throw new RuntimeException('PayMongo secret key is not configured.');
        }

        $lineItems = [];
        foreach ($order->items as $item) {
            $lineItems[] = [
                'currency' => 'PHP',
                'amount' => (int) round(((float) $item->unit_price) * 100),
                'name' => $item->product?->name ?? ('Product #' . $item->product_id),
                'quantity' => (int) $item->quantity,
            ];
        }

        if ($lineItems === []) {
            throw new RuntimeException('Cannot create PayMongo checkout session with no line items.');
        }

        $paymentMethodTypes = config('services.paymongo.payment_method_types', ['card', 'gcash']);
        if (!is_array($paymentMethodTypes) || $paymentMethodTypes === []) {
            $paymentMethodTypes = ['card', 'gcash'];
        }

        $payload = [
            'data' => [
                'attributes' => [
                    'line_items' => $lineItems,
                    'payment_method_types' => array_values($paymentMethodTypes),
                    'success_url' => $successUrl,
                    'cancel_url' => $cancelUrl,
                    'description' => 'Order #' . $order->display_order_number,
                    'reference_number' => $order->display_order_number,
                    'send_email_receipt' => false,
                    'show_description' => true,
                    'show_line_items' => true,
                    'billing' => [
                        'name' => $order->user?->name,
                        'email' => $order->contact_email ?? $order->user?->email,
                        'phone' => $order->contact_phone,
                    ],
                ],
            ],
        ];

        /** @var Response $response */
        $response = Http::withBasicAuth($secretKey, '')
            ->acceptJson()
            ->asJson()
            ->post(self::BASE_URL . '/checkout_sessions', $payload);

        if ($response->failed()) {
            throw new RuntimeException('PayMongo checkout session creation failed: ' . $response->body());
        }

        /** @var array<string,mixed> $data */
        $data = $response->json('data') ?? [];

        return $data;
    }

    public function isValidWebhookSignature(string $payload, ?string $signatureHeader): bool
    {
        $secret = (string) config('services.paymongo.webhook_secret');
        if ($secret === '' || $signatureHeader === null || $signatureHeader === '') {
            return false;
        }

        $parts = [];
        foreach (explode(',', $signatureHeader) as $part) {
            $pair = explode('=', trim($part), 2);
            if (count($pair) === 2) {
                $parts[$pair[0]] = $pair[1];
            }
        }

        $timestamp = $parts['t'] ?? null;
        $signature = $parts['te'] ?? ($parts['v1'] ?? null);
        if ($timestamp === null || $signature === null) {
            return false;
        }

        $signedPayload = $timestamp . '.' . $payload;
        $computed = hash_hmac('sha256', $signedPayload, $secret);

        return hash_equals($computed, $signature);
    }
}

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Lumina Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 2px solid #fbbf24; padding-bottom: 15px; margin-bottom: 25px; }
        .brand { font-size: 24px; font-weight: bold; color: #1a1a1a; }
        h1 { color: #1a1a1a; font-size: 20px; margin: 0 0 10px 0; }
        .order-box { background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; border: 1px solid #e5e7eb; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { color: #6b7280; font-weight: 600; font-size: 12px; text-transform: uppercase; }
        .money-row { display: flex; justify-content: space-between; margin: 4px 0; }
        .total { font-size: 18px; font-weight: bold; color: #1a1a1a; margin-top: 10px; }
        .address { background: #f3f4f6; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .cta { margin: 16px 0 6px 0; }
        .cta a { display: inline-block; background: #111827; color: #ffffff; text-decoration: none; padding: 10px 14px; border-radius: 6px; font-size: 14px; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    @php
        $subtotal = $order->items->sum(fn($item) => (float) $item->unit_price * (int) $item->quantity);
        $discount = (float) ($order->discount_amount ?? 0);
        $shipping = 0.00;
    @endphp

    <div class="header">
        <span class="brand">Lumina</span> Jewelry
    </div>

    <h1>Your Lumina Order Confirmation</h1>
    <p>Dear {{ $order->user->name ?? 'Customer' }},</p>
    <p>Thank you for shopping with Lumina. We are pleased to confirm that we have successfully received your order <strong>#{{ $order->display_order_number }}</strong>.</p>
    <p>We are currently preparing your items and will notify you as soon as they ship.</p>

    <div class="order-box">
        <strong>Order Summary</strong>
        <table>
            <thead>
                <tr><th>Item</th><th>Quantity</th><th>Price</th></tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name ?? 'Product' }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="money-row"><span>Subtotal:</span><span>Php {{ number_format($subtotal, 2) }}</span></div>
        <div class="money-row"><span>Shipping:</span><span>Php {{ number_format($shipping, 2) }}</span></div>
        @if($discount > 0)
            <div class="money-row"><span>Discount:</span><span>- Php {{ number_format($discount, 2) }}</span></div>
        @endif
        <p class="total">Total: Php {{ number_format($order->total_price, 2) }}</p>

        <div class="address">
            <strong>Shipping Address</strong><br>
            {{ $order->formatted_shipping_address }}<br>
            @if($order->contact_phone)
            <strong>Contact:</strong> {{ $order->contact_phone }}
            @endif
        </div>
    </div>

    <div class="cta">
        <a href="{{ $orderStatusUrl ?? url('/dashboard') }}">Check Your Order Status</a>
    </div>

    <p>If you have any questions or need to make changes to your order, please reply directly to this email or contact our support team at {{ $supportEmail ?? config('mail.from.address') }}.</p>

    <div class="footer">
        Warm regards,<br>
        The Lumina Team<br>
        <a href="{{ $websiteUrl ?? config('app.url') }}">{{ $websiteUrl ?? config('app.url') }}</a><br><br>
        &copy; {{ date('Y') }} Lumina Jewelry. All rights reserved.
    </div>
</body>
</html>

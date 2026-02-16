<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 2px solid #fbbf24; padding-bottom: 15px; margin-bottom: 25px; }
        .brand { font-size: 24px; font-weight: bold; color: #1a1a1a; }
        h1 { color: #1a1a1a; font-size: 20px; margin: 0 0 10px 0; }
        .order-box { background: #f8f9fa; border-radius: 8px; padding: 20px; margin: 20px 0; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th, td { padding: 10px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { color: #6b7280; font-weight: 600; font-size: 12px; text-transform: uppercase; }
        .total { font-size: 18px; font-weight: bold; color: #1a1a1a; }
        .address { background: #f3f4f6; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <span class="brand">Lumina</span> Jewelry
    </div>
    <h1>Thank you for your order!</h1>
    <p>Hi {{ $order->user->name ?? 'Customer' }},</p>
    <p>Your order <strong>#{{ $order->id }}</strong> has been placed successfully. We'll notify you when it ships.</p>

    <div class="order-box">
        <strong>Order Summary</strong>
        <table>
            <thead>
                <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
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
        <p class="total">Total: ₱{{ number_format($order->total_price, 2) }}</p>

        <div class="address">
            <strong>Shipping Address</strong><br>
            {{ $order->formatted_shipping_address }}<br>
            @if($order->contact_phone)
            <strong>Contact:</strong> {{ $order->contact_phone }}
            @endif
        </div>
    </div>

    <p>Track your order status in your account dashboard.</p>
    <div class="footer">
        &copy; {{ date('Y') }} Lumina Jewelry. All rights reserved.
    </div>
</body>
</html>

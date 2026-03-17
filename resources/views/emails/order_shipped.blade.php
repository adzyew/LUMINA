<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Order Has Shipped</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 2px solid #fbbf24; padding-bottom: 15px; margin-bottom: 25px; }
        .brand { font-size: 24px; font-weight: bold; color: #1a1a1a; }
        h1 { color: #1a1a1a; font-size: 20px; margin: 0 0 10px 0; }
        .tracking { background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 20px; margin: 20px 0; text-align: center; }
        .tracking-code { font-size: 22px; font-weight: bold; letter-spacing: 2px; color: #1a1a1a; }
        .address { background: #f3f4f6; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="header">
        <span class="brand">Lumina</span> Jewelry
    </div>
    <h1>Your order is on its way!</h1>
    <p>Hi {{ $order->user->name ?? 'Customer' }},</p>
    <p>Good news! Your order <strong>#{{ $order->display_order_number }}</strong> has shipped and is on its way to you.</p>

    @if($order->tracking_number)
    <div class="tracking">
        <p style="margin: 0 0 8px 0; font-size: 14px; color: #6b7280;">Tracking Number</p>
        <p class="tracking-code">{{ $order->tracking_number }}</p>
        <p style="margin: 12px 0 0 0; font-size: 13px;">Use this number to track your package with the carrier.</p>
    </div>
    @endif

    <div class="address">
        <strong>Delivery Address</strong><br>
        {{ $order->formatted_shipping_address }}<br>
        @if($order->contact_phone)
        <strong>Contact:</strong> {{ $order->contact_phone }}
        @endif
    </div>

    <p>Thank you for shopping with Lumina. We hope you love your purchase!</p>
    <div class="footer">
        &copy; {{ date('Y') }} Lumina Jewelry. All rights reserved.
    </div>
</body>
</html>

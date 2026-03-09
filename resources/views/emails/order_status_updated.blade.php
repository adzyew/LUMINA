<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status Updated</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 2px solid #fbbf24; padding-bottom: 15px; margin-bottom: 25px; }
        .brand { font-size: 24px; font-weight: bold; color: #1a1a1a; }
        h1 { color: #1a1a1a; font-size: 20px; margin: 0 0 10px 0; }
        .status-box { background: #f8f9fa; border-radius: 8px; padding: 16px; margin: 20px 0; border: 1px solid #e5e7eb; }
        .status { font-size: 18px; font-weight: bold; color: #111827; }
        .status-note { color: #6b7280; font-size: 13px; }
        .items { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 14px; margin: 14px 0; }
        .item-row { margin: 4px 0; }
        .address { background: #f3f4f6; padding: 15px; border-radius: 6px; margin: 15px 0; }
        .cta { margin: 16px 0 6px 0; }
        .cta a { display: inline-block; background: #111827; color: #ffffff; text-decoration: none; padding: 10px 14px; border-radius: 6px; font-size: 14px; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 12px; color: #6b7280; }
    </style>
</head>
<body>
    @php
        $status = strtolower((string) $order->status);

        $headings = [
            'pending' => 'We Received Your Order',
            'confirmed' => 'Your Order Is Confirmed',
            'processing' => 'Your Order Is Being Prepared',
            'shipped' => 'Your Order Is On Its Way',
            'delivered' => 'Your Order Has Been Delivered',
            'cancelled' => 'Your Order Has Been Cancelled',
        ];

        $messages = [
            'pending' => 'Thank you for your purchase. Your order is now in our queue and awaiting confirmation.',
            'confirmed' => 'Great news. We have confirmed your order and will prepare it for dispatch shortly.',
            'processing' => 'Your items are now being packed by our team and are almost ready for shipment.',
            'shipped' => 'Great news. Your order has been handed over to our shipping partner and is now in transit.',
            'delivered' => 'Your package has been marked as delivered. We hope you love your Lumina pieces.',
            'cancelled' => 'This order has been cancelled. If this was unexpected, please contact our support team.',
        ];

        $heading = $headings[$status] ?? 'Order Status Updated';
        $message = $messages[$status] ?? 'Your order status has changed. Please review the latest order details below.';
    @endphp

    <div class="header">
        <span class="brand">Lumina</span> Jewelry
    </div>

    <h1>{{ $heading }}</h1>
    <p>Dear {{ $order->user->name ?? 'Customer' }},</p>
    <p>{{ $message }}</p>
    <p>Order reference: <strong>#{{ $order->id }}</strong></p>

    <div class="status-box">
        <p class="status" style="margin: 0 0 8px 0;">
            Status change: {{ ucfirst($previousStatus) }} -> {{ ucfirst($order->status) }}
        </p>

        @if($status === 'shipped')
            <p class="status-note" style="margin: 0 0 6px 0;">Tracking Information</p>
            <p class="status-note" style="margin: 0;">Courier: <strong>{{ $order->courier_name ?: 'Standard Carrier' }}</strong></p>
            @if($order->tracking_number)
                <p class="status-note" style="margin: 0;">Tracking Number: <strong>{{ $order->tracking_number }}</strong></p>
            @endif
            @php($resolvedTrackingUrl = $order->tracking_url ?: ($trackingUrl ?? null))
            @if(!empty($resolvedTrackingUrl))
                <p class="status-note" style="margin: 4px 0 0 0;">Track here: <a href="{{ $resolvedTrackingUrl }}">{{ $resolvedTrackingUrl }}</a></p>
            @endif
            <p class="status-note" style="margin: 8px 0 0 0;">Please allow up to 24 hours for carrier tracking updates.</p>
        @elseif($order->tracking_number)
            <p class="status-note" style="margin: 0;">Tracking Number: <strong>{{ $order->tracking_number }}</strong></p>
        @endif
    </div>

    @if($status === 'shipped' || $status === 'processing' || $status === 'delivered')
        <div class="items">
            <strong>Items in this order</strong>
            @foreach($order->items as $item)
                <div class="item-row">{{ $item->product->name ?? 'Product' }} - {{ $item->quantity }} x Php {{ number_format($item->unit_price, 2) }}</div>
            @endforeach
        </div>
    @endif

    <div class="address">
        <strong>Shipping Address</strong><br>
        {{ $order->formatted_shipping_address }}<br>
        @if($order->contact_phone)
            <strong>Contact:</strong> {{ $order->contact_phone }}
        @endif
    </div>

    <div class="cta">
        <a href="{{ $orderStatusUrl ?? url('/dashboard') }}">View Order Status</a>
    </div>
    <p>If you need help, you can reply directly to this email or contact us at {{ $supportEmail ?? config('mail.from.address') }}.</p>

    <div class="footer">
        Best regards,<br>
        The Lumina Team<br>
        <a href="{{ $websiteUrl ?? config('app.url') }}">{{ $websiteUrl ?? config('app.url') }}</a><br><br>
        &copy; {{ date('Y') }} Lumina Jewelry. All rights reserved.
    </div>
</body>
</html>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Lumina Jewelries Accessories | Order #{{ $order->display_order_number }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; font-size: 12px; margin: 0; }
        .page { padding: 28px; }
        .title { font-size: 24px; font-weight: 700; margin-bottom: 7px; color: #1f2937; }
        .muted { color: #6b7280; }
        .section { margin-top: 20px; border: 1px solid #e5e7eb; border-radius: 8px; }
        .section-header { padding: 10px 12px; border-bottom: 1px solid #e5e7eb; font-size: 13px; font-weight: 700; background: #f9fafb; }
        .section-body { padding: 12px; }
        .row { width: 100%; border-collapse: collapse; }
        .row td { padding: 5px 0; vertical-align: top; }
        .label { width: 170px; font-weight: 700; color: #4b5563; }
        table.items { width: 100%; border-collapse: collapse; }
        table.items th, table.items td { padding: 8px; border-bottom: 1px solid #e5e7eb; text-align: left; }
        table.items th { background: #f9fafb; font-size: 11px; text-transform: uppercase; color: #4b5563; }
        .text-right { text-align: right; }
        .total { font-size: 16px; font-weight: 700; color: #b45309; }
        .footer { margin-top: 24px; font-size: 11px; color: #6b7280; }
    </style>
</head>
<body>
    <div class="page">
        <div class="title">Lumina Jewelry Accessories</div>
        <div class="muted">Invoice for Order #{{ $order->display_order_number }}</div>
        <div class="muted">Generated on {{ now()->format('F d, Y h:i A') }}</div>

        <div class="section">
            <div class="section-header">Order Information</div>
            <div class="section-body">
                <table class="row">
                    <tr><td class="label">Delivery Address:</td><td>{{ $order->formatted_shipping_address ?: 'Not provided' }}</td></tr>
                    <tr><td class="label">Contact Number:</td><td>{{ $order->contact_phone ?: 'Not provided' }}</td></tr>
                    <tr><td class="label">Email Address:</td><td>{{ $order->contact_email ?: 'Not provided' }}</td></tr>
                    <tr><td class="label">Payment Method:</td><td>{{ $order->payment_display }}</td></tr>
                    <tr><td class="label">Payment Status:</td><td>{{ ucfirst((string) $order->payment_status) }}</td></tr>
                    <tr><td class="label">Order Status:</td><td>{{ ucfirst(str_replace('_', ' ', (string) $order->status)) }}</td></tr>
                    <tr><td class="label">Tracking Number:</td><td>{{ $order->tracking_number ?: 'Pending assignment' }}</td></tr>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Items Ordered</div>
            <div class="section-body" style="padding:0;">
                <table class="items">
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Unit Price</th>
                            <th class="text-right">Line Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product?->name ?? ('Product #' . $item->product_id) }}</td>
                                <td class="text-right">{{ (int) $item->quantity }}</td>
                                <td class="text-right">Php {{ number_format((float) $item->unit_price, 2) }}</td>
                                <td class="text-right">Php {{ number_format((float) $item->unit_price * (int) $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="section">
            <div class="section-header">Order Summary</div>
            <div class="section-body">
                <table class="row">
                    <tr>
                        <td class="label">Subtotal:</td>
                        <td class="text-right">Php {{ number_format((float) $order->total_price + (float) $order->discount_amount, 2) }}</td>
                    </tr>
                    @if((float) $order->discount_amount > 0)
                        <tr>
                            <td class="label">Discount:</td>
                            <td class="text-right">- Php {{ number_format((float) $order->discount_amount, 2) }}</td>
                        </tr>
                    @endif
                    @if((int) $order->points_used > 0)
                        <tr>
                            <td class="label">Points Used:</td>
                            <td class="text-right">{{ (int) $order->points_used }} pts</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="label">Total Amount:</td>
                        <td class="text-right total">Php {{ number_format((float) $order->total_price, 2) }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">Thank you for shopping with Lumina Jewelry Accessories <br> luminajewelriesaccessories@gmail.com <br> luminajewels.up.railway.app</div>
    </div>
</body>
</html>

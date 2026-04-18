<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Today's Lumina Promo</title>
</head>
<body style="margin:0;padding:24px;background:#f8fafc;font-family:Arial,sans-serif;color:#111827;">
    <table role="presentation" cellpadding="0" cellspacing="0" width="100%" style="max-width:600px;margin:0 auto;background:#ffffff;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;">
        <tr>
            <td style="padding:20px 24px;background:#fef3c7;border-bottom:1px solid #fde68a;">
                <h1 style="margin:0;font-size:24px;line-height:1.25;color:#92400e;">Today's Promo Code</h1>
            </td>
        </tr>
        <tr>
            <td style="padding:24px;">
                <p style="margin:0 0 12px;font-size:16px;color:#111827;">Hi {{ $user->first_name ?: 'Lumina Customer' }},</p>
                <p style="margin:0 0 18px;font-size:15px;color:#374151;">Here is your active Lumina promo code for today:</p>

                <div style="margin:0 0 18px;padding:14px 16px;border:1px dashed #f59e0b;border-radius:10px;background:#fffbeb;text-align:center;">
                    <div style="font-size:28px;font-weight:700;letter-spacing:2px;color:#b45309;">{{ $promo->code }}</div>
                    <div style="margin-top:6px;font-size:14px;color:#78350f;">{{ number_format((float) $promo->discount_percent, 0) }}% OFF</div>
                </div>

                <p style="margin:0 0 8px;font-size:14px;color:#4b5563;">Valid until: <strong>{{ optional($promo->expires_at)->format('M d, Y h:i A') }}</strong></p>
                <p style="margin:0 0 18px;font-size:14px;color:#4b5563;">Use this code in checkout (payment step).</p>

                <a href="{{ route('checkout') }}" style="display:inline-block;padding:12px 18px;border-radius:10px;background:#f59e0b;color:#111827;text-decoration:none;font-weight:700;">
                    Use Promo Now
                </a>
            </td>
        </tr>
    </table>
</body>
</html>


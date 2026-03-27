<!DOCTYPE html>
<html>
<head>
    @include('partials.favicon')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; }
        .otp-box { background: #fffaf0; border: 1px solid #f3d08a; border-radius: 10px; padding: 18px; margin: 18px 0; text-align: center; }
        .otp-code { font-size: 34px; letter-spacing: 6px; font-weight: 700; color: #8a4b1f; margin: 4px 0; }
        .note { color: #6b7280; font-size: 13px; }
    </style>
</head>
<body>
    @include('emails.partials.header', ['emailHeaderTitle' => 'Security Verification'])

    <h2 style="margin:0 0 8px 0;color:#1a1a1a;">Your Verification Code</h2>
    <p>Use the code below to verify your account:</p>

    <div class="otp-box">
        <div class="otp-code">{{ $otp }}</div>
        <div class="note">This code expires in 5 minutes.</div>
    </div>

    <p>If you did not request this code, you can safely ignore this email.</p>

    @include('emails.partials.footer')
</body>
</html>

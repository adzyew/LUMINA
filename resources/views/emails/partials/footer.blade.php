@php
    $support = $supportEmail ?? config('mail.from.address');
    $site = $websiteUrl ?? config('app.url');

    $parsedHost = $site ? parse_url($site, PHP_URL_HOST) : null;
    $isLocalSite = in_array($parsedHost, ['localhost', '127.0.0.1'], true);
@endphp

<div style="margin-top:30px;padding-top:20px;border-top:1px solid #e5e7eb;font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#6b7280;">
    Best regards,<br>
    The Lumina Team<br>
    @if($site && !$isLocalSite)
        <a href="{{ $site }}" style="color:#8a4b1f;text-decoration:none;">{{ $site }}</a><br>
    @endif
    @if($support)
        Support: <a href="mailto:{{ $support }}" style="color:#8a4b1f;text-decoration:none;">{{ $support }}</a><br>
    @endif
    <br>
    &copy; {{ date('Y') }} Lumina Jewelry. All rights reserved.
</div>
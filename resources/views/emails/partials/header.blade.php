@php
    $headerTitle = $emailHeaderTitle ?? null;
    $headerFile = 'IMAGES/Lumina Email Header.png';

    if (!empty($emailHeaderImageUrl)) {
        $headerImage = $emailHeaderImageUrl;
    } else {
        // Use an absolute public URL for maximum inbox compatibility.
        $appUrl = rtrim((string) config('app.url'), '/');
        $headerImage = $appUrl . '/' . str_replace(' ', '%20', ltrim($headerFile, '/'));
    }
@endphp

<div style="margin:0 0 16px 0;border:1px solid #e5e7eb;border-radius:12px;overflow:hidden;background:#ffffff;">
    <img
        src="{{ $headerImage }}"
        alt="Lumina Email Header"
        style="display:block;width:100%;max-height:200px;height:220px;object-fit:cover;object-position:center;"
    >
    @if($headerTitle)
        <div style="padding:12px 16px;background:#fffaf0;border-top:1px solid #f3e8c8;">
            <p style="margin:0;font-family:Arial,Helvetica,sans-serif;font-size:13px;letter-spacing:0.08em;text-transform:uppercase;color:#8a4b1f;font-weight:700;">
                {{ $headerTitle }}
            </p>
        </div>
    @endif
</div>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Lumina')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    <link rel="shortcut icon" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-stone-100 text-gray-900 font-sans antialiased min-h-screen">
    @include('partials.navbar')
    <main class="min-h-screen pt-24 pb-12">
        @yield('content')
    </main>
    @include('partials.footer')
</body>
</html>

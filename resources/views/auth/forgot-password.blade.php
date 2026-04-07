<!doctype html>
<html lang="en">
<head>
    @include('partials.favicon')
    <title>Forgot Password | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased flex flex-col min-h-screen transition-colors">
    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-linear-to-b backdrop-blur-sm "></div>
    </div>
    @include('partials.navbar')

    <div class="grow flex items-center justify-center py-24 px-4 mt-10">
        <div class="w-full max-w-md bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-amber-200/50 p-8 sm:p-10">
            <div class="text-center mb-8">
                <div class="inline-flex justify-center w-16 h-16 rounded-full bg-amber-100 mb-4 text-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-9 mt-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-playfair font-bold text-gray-900 mb-2">Forgot Password</h2>
                <p class="text-gray-600 text-sm">Enter your email and we'll send you a verification code to reset your password.</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="mb-6 p-4 bg-amber-50 border border-amber-200 rounded-xl text-amber-700 text-sm">{{ session('info') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                    <ul class="list-disc list-inside space-y-1 text-sm">{{ $errors->first() }}</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-gray-700 text-sm font-medium mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                        placeholder="you@example.com">
                </div>
                <button type="submit" class="w-full py-4 bg-amber-300 text-black font-medium rounded-xl hover:bg-amber-400 transition-colors">
                    Send OTP to Email
                </button>
            </form>

            <a href="{{ route('login') }}" class="block mt-6 text-center text-sm text-gray-500 hover:text-amber-600 transition-colors">← Back to Login</a>
        </div>
    </div>
</body>
</html>

<!doctype html>
<html lang="en">
<head>
    <title>Forgot Password | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white font-sans antialiased flex flex-col min-h-screen transition-colors">
    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-linear-to-b from-amber-300/20 via-black/70 to-black/90"></div>
    </div>
    @include('partials.navbar')

    <div class="grow flex items-center justify-center py-24 px-4">
        <div class="w-full max-w-md bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-amber-300/20 p-8 sm:p-10">
            <div class="text-center mb-8">
                <div class="inline-flex justify-center w-16 h-16 rounded-full bg-amber-300/10 mb-4 text-amber-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
                </div>
                <h2 class="text-3xl font-playfair font-bold text-white mb-2">Forgot Password</h2>
                <p class="text-gray-400 text-sm">Enter your email and we'll send you a verification code to reset your password.</p>
            </div>

            @if(session('error'))
                <div class="mb-6 p-4 bg-red-500/20 border border-red-500/40 rounded-xl text-red-400 text-sm">{{ session('error') }}</div>
            @endif
            @if(session('info'))
                <div class="mb-6 p-4 bg-amber-500/20 border border-amber-500/40 rounded-xl text-amber-400 text-sm">{{ session('info') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-6 p-4 bg-red-500/20 border border-red-500/40 rounded-xl text-red-400 text-sm">
                    <ul class="list-disc list-inside space-y-1 text-sm">{{ $errors->first() }}</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-gray-300 text-sm font-medium mb-2">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 bg-black border border-gray-700 rounded-xl text-white placeholder-gray-500 focus:outline-none focus:border-amber-300 focus:ring-1 focus:ring-amber-300"
                        placeholder="you@example.com">
                </div>
                <button type="submit" class="w-full py-4 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors">
                    Send OTP to Email
                </button>
            </form>

            <a href="{{ route('login') }}" class="block mt-6 text-center text-sm text-gray-500 hover:text-amber-300 transition-colors">← Back to Login</a>
        </div>
    </div>
    @include('partials.footer')
</body>
</html>

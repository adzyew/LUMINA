<!doctype html>
<html>
<head>
    <title>Login | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class=" text-white font-sans antialiased flex flex-col min-h-screen">
    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES\BG.png') }}" alt="Luxury background" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-linear-to-b from-amber-300/20 via-black/70 to-black/90"></div>
    </div>
    @include('partials.navbar')

    <div class="grow flex items-center justify-center py-24 sm:px-6 lg:px-8 relative">
        <div class="grid grid-cols-1 md:grid-cols-2 w-full max-w-4xl inset-0 bg-amber-800/10 rounded-2xl shadow-2xl overflow-hidden border border-white/10 relative z-10">
            
            <div class="hidden md:flex flex-col justify-center items-center p-12 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-amber-300/10 blur-sm"></div>
                <div class="relative z-10">
                    <h2 class="text-4xl font-playfair font-bold mb-4 text-transparent bg-clip-text bg-linear-to-r from-amber-300 to-amber-500">Welcome Back</h2>
                    <p class="text-gray-400">Access your curated collection and exclusive offers.</p>
                </div>
            </div>

            <div class="p-8 sm:p-12 flex flex-col justify-center ">
                <h2 class="text-3xl font-playfair font-bold text-amber-300 mb-6 text-center md:text-left">Login</h2>

                <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
                    @csrf
                    @session("error")
                        <div class="bg-red-500/20 border border-red-500 text-red-300 px-4 py-3 rounded-lg mb-4">
                            {{ $value }}
                        </div>
                    @endsession
                    <div>
                        <label class="block text-lg text-gray-100 mb-2">Email Address</label>
                        <input type="email" name="email" class="w-full px-4 py-3 bg-black border border-gray-700 rounded-lg focus:outline-none focus:border-amber-300 focus:duration-500 text-white placeholder-gray-600 transition-colors" placeholder="you@example.com">
                        @error("email")
                            <span class="text-danger text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <div class="mb-6">
                            <label for="password" class="block text-gray-400 text-sm font-medium mb-2">Password</label>
                            <div class="relative">
                                <input id="password" type="password" name="password" required 
                                    class="w-full bg-gray-900 border border-gray-700 text-white text-sm rounded-lg focus:ring-amber-300 focus:border-amber-300 block p-3 pr-10 placeholder-gray-600" 
                                    placeholder="••••••••">

                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-amber-300 focus:outline-none">
                                    <svg id="eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    
                                    <svg id="eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </button>
                            </div>
                        </div>
                            <a href="#" class="text-xs text-amber-300 hover:text-white transition-colors">Forgot password?</a>
                        </div>
                    </div>

                    <button type="submit" class="w-full py-4 bg-linear-to-r from-amber-300 to-amber-500 text-gray-100 text-lg font-bold rounded-lg hover:bg-amber-400 transition-all transform hover:scale-[1.02] shadow-lg shadow-amber-300/20">
                        Log In
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-100 text-sm">
                        Don't have an account? 
                        <a href="{{ route('register.form') }}" class="text-amber-300 hover:text-white font-medium transition-colors">Create one now</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>

<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eye-open');
        const eyeClosed = document.getElementById('eye-closed');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeOpen.classList.remove('hidden');
            eyeClosed.classList.add('hidden');
        } else {
            passwordInput.type = 'password';
            eyeOpen.classList.add('hidden');
            eyeClosed.classList.remove('hidden');
        }
    }
</script>
</html>
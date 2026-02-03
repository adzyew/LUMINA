<!doctype html>
<html>
<head>
    <title>Register | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-black text-white font-sans antialiased flex flex-col min-h-screen">

    @include('partials.navbar')

    <div class="grow flex items-center justify-center py-24 sm:px-6 lg:px-8 relative">
         <div class="absolute inset-0 overflow-hidden">
             <img src="{{ asset('IMAGES/IMG_0843-removebg-preview.png') }}" class="w-full h-full object-cover opacity-5 blur-sm" alt="">
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 w-full max-w-5xl bg-gray-900 rounded-2xl shadow-2xl overflow-hidden border border-white/10 relative z-10">            
            <div class="hidden md:flex flex-col justify-center items-center bg-black p-12 text-center relative overflow-hidden">
                <div class="absolute inset-0 bg-amber-300/10 blur-[60px]"></div>
                <div class="relative z-10">
                    <h2 class="text-4xl font-playfair font-bold text-white mb-4">Join Lumina</h2>
                    <p class="text-gray-400 mb-8">Create an account to track orders, save favorites, and get exclusive access.</p>
                    <ul class="text-left space-y-4 text-gray-400 text-sm">
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-amber-300 rounded-full"></span> Fast & Secure Checkout</li>
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-amber-300 rounded-full"></span> Exclusive Member Discounts</li>
                        <li class="flex items-center gap-3"><span class="w-2 h-2 bg-amber-300 rounded-full"></span> Order History & Tracking</li>
                    </ul>
                </div>
            </div>

            <div class="p-8 sm:p-12 flex flex-col justify-center">
                <h2 class="text-3xl font-playfair font-bold text-amber-300 mb-6 text-center md:text-left">Create Account</h2>

                <form method="POST" action="{{ route('register.post') }}" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Name</label>
                            <input type="text" name="name" class="w-full px-4 py-3 bg-black border border-gray-700 rounded-lg focus:outline-none focus:border-amber-300 text-white">
                            @error("name")
                            <span class="text-danger text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-200 mb-2">Phone Number</label>
                            <input type="text" name="phone" class="w-full px-4 py-3 bg-black border border-gray-700 rounded-lg focus:outline-none focus:border-amber-300 text-white">
                            @error("phone")
                            <span class="text-danger text-sm text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-200 mb-2">Email Address</label>
                        <input type="email" name="email" value="{{ session('email') }}" class="w-full px-4 py-3 bg-black border border-gray-700 rounded-lg focus:outline-none focus:border-amber-300 text-white">
                        @error("email")
                            <span class="text-danger text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="mb-6">
                            <label for="password" class="block text-gray-400 text-sm font-medium mb-2">Password</label>
                            <div class="relative">
                                <input id="password" type="password" name="password"  
                                    class="w-full px-4 py-3 bg-black border border-gray-700 rounded-lg focus:outline-none focus:border-amber-300 text-white"  
                                    placeholder="••••••••">
                                    @error("password")
                                <span class="text-danger text-sm text-red-500">{{ $message }}</span>
                                @enderror

                                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-amber-300 focus:outline-none">
                                    <svg id="eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    
                                    <svg id="eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </button>
                            </div>
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block text-gray-400 text-sm font-medium mb-2">Confirm Password</label>
                            <div class="relative">
                                <input id="password_confirmation" type="password" name="password_confirmation"  
                                    class="w-full px-4 py-3 bg-black border border-gray-700 rounded-lg focus:outline-none focus:border-amber-300 text-white" 
                                    placeholder="••••••••">
                                    @error("password_confirmation")
                                <span class="text-danger text-sm text-red-500">{{ $message }}</span>
                                @enderror

                                <button type="button" onclick="togglePasswordConfirmation()" class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-500 hover:text-amber-300 focus:outline-none">
                                    <svg id="eye-open-confirm" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" 
                                        stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    
                                    <svg id="eye-closed-confirm" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </button>
                            </div>
                        </div>
                        
                    </div>

                    <button type="submit" class="w-full py-4 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-all transform hover:scale-[1.02] shadow-lg shadow-amber-300/20">
                        Sign Up
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-gray-300 text-sm">
                        Already have an account? 
                        <a href="{{ route('login.form') }}" class="text-amber-300 hover:text-white font-medium transition-colors">Log in here</a>
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
    function togglePasswordConfirmation() {
        const passwordInput = document.getElementById('password_confirmation');
        const eyeOpen = document.getElementById('eye-open-confirm');
        const eyeClosed = document.getElementById('eye-closed-confirm');

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
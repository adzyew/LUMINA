<!doctype html>
<html lang="en">
<head>
    @include('partials.favicon')
    <title>Reset Password | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased flex flex-col min-h-screen transition-colors">
    <div class="fixed inset-0 -z-50 overflow-hidden">
        <img src="{{ asset('IMAGES/BG.png') }}" alt="" class="w-full h-full object-cover"/>
        <div class="absolute inset-0 bg-linear-to-b from-amber-200/30 via-white/60 to-amber-50/80"></div>
    </div>
    @include('partials.navbar')

    <div class="grow flex items-center justify-center py-24 px-4">
        <div class="w-full max-w-md bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-amber-200/50 p-8 sm:p-10">
            <div class="text-center mb-8">
                <div class="inline-flex justify-center w-16 h-16 rounded-full bg-amber-100 mb-4 text-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-9 mt-3">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                </svg>
                </div>
                <h2 class="text-3xl font-playfair font-bold text-gray-900 mb-2">Set New Password</h2>
                <p class="text-gray-600 text-sm">Enter your new password below.</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                    <ul class="list-disc list-inside space-y-1 text-sm">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="password" class="block text-gray-700 text-sm font-medium mb-2">New Password</label>
                    <div class="relative">
                        <input type="password" name="password" id="password" required minlength="8"
                            class="w-full px-4 py-3 pr-11 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            placeholder="Use uppercase, lowercase, number"
                            autocomplete="new-password">
                        <button type="button" onclick="togglePasswordField('password', 'reset-eye-open', 'reset-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility">
                            <svg id="reset-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="reset-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <p id="reset-password-strength" class="hidden text-xs mt-2 text-gray-500">Password strength: Weak</p>
                    <p id="reset-password-rules" class="hidden text-xs mt-1 text-gray-500">Use at least 8 characters with uppercase, lowercase, and a number.</p>
                </div>
                <div>
                    <label for="password_confirmation" class="block text-gray-700 text-sm font-medium mb-2">Confirm Password</label>
                    <div class="relative">
                        <input type="password" name="password_confirmation" id="password_confirmation" required minlength="8"
                            class="w-full px-4 py-3 pr-11 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400"
                            placeholder="Confirm your password"
                            autocomplete="new-password">
                        <button type="button" onclick="togglePasswordField('password_confirmation', 'reset-confirm-eye-open', 'reset-confirm-eye-closed')" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600" aria-label="Toggle password visibility">
                            <svg id="reset-confirm-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg id="reset-confirm-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    <p id="reset-password-match" class="hidden text-xs mt-2">Passwords match.</p>
                </div>
                <button type="submit" class="w-full py-4 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors">
                    Update Password
                </button>
            </form>

            <a href="{{ route('login') }}" class="block mt-6 text-center text-sm text-gray-500 hover:text-amber-600 transition-colors">← Back to Login</a>
        </div>
    </div>
    @include('partials.footer')
    <script>
        function togglePasswordField(inputId, eyeOpenId, eyeClosedId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(eyeOpenId);
            const eyeClosed = document.getElementById(eyeClosedId);

            if (!input || !eyeOpen || !eyeClosed) {
                return;
            }

            if (input.type === 'password') {
                input.type = 'text';
                eyeOpen.classList.remove('hidden');
                eyeClosed.classList.add('hidden');
            } else {
                input.type = 'password';
                eyeOpen.classList.add('hidden');
                eyeClosed.classList.remove('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('password_confirmation');
            const strengthLabel = document.getElementById('reset-password-strength');
            const rulesLabel = document.getElementById('reset-password-rules');
            const matchLabel = document.getElementById('reset-password-match');

            function updatePasswordStrength(showOutput = false) {
                const value = password ? (password.value || '') : '';
                const hasMinLength = value.length >= 8;
                const hasLower = /[a-z]/.test(value);
                const hasUpper = /[A-Z]/.test(value);
                const hasNumber = /\d/.test(value);
                const isStrong = hasMinLength && hasLower && hasUpper && hasNumber;

                if (!strengthLabel || !rulesLabel) {
                    return;
                }

                if (!showOutput || value.length === 0) {
                    strengthLabel.classList.add('hidden');
                    rulesLabel.classList.add('hidden');
                    strengthLabel.textContent = 'Password strength: Weak';
                    strengthLabel.className = 'hidden text-xs mt-2 text-gray-500';
                    rulesLabel.textContent = 'Use at least 8 characters with uppercase, lowercase, and a number.';
                    rulesLabel.className = 'hidden text-xs mt-1 text-gray-500';
                    return;
                }

                strengthLabel.classList.remove('hidden');
                rulesLabel.classList.remove('hidden');

                if (isStrong) {
                    strengthLabel.textContent = 'Password strength: Strong';
                    strengthLabel.className = 'text-xs mt-2 text-green-600';
                    rulesLabel.textContent = 'Good password. Requirements complete.';
                    rulesLabel.className = 'text-xs mt-1 text-green-600';
                } else {
                    strengthLabel.textContent = 'Password strength: Weak';
                    strengthLabel.className = 'text-xs mt-2 text-red-500';
                    rulesLabel.textContent = 'Missing requirement: 8+ chars, uppercase, lowercase, and number.';
                    rulesLabel.className = 'text-xs mt-1 text-red-500';
                }
            }

            function updatePasswordMatch() {
                if (!password || !confirmPassword || !matchLabel) {
                    return;
                }

                const passwordValue = password.value || '';
                const confirmValue = confirmPassword.value || '';

                if (confirmValue.length === 0) {
                    matchLabel.classList.add('hidden');
                    return;
                }

                matchLabel.classList.remove('hidden');

                if (passwordValue === confirmValue) {
                    matchLabel.textContent = 'Passwords match.';
                    matchLabel.className = 'text-xs mt-2 text-green-600';
                } else {
                    matchLabel.textContent = 'Passwords do not match.';
                    matchLabel.className = 'text-xs mt-2 text-red-500';
                }
            }

            if (password) {
                password.addEventListener('input', function () {
                    updatePasswordStrength(true);
                    updatePasswordMatch();
                });

                password.addEventListener('blur', function () {
                    updatePasswordStrength(false);
                });
            }

            if (confirmPassword) {
                confirmPassword.addEventListener('input', updatePasswordMatch);
                confirmPassword.addEventListener('blur', updatePasswordMatch);
            }
        });
    </script>
</body>
</html>

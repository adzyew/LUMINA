<!doctype html>
<html lang="en">
<head>
    <title>Verify OTP | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        input[type=number]::-webkit-inner-spin-button, input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white font-sans antialiased flex flex-col min-h-screen transition-colors">
    @include('partials.navbar')

    <div class="grow flex items-center justify-center py-24 px-4 sm:px-6 relative">
        <div class="fixed inset-0 -z-50 overflow-hidden">
            <img src="{{ asset('IMAGES/BG.png') }}" class="w-full h-full object-cover opacity-20" alt="">
            <div class="absolute inset-0 bg-linear-to-b from-black/60 via-black/90 to-black"></div>
        </div>

        <div class="w-full max-w-md bg-gray-900/80 backdrop-blur-xl rounded-2xl shadow-2xl border border-amber-300/20 p-8 sm:p-10">
            <div class="text-center mb-8">
                <div class="inline-flex justify-center w-16 h-16 rounded-full bg-amber-300/10 mb-4 text-amber-300">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <h2 class="text-3xl font-playfair font-bold text-white mb-2">Enter Verification Code</h2>
                <p class="text-gray-400 text-sm">We've sent a 6-digit OTP to<br><span class="text-white font-medium">{{ session('password_reset_email') }}</span></p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-500/20 border border-red-500/40 rounded-xl text-red-400 text-sm">{{ $errors->first() }}</div>
            @endif
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-500/20 border border-green-500/40 rounded-xl text-green-400 text-sm">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('password.verify-otp') }}" id="otpForm">
                @csrf
                <div class="flex justify-between gap-2 mb-8">
                    @for($i = 0; $i < 6; $i++)
                        <input type="number" name="code[]" class="otp-input w-12 h-14 sm:w-14 sm:h-16 bg-black border border-gray-700 rounded-lg text-center text-xl font-bold text-amber-300 focus:outline-none focus:border-amber-300 focus:ring-1 focus:ring-amber-300" maxlength="1" inputmode="numeric" required>
                    @endfor
                </div>
                <p id="otp-timer" class="text-center text-sm text-gray-400 mb-6">OTP expires in <span class="text-amber-300 font-semibold">5:00</span></p>
                <button type="submit" class="w-full py-4 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors mb-6">Verify & Reset Password</button>
            </form>

            <div class="text-center">
                <form method="POST" action="{{ route('password.resend-otp') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-amber-300 text-sm hover:text-amber-200">Resend OTP</button>
                </form>
                <a href="{{ route('password.request') }}" class="block mt-4 text-xs text-gray-500 hover:text-gray-400">Use different email</a>
            </div>
        </div>
    </div>
    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.otp-input');
            const timerEl = document.getElementById('otp-timer');
            let timeLeft = {{ (int) ($remainingSeconds ?? 300) }};

            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (!/^\d$/.test(e.target.value)) { e.target.value = ''; return; }
                    if (index < inputs.length - 1) inputs[index + 1].focus();
                });
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) inputs[index - 1].focus();
                });
                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const paste = e.clipboardData.getData('text').slice(0, 6);
                    if (/^\d{6}$/.test(paste)) { paste.split('').forEach((n, i) => inputs[i].value = n); inputs[5].focus(); }
                });
            });

            const countdown = setInterval(() => {
                if (timeLeft <= 0) { clearInterval(countdown); timerEl.innerHTML = 'OTP expired'; return; }
                const m = Math.floor(timeLeft / 60), s = timeLeft % 60;
                timerEl.innerHTML = `OTP expires in <span class="text-amber-300 font-semibold">${m}:${s.toString().padStart(2,'0')}</span>`;
                timeLeft--;
            }, 1000);
        });
    </script>
</body>
</html>

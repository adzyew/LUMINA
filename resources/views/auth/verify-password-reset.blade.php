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
        .fade-in-up { animation: fadeInUp 0.45s ease-out; }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased flex flex-col min-h-screen transition-colors">
    @include('partials.navbar')

    <div class="grow flex items-center justify-center py-24 px-4 sm:px-6 relative">
        <div class="fixed inset-0 -z-50 overflow-hidden">
            <img src="{{ asset('IMAGES/BG.png') }}" class="w-full h-full object-cover opacity-20" alt="">
            <div class="absolute inset-0 bg-linear-to-b from-amber-200/30 via-white/60 to-amber-50/80"></div>
        </div>

        <div class="fade-in-up w-full max-w-md bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-amber-200/50 p-8 sm:p-10">
            <div class="text-center mb-8">
                <div class="inline-flex justify-center w-16 h-16 rounded-full bg-amber-100 mb-4 text-amber-500">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-9 mt-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                </svg>
                </div>
                <h2 class="text-3xl font-playfair font-bold text-gray-900 mb-2">Enter Verification Code</h2>
                <p class="text-gray-600 text-sm">We've sent a 6-digit OTP to<br><span class="text-gray-700 font-medium">{{ session('password_reset_email') }}</span></p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">{{ $errors->first() }}</div>
            @endif
            @if(session('success'))
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('password.verify-otp') }}" id="otpForm">
                @csrf
                <div class="flex justify-between gap-2 mb-8">
                    @for($i = 0; $i < 6; $i++)
                        <input type="number" name="code[]" class="otp-input w-12 h-14 sm:w-14 sm:h-16 bg-white border border-gray-300 rounded-lg text-center text-xl font-bold text-amber-600 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400" maxlength="1" inputmode="numeric" required>
                    @endfor
                </div>
                <p id="lock-timer" class="hidden text-center text-sm text-red-600 mb-3"></p>
                <p id="otp-timer" class="text-center text-sm text-gray-600 mb-6"></p>
                <button id="verifyBtn" type="submit" class="w-full py-4 bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors mb-6">Verify & Reset Password</button>
            </form>

            <div class="text-center">
                <p class="text-sm text-gray-600 mb-2">Didn't receive a code?</p>
                <form method="POST" action="{{ route('password.resend-otp') }}" class="inline" id="resendForm">
                    @csrf
                    <button id="resendBtn" type="submit" class="text-amber-600 text-sm font-semibold hover:text-amber-700 disabled:text-gray-400 disabled:cursor-not-allowed">Resend OTP</button>
                </form>
                <p id="resendHint" class="mt-2 text-xs text-gray-500"></p>
                <a href="{{ route('password.request') }}" class="block mt-4 text-xs text-gray-500 hover:text-gray-700">Use different email</a>
            </div>
        </div>
    </div>
    @include('partials.footer')

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.otp-input');
            const timerEl = document.getElementById('otp-timer');
            const lockEl = document.getElementById('lock-timer');
            const resendBtn = document.getElementById('resendBtn');
            const resendHint = document.getElementById('resendHint');
            const verifyBtn = document.getElementById('verifyBtn');
            let otpRemaining = {{ (int) ($remainingSeconds ?? 0) }};
            let lockRemaining = {{ (int) ($lockRemainingSeconds ?? 0) }};

            const formatClock = (seconds) => {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                return `${m}:${s.toString().padStart(2,'0')}`;
            };

            const setVerificationDisabled = (disabled) => {
                inputs.forEach((input) => input.disabled = disabled);
                verifyBtn.disabled = disabled;
                verifyBtn.classList.toggle('opacity-50', disabled);
                verifyBtn.classList.toggle('cursor-not-allowed', disabled);
            };

            const updateResendState = () => {
                if (lockRemaining > 0) {
                    resendBtn.disabled = true;
                    resendHint.textContent = `Resend available after lock: ${formatClock(lockRemaining)}`;
                    return;
                }

                if (otpRemaining > 0) {
                    resendBtn.disabled = true;
                    resendHint.textContent = `You can resend in ${formatClock(otpRemaining)}`;
                } else {
                    resendBtn.disabled = false;
                    resendHint.textContent = 'You can request a new code now.';
                }
            };

            const updateTimerText = () => {
                if (lockRemaining > 0) {
                    lockEl.classList.remove('hidden');
                    lockEl.textContent = `Too many attempts. Try again in ${formatClock(lockRemaining)}.`;
                    timerEl.textContent = 'Verification temporarily locked';
                    setVerificationDisabled(true);
                    return;
                }

                lockEl.classList.add('hidden');
                setVerificationDisabled(false);

                if (otpRemaining > 0) {
                    timerEl.innerHTML = `OTP expires in <span class="text-amber-600 font-semibold">${formatClock(otpRemaining)}</span>`;
                } else {
                    timerEl.textContent = 'OTP expired. Please resend a new code.';
                }
            };

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

            updateTimerText();
            updateResendState();

            setInterval(() => {
                if (lockRemaining > 0) {
                    lockRemaining--;
                } else if (otpRemaining > 0) {
                    otpRemaining--;
                }

                updateTimerText();
                updateResendState();
            }, 1000);
        });
    </script>
</body>
</html>

<!doctype html>
<html>
<head>
    @include('partials.favicon')
    <title>Verify Account | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        .font-playfair { font-family: 'Playfair Display', serif; }
        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .otp-error {
            border-color: #ef4444 !important;
            animation: pulseError 0.6s ease-in-out;
        }

        .shake {
            animation: shake 0.35s;
        }

        .fade-in-up {
            animation: fadeInUp 0.45s ease-out;
        }

        @keyframes pulseError {
            0% { box-shadow: 0 0 0 0 rgba(239,68,68,0.55); }
            70% { box-shadow: 0 0 0 10px rgba(239,68,68,0); }
            100% { box-shadow: 0 0 0 0 rgba(239,68,68,0); }
        }

        @keyframes shake {
            0% { transform: translateX(0); }
            25% { transform: translateX(-6px); }
            50% { transform: translateX(6px); }
            75% { transform: translateX(-6px); }
            100% { transform: translateX(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 font-sans antialiased flex flex-col min-h-screen transition-colors">
    <div class="grow flex items-center justify-center py-24 px-4 sm:px-6 relative">
        <div class="fixed inset-0 -z-50 overflow-hidden">
            <img src="{{ asset('IMAGES/BG.png') }}" class="w-full h-full object-cover opacity-20" alt="">
            <div class="absolute inset-0 bg-linear-to-b from-amber-200/30 via-white/60 to-amber-50/80"></div>
        </div>

        <div class="fade-in-up w-full max-w-md bg-white/90 backdrop-blur-xl rounded-2xl shadow-2xl border border-amber-200/50 p-8 sm:p-10 relative overflow-hidden">
            <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-1 bg-linear-to-r from-transparent via-amber-400 to-transparent opacity-60"></div>

            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-amber-100 mb-4 text-amber-500">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                    </svg>
                </div>
                <h2 class="text-3xl font-playfair font-bold text-gray-900 mb-2">Verify it's you</h2>
                <p class="text-gray-600 text-sm">
                    We've sent a 6-digit verification code to<br>
                    <span class="text-gray-700 font-medium">{{ session('email') }}</span>
                </p>
            </div>

            @if(session('success'))
                <div class="mb-4 p-3 rounded-xl border border-green-200 bg-green-50 text-green-700 text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-3 rounded-xl border border-red-200 bg-red-50 text-red-600 text-sm">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="mb-4 p-3 rounded-xl border border-red-200 bg-red-50 text-red-600 text-sm">{{ $errors->first() }}</div>
            @endif

            <form method="POST" action="{{ route('verify.otp') }}" id="otpForm">
                @csrf

                <div class="flex justify-between gap-2 mb-8" id="otpInputsWrap">
                    @for($i = 0; $i < 6; $i++)
                        <input type="number" name="code[]" class="otp-input w-12 h-14 sm:w-14 sm:h-16 bg-white border border-gray-300 rounded-lg text-center text-xl sm:text-2xl font-bold text-amber-600 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all placeholder-gray-300" maxlength="1" inputmode="numeric" placeholder="-" required>
                    @endfor
                </div>

                <p id="lock-timer" class="hidden text-center text-sm text-red-600 mb-3"></p>
                <p id="attempts-info" class="text-center text-sm text-amber-600 mb-2"></p>
                <p id="otp-timer" class="text-center text-sm text-gray-600 mb-6"></p>

                <button id="verifyBtn" type="submit" class="w-full py-4 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-all transform hover:scale-[1.02] shadow-lg shadow-amber-300/20 mb-6">
                    Verify Code
                </button>
            </form>

            <div class="text-center">
                <form method="POST" action="{{ route('otp.resend') }}" id="resendForm" class="inline-flex items-center gap-2">
                    @csrf
                    <p class="text-sm text-gray-600">Didn't receive a code?</p>
                    <button id="resendBtn" type="submit" class="text-amber-600 text-sm font-semibold hover:text-amber-700 disabled:text-gray-400 disabled:cursor-not-allowed">
                        Resend Code
                    </button>
                </form>
                <a href="{{ route('login') }}" class="block mt-4 text-md text-gray-600 hover:text-amber-400">Back to Login</a>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.otp-input');
            const timerEl = document.getElementById('otp-timer');
            const lockEl = document.getElementById('lock-timer');
            const attemptsInfoEl = document.getElementById('attempts-info');
            const resendBtn = document.getElementById('resendBtn');
            const resendHint = document.getElementById('resendHint');
            const verifyBtn = document.getElementById('verifyBtn');
            const otpInputsWrap = document.getElementById('otpInputsWrap');

            let otpRemaining = {{ (int) ($remainingSeconds ?? 0) }};
            let lockRemaining = {{ (int) ($lockRemainingSeconds ?? 0) }};
            let attemptsRemaining = {{ (int) ($attemptsRemaining ?? 0) }};

            const formatClock = (seconds) => {
                const m = Math.floor(seconds / 60);
                const s = seconds % 60;
                return `${m}:${s.toString().padStart(2, '0')}`;
            };

            const setVerificationDisabled = (disabled) => {
                inputs.forEach((input) => {
                    input.disabled = disabled;
                });
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
                    attemptsInfoEl.textContent = 'Attempts left: 0 / 3';
                    timerEl.innerHTML = 'Verification temporarily locked';
                    setVerificationDisabled(true);
                    return;
                }

                lockEl.classList.add('hidden');
                setVerificationDisabled(false);

                if (otpRemaining > 0) {
                    timerEl.innerHTML = `Resend code available in <span class="text-amber-600 font-semibold">${formatClock(otpRemaining)}</span>`;
                } else {
                    timerEl.textContent = 'You can resend a new code now.';
                }

                attemptsInfoEl.textContent = `Attempts left: ${attemptsRemaining} / 3`;
            };

            inputs.forEach((input, index) => {
                input.addEventListener('input', (e) => {
                    if (!/^\d$/.test(e.target.value)) {
                        e.target.value = '';
                        return;
                    }
                    if (index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }
                });

                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Backspace' && !e.target.value && index > 0) {
                        inputs[index - 1].focus();
                    }
                });

                input.addEventListener('paste', (e) => {
                    e.preventDefault();
                    const paste = e.clipboardData.getData('text').slice(0, 6);
                    if (/^\d{6}$/.test(paste)) {
                        paste.split('').forEach((num, i) => {
                            inputs[i].value = num;
                        });
                        inputs[5].focus();
                    }
                });
            });

            @if($errors->any())
                otpInputsWrap.classList.add('shake');
                inputs.forEach((input) => input.classList.add('otp-error'));
                setTimeout(() => {
                    otpInputsWrap.classList.remove('shake');
                    inputs.forEach((input) => input.classList.remove('otp-error'));
                }, 600);
            @endif

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

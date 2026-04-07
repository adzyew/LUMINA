<!doctype html>
<html lang="en">
<head>
    @include('partials.favicon')
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

            <form method="POST" action="{{ route('password.verify-otp') }}" id="otpForm">
                @csrf
                <div class="flex justify-between gap-2 mb-8">
                    @for($i = 0; $i < 6; $i++)
                        <input type="number" name="code[]" class="otp-input w-12 h-14 sm:w-14 sm:h-16 bg-white border border-gray-300 rounded-lg text-center text-xl font-bold text-amber-600 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400" maxlength="1" inputmode="numeric" required>
                    @endfor
                </div>
                <p id="lock-timer" class="hidden text-center text-sm text-red-600 mb-3"></p>
                <p id="otp-timer" class="text-center text-sm text-gray-600 mb-6"></p>
                <button id="verifyBtn" type="submit" class="w-full py-4 bg-amber-300 text-black font-medium rounded-xl hover:bg-amber-400 transition-colors mb-6">Verify & Reset Password</button>
            </form>

            <div class="text-center">
                <form method="POST" action="{{ route('password.resend-otp') }}" class="inline-flex item-center gap-2" id="resendForm">
                    @csrf
                    <p class="text-sm text-gray-600">Didn't receive a code?</p>
                    <button id="resendBtn" type="submit" class="text-amber-600 text-sm font-semibold hover:text-amber-700 disabled:text-gray-400 disabled:cursor-not-allowed">Resend OTP</button>
                </form>
                <a href="{{ route('password.request') }}" class="block mt-4 text-xs text-gray-500 hover:text-gray-700">Use different email</a>
            </div>
        </div>
    </div>

    @php
        $toastMessage = session('success') ?: ($errors->any() ? $errors->first() : null);
        $toastType = session('success') ? 'success' : 'error';
        $toastClasses = $toastType === 'success'
            ? 'border-green-200 bg-green-50 text-green-700'
            : 'border-red-200 bg-red-50 text-red-600';
    @endphp
    @if($toastMessage)
        <div id="otpResetToast" class="fixed top-20 left-1/2 -translate-x-1/2 z-50 w-[calc(100vw-2rem)] max-w-md p-3 rounded-xl border text-sm shadow-xl opacity-0 translate-y-2 pointer-events-none transition-all duration-300 {{ $toastClasses }}">
            <div class="flex items-center justify-between gap-3">
                <span>{{ $toastMessage }}</span>
                <button id="otpResetToastClose" type="button" class="shrink-0 opacity-70 hover:opacity-100 transition-opacity" aria-label="Close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const inputs = document.querySelectorAll('.otp-input');
            const timerEl = document.getElementById('otp-timer');
            const lockEl = document.getElementById('lock-timer');
            const resendBtn = document.getElementById('resendBtn');
            const resendHint = document.getElementById('resendHint');
            const verifyBtn = document.getElementById('verifyBtn');
            const otpForm = document.getElementById('otpForm');
            const resendForm = document.getElementById('resendForm');
            const resetEmail = @json((string) session('password_reset_email', 'guest'));
            const serverOtpExpiresAtTs = Number(@json((int) ($otpExpiresAtTs ?? 0)));
            const serverLockExpiresAtTs = Number(@json((int) ($lockExpiresAtTs ?? 0)));
            let otpRemaining = {{ (int) ($remainingSeconds ?? 0) }};
            let lockRemaining = {{ (int) ($lockRemainingSeconds ?? 0) }};

            const otpStorageKey = `otp_reset_expires_at_${resetEmail}`;
            const lockStorageKey = `otp_reset_lock_expires_at_${resetEmail}`;
            const nowMs = Date.now();

            const serverOtpEndsAt = serverOtpExpiresAtTs > 0 ? serverOtpExpiresAtTs * 1000 : (otpRemaining > 0 ? nowMs + (otpRemaining * 1000) : 0);
            const serverLockEndsAt = serverLockExpiresAtTs > 0 ? serverLockExpiresAtTs * 1000 : (lockRemaining > 0 ? nowMs + (lockRemaining * 1000) : 0);
            const storedOtpEndsAt = Number(localStorage.getItem(otpStorageKey) || 0);
            const storedLockEndsAt = Number(localStorage.getItem(lockStorageKey) || 0);

            let otpEndsAtMs = 0;
            if (serverOtpEndsAt > 0 && storedOtpEndsAt > nowMs) {
                otpEndsAtMs = Math.min(serverOtpEndsAt, storedOtpEndsAt);
            } else if (serverOtpEndsAt > 0) {
                otpEndsAtMs = serverOtpEndsAt;
            } else if (storedOtpEndsAt > nowMs) {
                otpEndsAtMs = storedOtpEndsAt;
            }

            let lockEndsAtMs = 0;
            if (serverLockEndsAt > 0 && storedLockEndsAt > nowMs) {
                lockEndsAtMs = Math.min(serverLockEndsAt, storedLockEndsAt);
            } else if (serverLockEndsAt > 0) {
                lockEndsAtMs = serverLockEndsAt;
            } else if (storedLockEndsAt > nowMs) {
                lockEndsAtMs = storedLockEndsAt;
            }

            if (otpEndsAtMs > 0) {
                localStorage.setItem(otpStorageKey, String(otpEndsAtMs));
            } else {
                localStorage.removeItem(otpStorageKey);
            }

            if (lockEndsAtMs > 0) {
                localStorage.setItem(lockStorageKey, String(lockEndsAtMs));
            } else {
                localStorage.removeItem(lockStorageKey);
            }

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
                if (!resendBtn || !resendHint) {
                    return;
                }
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
                if (!timerEl || !lockEl || !verifyBtn) {
                    return;
                }
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

            if (otpForm) {
                otpForm.addEventListener('submit', () => {
                    verifyBtn.disabled = true;
                    verifyBtn.classList.add('opacity-60', 'cursor-not-allowed');
                    verifyBtn.textContent = 'Verifying...';
                });
            }

            if (resendForm) {
                resendForm.addEventListener('submit', () => {
                    resendBtn.disabled = true;
                    resendBtn.classList.add('opacity-60', 'cursor-not-allowed');
                    resendBtn.textContent = 'Sending...';
                });
            }

            const syncCountdown = () => {
                lockRemaining = Math.max(0, Math.ceil((lockEndsAtMs - Date.now()) / 1000));
                otpRemaining = Math.max(0, Math.ceil((otpEndsAtMs - Date.now()) / 1000));

                if (lockRemaining <= 0) {
                    localStorage.removeItem(lockStorageKey);
                }
                if (otpRemaining <= 0) {
                    localStorage.removeItem(otpStorageKey);
                }

                updateTimerText();
                updateResendState();
            };

            syncCountdown();
            setInterval(syncCountdown, 1000);
            document.addEventListener('visibilitychange', syncCountdown);

            const pageToast = document.getElementById('otpResetToast');
            if (pageToast) {
                const dismissToast = () => {
                    pageToast.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
                };
                requestAnimationFrame(() => {
                    pageToast.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
                });
                const closeBtn = document.getElementById('otpResetToastClose');
                if (closeBtn) {
                    closeBtn.addEventListener('click', dismissToast);
                }
                window.setTimeout(dismissToast, 3200);
            }
        });
    </script>
</body>
</html>

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
                    {{ $smsMode ? 'Complete reCAPTCHA, then tap "Send SMS Code" for' : "We've sent a 6-digit verification code to" }}<br>
                    <span class="text-gray-700 font-medium">{{ $otpRecipient ?? session('email') }}</span>
                    <span class="block mt-1 text-xs uppercase tracking-wide text-gray-400">via {{ ($otpChannel ?? 'email') === 'sms' ? 'SMS' : 'Email' }}</span>
                </p>
            </div>

            <form method="POST" action="{{ $smsMode ? route('verify.firebase.sms') : route('verify.otp') }}" id="otpForm">
                @csrf

                <div class="flex justify-between gap-2 mb-8" id="otpInputsWrap">
                    @for($i = 0; $i < 6; $i++)
                        <input type="number" name="code[]" class="otp-input w-12 h-14 sm:w-14 sm:h-16 bg-white border border-gray-300 rounded-lg text-center text-xl sm:text-2xl font-bold text-amber-600 focus:outline-none focus:border-amber-400 focus:ring-1 focus:ring-amber-400 transition-all placeholder-gray-300" maxlength="1" inputmode="numeric" placeholder="-" required>
                    @endfor
                </div>

                <p id="lock-timer" class="hidden text-center text-sm text-red-600 mb-2"></p>
                <p id="otp-timer" class="text-center text-sm text-gray-600 mb-1"></p>
                <p id="attempts-info" class="text-center text-sm text-gray-600 mb-6"></p>
                @if($smsMode)
                    <div id="firebase-recaptcha" class="mt-2 mb-3 flex justify-center"></div>
                    <input type="hidden" name="id_token" id="firebase-id-token">
                @endif

                <button id="verifyBtn" type="submit" class="w-full py-4 bg-amber-300 text-black font-medium rounded-lg hover:bg-amber-400 transition-all transform hover:scale-[1.02] shadow-lg shadow-amber-300/20 mb-6">
                    Verify Code
                </button>
            </form>

            <div class="text-center">
                @if($smsMode)
                    <div class="inline-flex items-center gap-2 flex-wrap justify-center" id="resendForm">
                        <p id="smsSendPrompt" class="text-sm text-gray-600">Didn't receive a code?</p>
                        <button id="resendBtnSmsFirebase" type="button" class="text-amber-600 text-sm font-semibold hover:text-amber-700 disabled:text-gray-400 disabled:cursor-not-allowed">
                            Send SMS Code
                        </button>
                    </div>
                @else
                    <form method="POST" action="{{ route('otp.resend') }}" id="resendForm" class="inline-flex items-center gap-2 flex-wrap justify-center">
                        @csrf
                        <p class="text-sm text-gray-600">Didn't receive a code?</p>
                        <button id="resendBtnEmail" name="channel" value="email" type="submit" class="text-amber-600 text-sm font-semibold hover:text-amber-700 disabled:text-gray-400 disabled:cursor-not-allowed">
                            Resend via Email
                        </button>
                    </form>
                @endif
                <p id="resendTimerText" class="text-sm text-gray-600 mt-2"></p>
                <p id="resendHint" class="hidden text-sm mt-1"></p>
                <a href="{{ route('login') }}" class="block mt-4 text-md text-gray-600 hover:text-amber-400">Back to Login</a>
            </div>
        </div>
    </div>

    @php
        $toastMessage = session('success') ?: session('error') ?: ($errors->any() ? $errors->first() : null);
        $toastType = session('success') ? 'success' : 'error';
        $toastClasses = $toastType === 'success'
            ? 'border-green-200 bg-green-50 text-green-700'
            : 'border-red-200 bg-red-50 text-red-600';
    @endphp
    @if($toastMessage)
        <div id="otpPageToast" class="fixed top-20 left-1/2 -translate-x-1/2 z-50 w-[calc(100vw-2rem)] max-w-md p-3 rounded-xl border text-sm shadow-xl opacity-0 translate-y-2 pointer-events-none transition-all duration-300 {{ $toastClasses }}">
            <div class="flex items-center justify-between gap-3">
                <span>{{ $toastMessage }}</span>
                <button id="otpPageToastClose" type="button" class="shrink-0 opacity-70 hover:opacity-100 transition-opacity" aria-label="Close">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12"></path></svg>
                </button>
            </div>
        </div>
    @endif

    @if($smsMode)
        <script src="https://www.gstatic.com/firebasejs/10.12.5/firebase-app-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/10.12.5/firebase-auth-compat.js"></script>
        <script src="https://www.gstatic.com/firebasejs/10.12.5/firebase-app-check-compat.js"></script>
    @endif
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const smsMode = @json((bool) $smsMode);
            const inputs = document.querySelectorAll('.otp-input');
            const timerEl = document.getElementById('otp-timer');
            const lockEl = document.getElementById('lock-timer');
            const attemptsInfoEl = document.getElementById('attempts-info');
            const resendHint = document.getElementById('resendHint');
            const resendTimerText = document.getElementById('resendTimerText');
            const verifyBtn = document.getElementById('verifyBtn');
            const otpForm = document.getElementById('otpForm');
            const otpInputsWrap = document.getElementById('otpInputsWrap');
            const sessionEmail = @json((string) session('email', 'guest'));

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

            if (smsMode) {
                const resendBtnSmsFirebase = document.getElementById('resendBtnSmsFirebase');
                const firebaseConfig = @json($firebaseConfig ?? []);
                const firebasePhone = @json((string) ($firebasePhoneE164 ?? ''));
                const firebaseSmsNonce = @json((string) ($firebaseSmsNonce ?? ''));
                const smsResendStorageKey = `firebase_sms_resend_at_${sessionEmail}`;
                const smsSentStorageKey = `firebase_sms_sent_${sessionEmail}_${firebaseSmsNonce}`;
                const emailFallbackUrl = @json(route('otp.fallback.email'));
                const csrfToken = @json(csrf_token());
                const idTokenInput = document.getElementById('firebase-id-token');
                const smsSendPrompt = document.getElementById('smsSendPrompt');
                let resendRemaining = 0;
                let confirmationResult = null;
                let recaptchaVerifier = null;
                let recaptchaWidgetId = null;

                const firebaseErrorMessage = (error) => {
                    const code = (error && error.code) ? String(error.code) : '';
                    const fallback = 'SMS verification failed. Please try again.';
                    const map = {
                        'auth/invalid-app-credential': 'Security check expired. Please tap "Send SMS Code" again.',
                        'auth/firebase-app-check-token-is-invalid': 'App security check failed. Please try again in a moment.',
                        'auth/captcha-check-failed': 'reCAPTCHA verification failed. Please refresh and try again.',
                        'auth/too-many-requests': 'Too many requests. Please wait a few minutes before trying again.',
                        'auth/quota-exceeded': 'SMS quota exceeded for this Firebase project.',
                        'auth/operation-not-allowed': 'Phone authentication is not enabled in Firebase.',
                        'auth/missing-phone-number': 'No phone number is available for SMS verification.',
                        'auth/invalid-phone-number': 'The phone number format is invalid.',
                        'auth/network-request-failed': 'Network error while contacting Firebase. Check your internet and try again.',
                        'auth/code-expired': 'The SMS code has expired. Please request a new one.',
                        'auth/invalid-verification-code': 'The SMS code is invalid. Please check and try again.',
                        'auth/session-expired': 'Verification session expired. Please resend the SMS code.',
                        'auth/argument-error': 'Invalid verification request. Please try again.',
                    };

                    if (code && map[code]) {
                        return map[code];
                    }
                    return fallback;
                };

                const logFirebaseError = (error) => {
                    const code = (error && error.code) ? String(error.code) : 'unknown';
                    // Keep full technical details in console for debugging.
                    console.error('[Firebase SMS OTP]', code, error);
                };
                const isTooManyRequests = (error) => (error && error.code) === 'auth/too-many-requests';
                const shouldRebuildRecaptcha = (error) => {
                    const code = (error && error.code) ? String(error.code) : '';
                    return [
                        'auth/invalid-app-credential',
                        'auth/captcha-check-failed',
                        'auth/session-expired',
                        'auth/argument-error',
                    ].includes(code);
                };
                const fallbackToEmailOtp = async () => {
                    const response = await fetch(emailFallbackUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                        },
                        body: JSON.stringify({}),
                        credentials: 'same-origin',
                    });
                    const payload = await response.json().catch(() => ({}));
                    if (!response.ok || payload.success !== true) {
                        throw new Error((payload && payload.message) ? payload.message : 'Email fallback failed.');
                    }
                    return payload;
                };

                const getOtpCode = () => Array.from(inputs).map((input) => (input.value || '').trim()).join('');

                const buildRecaptchaVerifier = async () => {
                    const container = document.getElementById('firebase-recaptcha');
                    if (recaptchaVerifier) {
                        try {
                            recaptchaVerifier.clear();
                        } catch (_) {
                            // noop
                        }
                    }
                    recaptchaVerifier = null;
                    recaptchaWidgetId = null;
                    if (container) {
                        container.innerHTML = '';
                    }

                    recaptchaVerifier = new window.firebase.auth.RecaptchaVerifier('firebase-recaptcha', {
                        size: 'invisible',
                        callback: () => {
                            if (resendHint) {
                                resendHint.classList.add('hidden');
                            }
                        },
                        'expired-callback': () => {
                            if (resendHint) {
                                resendHint.classList.remove('hidden');
                                resendHint.textContent = 'Security check expired. Please try again.';
                            }
                        },
                    });
                    recaptchaWidgetId = await recaptchaVerifier.render();
                };

                const setSmsResendCountdown = (seconds) => {
                    const endAt = Date.now() + (seconds * 1000);
                    localStorage.setItem(smsResendStorageKey, String(endAt));
                };

                const hasSmsSentBefore = () => localStorage.getItem(smsSentStorageKey) === '1';
                const markSmsSent = () => localStorage.setItem(smsSentStorageKey, '1');

                const updateSmsTimer = () => {
                    const endAt = Number(localStorage.getItem(smsResendStorageKey) || 0);
                    resendRemaining = Math.max(0, Math.ceil((endAt - Date.now()) / 1000));
                    if (resendBtnSmsFirebase) {
                        resendBtnSmsFirebase.textContent = hasSmsSentBefore() ? 'Resend via SMS' : 'Send SMS Code';
                    }
                    if (smsSendPrompt) {
                        smsSendPrompt.textContent = hasSmsSentBefore() ? "Didn't receive a code?" : 'Ready to get a code?';
                    }
                    if (resendRemaining <= 0) {
                        localStorage.removeItem(smsResendStorageKey);
                        if (resendBtnSmsFirebase) {
                            resendBtnSmsFirebase.disabled = false;
                        }
                        if (resendHint) {
                            resendHint.classList.add('hidden');
                            resendHint.textContent = '';
                        }
                        if (resendTimerText) {
                            resendTimerText.textContent = 'You can resend a new SMS code now.';
                        }
                    } else {
                        if (resendBtnSmsFirebase) {
                            resendBtnSmsFirebase.disabled = true;
                        }
                        if (resendTimerText) {
                            resendTimerText.textContent = `Resend code available in ${formatClock(resendRemaining)}`;
                        }
                    }
                    if (timerEl) {
                        timerEl.textContent = '';
                    }
                    if (attemptsInfoEl) {
                        attemptsInfoEl.textContent = '';
                    }
                    if (lockEl) {
                        lockEl.classList.add('hidden');
                    }
                };

                const sendSmsCode = async () => {
                    if (!firebasePhone) {
                        throw new Error('No phone number available for SMS verification.');
                    }
                    if (!window.firebase || !window.firebase.auth) {
                        throw new Error('Firebase SDK failed to load.');
                    }
                    if (!recaptchaVerifier || recaptchaWidgetId === null) {
                        throw new Error('reCAPTCHA is not ready yet.');
                    }

                    await recaptchaVerifier.verify();
                    confirmationResult = await window.firebase.auth().signInWithPhoneNumber(firebasePhone, recaptchaVerifier);
                    markSmsSent();
                    setSmsResendCountdown(120);
                    updateSmsTimer();
                    await buildRecaptchaVerifier();
                };

                const initSmsVerification = async () => {
                    try {
                        if (!window.firebase.apps.length) {
                            window.firebase.initializeApp(firebaseConfig);
                        }

                        const appCheckSiteKey = (firebaseConfig.appCheckSiteKey || '').trim();
                        const appCheckDebugToken = (firebaseConfig.appCheckDebugToken || '').trim();
                        const enableAppCheck = Boolean(firebaseConfig.enableAppCheck);
                        const isLocalHost = ['localhost', '127.0.0.1'].includes(window.location.hostname);
                        if (window.firebase.appCheck && enableAppCheck && appCheckSiteKey && !isLocalHost) {
                            if (appCheckDebugToken !== '') {
                                self.FIREBASE_APPCHECK_DEBUG_TOKEN = appCheckDebugToken === 'true'
                                    ? true
                                    : appCheckDebugToken;
                            }
                            window.firebase.appCheck().activate(appCheckSiteKey, true);
                        }
                        await buildRecaptchaVerifier();

                        updateSmsTimer();
                        if (resendHint) {
                            resendHint.classList.add('hidden');
                        }
                        if (resendTimerText && resendRemaining <= 0) {
                            resendTimerText.textContent = 'Tap "Send SMS Code" to receive your OTP.';
                        }
                    } catch (e) {
                        logFirebaseError(e);
                        if (resendHint) {
                            resendHint.classList.remove('hidden');
                            resendHint.textContent = firebaseErrorMessage(e);
                        }
                    }
                };
                initSmsVerification();

                if (resendBtnSmsFirebase) {
                    resendBtnSmsFirebase.addEventListener('click', async () => {
                        if (resendRemaining > 0) {
                            return;
                        }
                        resendBtnSmsFirebase.disabled = true;
                        resendBtnSmsFirebase.textContent = 'Sending...';
                        try {
                            await sendSmsCode();
                            resendBtnSmsFirebase.textContent = 'Resend via SMS';
                        } catch (error) {
                            logFirebaseError(error);
                            if (isTooManyRequests(error)) {
                                try {
                                    const fallback = await fallbackToEmailOtp();
                                    if (resendHint) {
                                        resendHint.classList.remove('hidden');
                                        resendHint.textContent = fallback.message || 'SMS limited. Switched to email OTP.';
                                    }
                                    window.setTimeout(() => window.location.reload(), 900);
                                    return;
                                } catch (fallbackError) {
                                    logFirebaseError(fallbackError);
                                }
                            }
                            if (shouldRebuildRecaptcha(error)) {
                                try {
                                    await buildRecaptchaVerifier();
                                } catch (renderError) {
                                    logFirebaseError(renderError);
                                }
                            }
                            resendBtnSmsFirebase.textContent = hasSmsSentBefore() ? 'Resend via SMS' : 'Send SMS Code';
                            if (resendHint) {
                                resendHint.classList.remove('hidden');
                                resendHint.textContent = firebaseErrorMessage(error);
                            }
                            resendBtnSmsFirebase.disabled = false;
                        }
                    });
                }

                setVerificationDisabled(false);
                setInterval(updateSmsTimer, 1000);
                document.addEventListener('visibilitychange', updateSmsTimer);

                if (otpForm) {
                    otpForm.addEventListener('submit', async (event) => {
                        event.preventDefault();
                        const otpCode = getOtpCode();
                        if (!/^\d{6}$/.test(otpCode)) {
                            if (resendHint) {
                                resendHint.classList.remove('hidden');
                                resendHint.textContent = 'Enter a valid 6-digit code.';
                            }
                            return;
                        }
                        if (!confirmationResult) {
                            if (resendHint) {
                                resendHint.classList.remove('hidden');
                                resendHint.textContent = 'SMS code is not ready yet. Please resend.';
                            }
                            return;
                        }

                        verifyBtn.disabled = true;
                        verifyBtn.classList.add('opacity-60', 'cursor-not-allowed');
                        verifyBtn.textContent = 'Verifying...';

                        try {
                            const credential = await confirmationResult.confirm(otpCode);
                            const idToken = await credential.user.getIdToken(true);
                            if (idTokenInput) {
                                idTokenInput.value = idToken;
                            }
                            otpForm.submit();
                        } catch (error) {
                            logFirebaseError(error);
                            verifyBtn.disabled = false;
                            verifyBtn.classList.remove('opacity-60', 'cursor-not-allowed');
                            verifyBtn.textContent = 'Verify Code';
                            if (resendHint) {
                                resendHint.classList.remove('hidden');
                                resendHint.textContent = firebaseErrorMessage(error);
                            }
                        }
                    });
                }
            } else {
                const resendBtnEmail = document.getElementById('resendBtnEmail');
                const resendForm = document.getElementById('resendForm');
                const serverOtpExpiresAtTs = Number(@json((int) ($otpExpiresAtTs ?? 0)));
                const serverLockExpiresAtTs = Number(@json((int) ($lockExpiresAtTs ?? 0)));

                let otpRemaining = {{ (int) ($remainingSeconds ?? 0) }};
                let lockRemaining = {{ (int) ($lockRemainingSeconds ?? 0) }};
                let attemptsRemaining = {{ (int) ($attemptsRemaining ?? 0) }};

                const otpStorageKey = `otp_verify_expires_at_${sessionEmail}`;
                const lockStorageKey = `otp_verify_lock_expires_at_${sessionEmail}`;
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

                const updateResendState = () => {
                    if (!resendHint) {
                        return;
                    }
                    if (resendHint) {
                        resendHint.classList.add('hidden');
                        resendHint.textContent = '';
                    }
                    if (lockRemaining > 0) {
                        if (resendBtnEmail) resendBtnEmail.disabled = true;
                        if (resendTimerText) {
                            resendTimerText.textContent = `Resend available after lock: ${formatClock(lockRemaining)}`;
                        }
                        return;
                    }

                    if (otpRemaining > 0) {
                        if (resendBtnEmail) resendBtnEmail.disabled = true;
                        if (resendTimerText) {
                            resendTimerText.textContent = `You can resend in ${formatClock(otpRemaining)}`;
                        }
                    } else {
                        if (resendBtnEmail) resendBtnEmail.disabled = false;
                        if (resendTimerText) {
                            resendTimerText.textContent = 'You can request a new code now.';
                        }
                    }
                };

                const updateTimerText = () => {
                    if (!timerEl || !lockEl || !verifyBtn || !attemptsInfoEl) {
                        return;
                    }
                    if (lockRemaining > 0) {
                        lockEl.classList.remove('hidden');
                        lockEl.textContent = `Too many attempts. Try again in ${formatClock(lockRemaining)}.`;
                        attemptsInfoEl.textContent = 'Attempts left: 0 / 3';
                        timerEl.textContent = '';
                        setVerificationDisabled(true);
                        return;
                    }

                    lockEl.classList.add('hidden');
                    setVerificationDisabled(false);

                    if (otpRemaining > 0) {
                        timerEl.textContent = '';
                    } else {
                        timerEl.textContent = '';
                    }

                    attemptsInfoEl.textContent = `Attempts left: ${attemptsRemaining} / 3`;
                };

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
                        if (resendBtnEmail) {
                            resendBtnEmail.disabled = true;
                            resendBtnEmail.classList.add('opacity-60', 'cursor-not-allowed');
                            resendBtnEmail.textContent = 'Sending...';
                        }
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
            }

            const pageToast = document.getElementById('otpPageToast');
            if (pageToast) {
                const dismissToast = () => {
                    pageToast.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
                };
                requestAnimationFrame(() => {
                    pageToast.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
                });
                const closeBtn = document.getElementById('otpPageToastClose');
                if (closeBtn) {
                    closeBtn.addEventListener('click', dismissToast);
                }
                window.setTimeout(dismissToast, 3200);
            }
        });
    </script>
</body>
</html>

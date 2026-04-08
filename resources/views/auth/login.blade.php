<!doctype html>
<html>
<head>
    @include('partials.favicon')
    <title>{{ ($activeTab ?? 'login') === 'register' ? 'Register' : 'Login' }} | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.3/build/css/intlTelInput.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .auth-serif { font-family: 'Cormorant Garamond', serif; }
        .font-sans { font-family: 'Inter', sans-serif; }

        .auth-form-wrapper {
            width: 100%;
            max-width: 28rem;
            margin: 0 auto;
        }

        .tab-indicator {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1), width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Slider container */
        .slider-container {
            position: relative;
            overflow: hidden;
            width: 100%;
        }

        /* Both panels sit side by side */
        .slider-track {
            display: flex;
            width: 200%;
            transition: transform 0.45s cubic-bezier(0.4, 0, 0.2, 1);
            will-change: transform;
        }

        .slider-track.show-register {
            transform: translateX(-50%);
        }

        .slide-panel {
            width: 50%;
            flex-shrink: 0;
            box-sizing: border-box;
        }

        .floating-group {
            position: relative;
        }

        .floating-input {
            width: 100%;
            background-color: white;
            border: 1px solid rgb(229 231 235);
            border-radius: 0.75rem;
            color: rgb(17 24 39);
            outline: none;
            transition: all 0.2s ease;
        }

        .floating-input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 1px #f59e0b;
        }

        .floating-label {
            position: absolute;
            left: 3rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgb(156 163 175);
            font-size: 1rem;
            line-height: 1;
            pointer-events: none;
            transition: all 0.18s ease;
            z-index: 20;
            background: white;
            padding: 0 0.35rem;
        }

        .floating-input:focus + .floating-label,
        .floating-input:not(:placeholder-shown) + .floating-label {
            top: 0;
            transform: translateY(-50%);
            font-size: 0.75rem;
            color: #d97706;
        }

        .floating-label.no-icon {
            left: 1rem;
        }

        .phone-floating-group {
            position: relative;
        }

        .phone-floating-group .floating-input.phone-input {
            width: 100%;
            padding-bottom: 0.75rem;
            padding-left: 4rem;
            padding-right: 1rem;
            background-color: white;
            border: 1px solid rgb(229 231 235);
            border-radius: 0.75rem;
            color: rgb(17 24 39);
            outline: none;
            transition: all 0.2s ease;
        }

        .phone-floating-group .floating-input.phone-input:focus {
            border-color: #f59e0b;
            box-shadow: 0 0 0 1px #f59e0b;
        }

        .phone-prefix {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgb(55 65 81);
            font-weight: 600;
            line-height: 1;
            z-index: 15;
            pointer-events: none;
        }

        .phone-floating-label {
            position: absolute;
            left: 4rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgb(156 163 175);
            font-size: 1rem;
            line-height: 1;
            pointer-events: none;
            transition: all 0.2s ease;
            z-index: 20;
        }

        .phone-floating-group.is-focused .phone-floating-label,
        .phone-floating-group.has-value .phone-floating-label {
            top: 0;
            transform: translateY(-50%);
            font-size: 0.75rem;
            color: #d97706;
            background: white;
            padding: 0 0.25rem;
        }
    </style>
</head>
<body class="bg-white font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 lg:p-16">
            <div class="auth-form-wrapper">
                <a href="{{ url('/') }}" class="inline-flex items-center  mb-5 text-xs font-semibold hover:text-amber-300 bg-white hover:bg-amber-600 rounded-full p-2 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M11.707 4.293a1 1 0 010 1.414L8.414 9H16a1 1 0 110 2H8.414l3.293 3.293a1 1 0 01-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>

                <!-- Tabs -->
                <div class="relative mb-8">
                    <div class="flex gap-8 border-b border-gray-200">
                        <button type="button" onclick="showTab('login')" id="tab-login" class="pb-3 font-medium transition-colors text-amber-400">
                            Sign In
                        </button>
                        <button type="button" onclick="showTab('register')" id="tab-register" class="pb-3 font-medium transition-colors text-gray-400 hover:text-gray-600">
                            Create Account
                        </button>
                    </div>
                    <div id="tab-indicator" class="tab-indicator absolute bottom-0 left-0 h-0.5 bg-amber-400"></div>
                </div>

                <!-- Slider container -->
                <div class="slider-container">
                    <div class="slider-track" id="slider-track">

                        <!-- LOGIN PANEL -->
                        <div class="slide-panel" id="panel-login">
                            <h2 class="text-3xl auth-serif font-semibold text-gray-900 mb-2">Welcome Back!</h2>
                            <p class="text-gray-500 mb-8">Please enter your details to access your collection.</p>

                            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                                @csrf

                                <!-- Email -->
                                <div>
                                    <div class="floating-group">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                        <input type="email" name="email" id="login-email" value="{{ old('email') }}" placeholder=" " class=" floating-input peer pl-12 pr-12 py-4" >
                                        <label for="login-email" class="floating-label">Email Address</label>
                                    </div>

                                    @error('email')
                                        @if(($activeTab ?? 'login') === 'login')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <div class="floating-group">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </span>

                                        <input id="login-password" type="password" name="password" placeholder=" " class="floating-input pl-12 pr-12 py-4">
                                        <label for="login-password" class="floating-label">Password</label>

                                        <button type="button" onclick="togglePasswordField('login-password', 'login-eye-open', 'login-eye-closed')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none z-10">
                                            <svg id="login-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <svg id="login-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                            </svg>
                                        </button>
                                    </div>

                                    @error('password')
                                        @if(($activeTab ?? 'login') === 'login')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>

                                <!-- Remember & Forgot -->
                                <div class="flex items-center justify-between">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                        <span class="text-gray-600 text-sm">Remember me</span>
                                    </label>
                                    <a href="{{ route('password.request') }}" class="text-amber-400 text-sm font-medium hover:text-amber-800 transition-colors">Forgot password?</a>
                                </div>

                                <button type="submit" class="w-full py-4 bg-amber-400 text-white font-medium rounded-xl hover:bg-amber-800 transition-all flex items-center justify-center gap-2 group">
                                    Sign In
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </button>
                            </form>

                            <!-- Divider -->
                            <div class="flex items-center gap-4 my-6">
                                <div class="flex-1 h-px bg-gray-200"></div>
                                <span class="text-gray-400 text-sm">OR</span>
                                <div class="flex-1 h-px bg-gray-200"></div>
                            </div>

                            <!-- Social Login -->
                            @if ((env('GOOGLE_CLIENT_ID') && env('GOOGLE_CLIENT_SECRET')) || (env('GITHUB_CLIENT_ID') && env('GITHUB_CLIENT_SECRET')) || (env('FACEBOOK_CLIENT_ID') && env('FACEBOOK_CLIENT_SECRET')))
                                <div class="space-y-3">
                                    @if (env('GOOGLE_CLIENT_ID') && env('GOOGLE_CLIENT_SECRET'))
                                        <a class="bg-background text-foreground flex w-full items-center justify-center space-x-2 rounded-lg border px-4 py-2 text-sm font-medium transition-colors duration-200 hover:opacity-70" type="button" href="{{ route('auth.redirect', ['provider' => 'google'] + (request()->has('redirect') ? ['redirect' => request()->get('redirect')] : [])) }}">
                                            <svg class="h-auto w-4" width="40" height="42" viewBox="0 0 46 47" fill="none">
                                                <path d="M46 24.0287C46 22.09 45.8533 20.68 45.5013 19.2112H23.4694V27.9356H36.4069C36.1429 30.1094 34.7347 33.37 31.5957 35.5731L31.5663 35.8669L38.5191 41.2719L38.9885 41.3306C43.4477 37.2181 46 31.1669 46 24.0287Z" fill="#4285F4" />
                                                <path d="M23.4694 47C29.8061 47 35.1161 44.9144 39.0179 41.3012L31.625 35.5437C29.6301 36.9244 26.9898 37.8937 23.4987 37.8937C17.2793 37.8937 12.0281 33.7812 10.1505 28.1412L9.88649 28.1706L2.61097 33.7812L2.52296 34.0456C6.36608 41.7125 14.287 47 23.4694 47Z" fill="#34A853" />
                                                <path d="M10.1212 28.1413C9.62245 26.6725 9.32908 25.1156 9.32908 23.5C9.32908 21.8844 9.62245 20.3275 10.0918 18.8588V18.5356L2.75765 12.8369L2.52296 12.9544C0.909439 16.1269 0 19.7106 0 23.5C0 27.2894 0.909439 30.8731 2.49362 34.0456L10.1212 28.1413Z" fill="#FBBC05" />
                                                <path d="M23.4694 9.07688C27.8699 9.07688 30.8622 10.9863 32.5344 12.5725L39.1645 6.11C35.0867 2.32063 29.8061 0 23.4694 0C14.287 0 6.36607 5.2875 2.49362 12.9544L10.0918 18.8588C11.9987 13.1894 17.25 9.07688 23.4694 9.07688Z" fill="#EB4335" />
                                            </svg>
                                            <span>
                                                {{ __('Continue with Google') }}
                                            </span>
                                        </a>
                                    @endif
                                </div>

                            @endif
                        </div>
                        <!-- END LOGIN PANEL -->

                        <!-- REGISTER PANEL -->
                        <div class="slide-panel" id="panel-register">
                            <h2 class="text-3xl auth-serif font-semibold text-gray-900 mb-2 p-1">Join our Circle</h2>
                            <p class="text-gray-500 mb-6">Create an account to curate your wishlist.</p>

                            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                                @csrf

                                <!-- Name Fields -->
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                                <!-- First Name -->
                                <div class="floating-group">
                                    <input type="text" pattern="^[A-Za-z\s]+$" name="first_name" id="first_name" value="{{ old('first_name') }}" placeholder=" " class="floating-input pl-4 pr-4 py-4" maxlength="30" inputmode="text" autocomplete="given-name" title="First name must contain letters only.">
                                    <label for="first_name" class="floating-label no-icon">
                                        First Name
                                    </label>

                                    @error('first_name')
                                        @if(($activeTab ?? 'login') === 'register')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>

                                <!-- Last Name -->
                                <div class="floating-group">
                                    <input type="text" pattern="^[A-Za-z\s]+$" name="last_name" id="last_name" value="{{ old('last_name') }}" placeholder=" " class="floating-input pl-4 pr-4 py-4" maxlength="30" inputmode="text" autocomplete="family-name" title="Last name must contain letters only.">
                                    <label for="last_name" class="floating-label no-icon">
                                        Last Name
                                    </label>

                                    @error('last_name')
                                        @if(($activeTab ?? 'login') === 'register')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>

                            </div>

                                <!-- Email -->
                                <div>
                                    <div class="floating-group">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </span>

                                        <input type="email" name="email" id="register-email" value="{{ old('email', session('email')) }}" placeholder=" " class="floating-input pl-12 pr-4 py-4" maxlength="50">
                                        <label for="register-email" class="floating-label">Email Address</label>
                                    </div>

                                    @error('email')
                                        @if(($activeTab ?? 'login') === 'register')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div class="phone-floating-group relative">
                                    <div class="relative">
                                        <span class="phone-prefix">+63</span>
                                        <input id="register-phone" pattern="[0-9]*" type="tel" name="phone" value="{{ old('phone') }}" placeholder=" " class="floating-input phone-input w-full py-3 leading-tight" inputmode="numeric" maxlength="11">
                                        <label for="register-phone" class="phone-floating-label pt-1 pb-1 py-4">
                                            Phone Number
                                        </label>
                                    </div>

                                    @error('phone')
                                        @if(($activeTab ?? 'login') === 'register')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @endif
                                    @enderror
                                    <p id="register-phone-validity" class="hidden text-xs mt-2"></p>
                                </div>

                                <!-- Password -->
                                <div>
                                    <div class="floating-group">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </span>

                                        <input id="register-password" type="password" name="password" placeholder=" " class="floating-input pl-12 pr-12 py-4" minlength="8" autocomplete="new-password" maxlength="15">
                                        <label for="register-password" class="floating-label">Create Password</label>

                                        <button type="button" onclick="togglePasswordField('register-password', 'register-eye-open', 'register-eye-closed')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none z-10">
                                            <svg id="register-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <svg id="register-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                            </svg>
                                        </button>
                                    </div>

                                    @error('password')
                                        @if(($activeTab ?? 'login') === 'register')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @endif
                                    @enderror

                                    <p id="register-password-strength" class="hidden text-xs mt-2 text-gray-500">Password strength: Weak</p>
                                    <p id="register-password-rules" class="hidden text-xs mt-1 text-gray-500">Use at least 8 characters with uppercase, lowercase, and a number.</p>
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <div class="floating-group">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 z-10">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </span>

                                        <input id="register-password-confirm" type="password" name="password_confirmation" placeholder=" " class="floating-input pl-12 pr-12 py-4" maxlength="15">
                                        <label for="register-password-confirm" class="floating-label">Confirm Password</label>

                                        <button type="button" onclick="togglePasswordField('register-password-confirm', 'confirm-eye-open', 'confirm-eye-closed')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none z-10">
                                            <svg id="confirm-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <svg id="confirm-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <p id="register-password-match" class="hidden text-xs mt-2">Passwords match.</p>
                                </div>

                                <!-- Terms -->
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" name="terms" id="terms" class="w-4 h-4 mt-0.5 rounded border-gray-300 text-amber-400 focus:ring-amber-500">
                                        <label for="terms" class="text-gray-600 text-sm leading-tight cursor-pointer">
                                        I agree to the <a href="#" id="openTermsLink" onclick="openTermsModal('terms'); return false;" class="text-amber-400 hover:text-amber-600 underline">Terms of Service</a> and <a href="#" id="openPrivacyLink" onclick="openTermsModal('privacy'); return false;" class="text-amber-400 hover:text-amber-600 underline">Privacy Policy</a>.
                                    </label>
                                </div>
                                @error('terms')
                                    @if(($activeTab ?? 'login') === 'register')
                                        <span class="text-red-500 text-sm block">{{ $message }}</span>
                                    @endif
                                @enderror

                                <button id="signupBtn" type="submit" disabled class="w-full py-4 bg-amber-400 text-white font-medium rounded-xl hover:bg-amber-600 transition-all flex items-center justify-center gap-2 group disabled:opacity-50 disabled:cursor-not-allowed">
                                    Create Account
                                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                        <!-- END REGISTER PANEL -->

                    </div><!-- end slider-track -->
                </div><!-- end slider-container -->

            </div>
        </div>

        <!-- Right Side - Hero Image -->
        <div class="hidden lg:block lg:w-1/2 relative bg-slate-800">
            <img src="{{ asset('IMAGES/BG.png') }}" alt="Lumina Collection" class="absolute inset-0 w-full h-full object-cover">
            <div class="absolute inset-0 bg-linear-to-t from-slate-900/80 via-slate-900/20 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-12">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full border border-white/20 mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 w-5 h-5 text-amber-400">
  <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5Z" />
</svg>

                    <span class="text-white text-sm font-medium tracking-wider uppercase">Golden Hour Collection</span>
                </div>
                <h2 class="text-5xl lg:text-6xl font-serif text-white leading-tight mb-4">Elegance in<br>every detail.</h2>
                <p class="text-gray-300 text-lg max-w-md">Discover our handcrafted collection made with ethically sourced materials and timeless design principles.</p>
            </div>
        </div>

            @include('partials.terms_modal')
    </div>

    @include('partials.toast')

    <script src="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.3/build/js/intlTelInput.min.js"></script>
    <script>
        const activeTab = '{{ $activeTab ?? 'login' }}';

        function normalizePhilippineMobile(value) {
            const digits = (value || '').replace(/\D/g, '');

            if (/^09\d{9}$/.test(digits)) {
                return digits;
            }

            if (/^9\d{9}$/.test(digits)) {
                return `0${digits}`;
            }

            if (/^639\d{9}$/.test(digits)) {
                return `0${digits.slice(2)}`;
            }

            return digits;
        }

        function showTab(tab) {
            const track = document.getElementById('slider-track');
            const tabLogin = document.getElementById('tab-login');
            const tabRegister = document.getElementById('tab-register');
            const indicator = document.getElementById('tab-indicator');

            if (tab === 'register') {
                track.classList.add('show-register');
                tabLogin.classList.remove('text-amber-700');
                tabLogin.classList.add('text-gray-400');
                tabRegister.classList.remove('text-gray-400');
                tabRegister.classList.add('text-amber-400');
                indicator.style.transform = 'translateX(' + (tabLogin.offsetWidth + 32) + 'px)';
                indicator.style.width = tabRegister.offsetWidth + 'px';
                history.pushState({}, '', '{{ route('register.form') }}');
                document.title = 'Register | Lumina';
            } else {
                track.classList.remove('show-register');
                tabLogin.classList.remove('text-gray-400');
                tabLogin.classList.add('text-amber-400');
                tabRegister.classList.remove('text-amber-700');
                tabRegister.classList.add('text-gray-400');
                indicator.style.transform = 'translateX(0)';
                indicator.style.width = tabLogin.offsetWidth + 'px';
                history.pushState({}, '', '{{ route('login') }}');
                document.title = 'Login | Lumina';
            }
        }

        function togglePasswordField(inputId, eyeOpenId, eyeClosedId) {
            const input = document.getElementById(inputId);
            const eyeOpen = document.getElementById(eyeOpenId);
            const eyeClosed = document.getElementById(eyeClosedId);
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
            const indicator = document.getElementById('tab-indicator');
            const tabLogin = document.getElementById('tab-login');
            indicator.style.width = tabLogin.offsetWidth + 'px';

            if (activeTab === 'register') {
                showTab('register');
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            const termsCheckbox = document.querySelector('input[name="terms"]');
            const signupBtn = document.getElementById('signupBtn');
            const registerPassword = document.getElementById('register-password');
            const registerPasswordConfirm = document.getElementById('register-password-confirm');
            const registerPhone = document.getElementById('register-phone');
            const firstNameInput = document.getElementById('first_name');
            const lastNameInput = document.getElementById('last_name');
            const phoneValidityLabel = document.getElementById('register-phone-validity');
            const strengthLabel = document.getElementById('register-password-strength');
            const rulesLabel = document.getElementById('register-password-rules');
            const matchLabel = document.getElementById('register-password-match');

            const normalizeAndLimitPhilippineMobile = (value) => {
                const normalized = normalizePhilippineMobile(value || '');
                const digitsOnly = normalized.replace(/\D/g, '');
                return digitsOnly.slice(0, 11);
            };

            const sanitizeNameInput = (value) => (value || '')
                .replace(/[^A-Za-z\s]/g, '')
                .replace(/\s{2,}/g, ' ');

            function updatePhoneValidityState() {
                if (!registerPhone || !phoneValidityLabel) {
                    return false;
                }

                const phoneValue = registerPhone.value || '';
                if (phoneValue.length === 0) {
                    phoneValidityLabel.className = 'hidden text-xs mt-2';
                    phoneValidityLabel.textContent = '';
                    return false;
                }

                const isValid = /^09\d{9}$/.test(phoneValue);
                phoneValidityLabel.classList.remove('hidden');

                if (isValid) {
                    phoneValidityLabel.textContent = 'Valid Philippine number.';
                    phoneValidityLabel.className = 'text-xs mt-2 text-green-600';
                } else {
                    phoneValidityLabel.textContent = 'Use exactly 11 digits: 09XXXXXXXXX.';
                    phoneValidityLabel.className = 'text-xs mt-2 text-red-500';
                }

                return isValid;
            }

            function updateSignupButtonState() {
                if (!signupBtn) {
                    return;
                }

                const termsAccepted = !!(termsCheckbox && termsCheckbox.checked);
                const password = registerPassword ? (registerPassword.value || '') : '';
                const confirmPassword = registerPasswordConfirm ? (registerPasswordConfirm.value || '') : '';
                const passwordsMatch = password.length > 0 && confirmPassword.length > 0 && password === confirmPassword;
                const phoneValid = registerPhone ? /^09\d{9}$/.test(registerPhone.value || '') : false;

                signupBtn.disabled = !(termsAccepted && passwordsMatch && phoneValid);
            }

            function updateRegisterPasswordStrength() {
                if (!registerPassword || !strengthLabel || !rulesLabel) {
                    return;
                }

                const value = registerPassword.value || '';
                const hasMinLength = value.length >= 8;
                const hasLower = /[a-z]/.test(value);
                const hasUpper = /[A-Z]/.test(value);
                const hasNumber = /\d/.test(value);
                const isStrong = hasMinLength && hasLower && hasUpper && hasNumber;

                if (value.length === 0) {
                    strengthLabel.classList.add('hidden');
                    rulesLabel.classList.add('hidden');
                    return;
                }

                strengthLabel.classList.remove('hidden');
                rulesLabel.classList.remove('hidden');

                if (isStrong) {
                    strengthLabel.textContent = 'Password strength: Strong';
                    strengthLabel.className = 'text-xs mt-2 text-green-600';
                    rulesLabel.textContent = 'Use at least 8 characters with uppercase, lowercase, and a number.';
                    rulesLabel.className = 'text-xs mt-1 text-gray-500';
                } else {
                    strengthLabel.textContent = 'Password strength: Weak';
                    strengthLabel.className = 'text-xs mt-2 text-red-500';
                    rulesLabel.textContent = 'Missing requirement: 8+ chars, uppercase, lowercase, and number.';
                    rulesLabel.className = 'text-xs mt-1 text-red-500';
                }
            }

            function updatePasswordMatch() {
                if (!registerPassword || !registerPasswordConfirm || !matchLabel) {
                    return;
                }

                const password = registerPassword.value || '';
                const confirmPassword = registerPasswordConfirm.value || '';

                if (confirmPassword.length === 0) {
                    matchLabel.classList.add('hidden');
                    matchLabel.textContent = 'Passwords match.';
                    matchLabel.className = 'hidden text-xs mt-2';
                    updateSignupButtonState();
                    return;
                }

                matchLabel.classList.remove('hidden');

                if (password === confirmPassword) {
                    matchLabel.textContent = 'Passwords match.';
                    matchLabel.className = 'text-xs mt-2 text-green-600';
                } else {
                    matchLabel.textContent = 'Passwords do not match.';
                    matchLabel.className = 'text-xs mt-2 text-red-500';
                }

                updateSignupButtonState();
            }

            if (registerPassword) {
                registerPassword.addEventListener('input', function () {
                    updateRegisterPasswordStrength();
                    updatePasswordMatch();
                });

                registerPassword.addEventListener('blur', function () {
                    updateRegisterPasswordStrength(false);
                });
            }

            if (registerPasswordConfirm) {
                registerPasswordConfirm.addEventListener('input', updatePasswordMatch);
                registerPasswordConfirm.addEventListener('blur', updatePasswordMatch);
            }

            if (termsCheckbox) {
                termsCheckbox.addEventListener('change', updateSignupButtonState);
            }

            updateSignupButtonState();

            [firstNameInput, lastNameInput].forEach(function (input) {
                if (!input) return;

                input.addEventListener('input', function () {
                    const cursor = input.selectionStart;
                    const cleaned = sanitizeNameInput(input.value);
                    if (cleaned !== input.value) {
                        input.value = cleaned;
                        if (typeof cursor === 'number') {
                            const nextPos = Math.max(0, cursor - 1);
                            input.setSelectionRange(nextPos, nextPos);
                        }
                    }
                });
            });

            if (registerPhone) {
                registerPhone.addEventListener('input', function () {
                    registerPhone.value = normalizeAndLimitPhilippineMobile(registerPhone.value);
                    updatePhoneValidityState();
                    updateSignupButtonState();
                });

                registerPhone.addEventListener('blur', function () {
                    registerPhone.value = normalizeAndLimitPhilippineMobile(registerPhone.value);
                    updatePhoneValidityState();
                    updateSignupButtonState();
                });

                const registerForm = registerPhone.closest('form');
                if (registerForm) {
                    registerForm.addEventListener('submit', function () {
                        if (firstNameInput) {
                            firstNameInput.value = sanitizeNameInput(firstNameInput.value).trim();
                        }
                        if (lastNameInput) {
                            lastNameInput.value = sanitizeNameInput(lastNameInput.value).trim();
                        }
                        registerPhone.value = normalizeAndLimitPhilippineMobile(registerPhone.value);
                    });
                }
            }

            updatePhoneValidityState();
            updateSignupButtonState();
        });

                document.addEventListener('DOMContentLoaded', function () {
                const phoneInput = document.getElementById('register-phone');
                const phoneWrapper = phoneInput.closest('.phone-floating-group');

                function updatePhoneLabel() {
                    if (phoneInput.value.trim() !== '') {
                        phoneWrapper.classList.add('has-value');
                    } else {
                        phoneWrapper.classList.remove('has-value');
                    }
                }

                phoneInput.addEventListener('focus', function () {
                    phoneWrapper.classList.add('is-focused');
                });

                phoneInput.addEventListener('blur', function () {
                    phoneWrapper.classList.remove('is-focused');
                    updatePhoneLabel();
                });

                phoneInput.addEventListener('input', updatePhoneLabel);

                updatePhoneLabel();
            });
    </script>
</body>
</html>

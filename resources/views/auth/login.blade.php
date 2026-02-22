<!doctype html>
<html>
<head>
    <title>{{ ($activeTab ?? 'login') === 'register' ? 'Register' : 'Login' }} | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,500;1,600;1,700&family=Inter:wght@300;400;500;600&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        .font-serif { font-family: 'Cormorant Garamond', serif; }
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
    </style>
</head>
<body class="bg-white font-sans antialiased">
    @include('partials.navbar', ['authPage' => true])

    <div class="min-h-screen flex pt-16">
        <!-- Left Side - Form -->
        <div class="w-full lg:w-1/2 flex flex-col justify-center p-8 lg:p-16">
            <div class="auth-form-wrapper">

                <!-- Tabs -->
                <div class="relative mb-8">
                    <div class="flex gap-8 border-b border-gray-200">
                        <button type="button" onclick="showTab('login')" id="tab-login" class="pb-3 font-medium transition-colors text-amber-700">
                            Sign In
                        </button>
                        <button type="button" onclick="showTab('register')" id="tab-register" class="pb-3 font-medium transition-colors text-gray-400 hover:text-gray-600">
                            Create Account
                        </button>
                    </div>
                    <div id="tab-indicator" class="tab-indicator absolute bottom-0 left-0 h-0.5 bg-amber-700"></div>
                </div>

                <!-- Slider container -->
                <div class="slider-container">
                    <div class="slider-track" id="slider-track">

                        <!-- LOGIN PANEL -->
                        <div class="slide-panel" id="panel-login">
                            <h2 class="text-3xl font-serif font-semibold text-gray-900 mb-2">Welcome back</h2>
                            <p class="text-gray-500 mb-8">Please enter your details to access your collection.</p>

                            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                                @csrf

                                @if(session('error') && ($activeTab ?? 'login') === 'login')
                                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
                                @endif
                                @if(session('success') && ($activeTab ?? 'login') === 'login')
                                    <div class="bg-green-50 border border-green-200 text-green-600 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
                                @endif

                                <!-- Email -->
                                <div>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                        <input type="email" name="email" value="{{ old('email') }}"
                                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-gray-900 placeholder-gray-400 transition-all"
                                            placeholder="Email address">
                                    </div>
                                    @error('email')
                                        @if(($activeTab ?? 'login') === 'login')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </span>
                                        <input id="login-password" type="password" name="password"
                                            class="w-full pl-12 pr-12 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-gray-900 placeholder-gray-400 transition-all"
                                            placeholder="Password">
                                        <button type="button" onclick="togglePasswordField('login-password', 'login-eye-open', 'login-eye-closed')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
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
                                    <a href="{{ route('password.request') }}" class="text-amber-700 text-sm font-medium hover:text-amber-800 transition-colors">Forgot password?</a>
                                </div>

                                <button type="submit" class="w-full py-4 bg-amber-700 text-white font-medium rounded-xl hover:bg-amber-800 transition-all flex items-center justify-center gap-2 group">
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

                                    @if (env('FACEBOOK_CLIENT_ID') && env('FACEBOOK_CLIENT_SECRET'))
                                        <a class="bg-background text-foreground flex w-full items-center justify-center space-x-2 rounded-lg border px-4 py-2 text-sm font-medium transition-colors duration-200 hover:opacity-70" type="button" href="{{ route('auth.redirect', ['provider' => 'facebook'] + (request()->has('redirect') ? ['redirect' => request()->get('redirect')] : [])) }}">
                                            <svg class="w-4.5 h-auto" xmlns="http://www.w3.org/2000/svg" viewBox="-204.79995 -341.33325 1774.9329 2047.9995">
                                                <path d="M1365.333 682.667C1365.333 305.64 1059.693 0 682.667 0 305.64 0 0 305.64 0 682.667c0 340.738 249.641 623.16 576 674.373V880H402.667V682.667H576v-150.4c0-171.094 101.917-265.6 257.853-265.6 74.69 0 152.814 13.333 152.814 13.333v168h-86.083c-84.804 0-111.25 52.623-111.25 106.61v128.057h189.333L948.4 880H789.333v477.04c326.359-51.213 576-333.635 576-674.373" fill="#1877f2" />
                                                <path d="M948.4 880l30.267-197.333H789.333V554.609C789.333 500.623 815.78 448 900.584 448h86.083V280s-78.124-13.333-152.814-13.333c-155.936 0-257.853 94.506-257.853 265.6v150.4H402.667V880H576v477.04a687.805 687.805 0 00106.667 8.293c36.288 0 71.91-2.84 106.666-8.293V880H948.4" fill="#fff" />
                                            </svg>
                                            <span>
                                                {{ __('Continue with Facebook') }}
                                            </span>
                                        </a>
                                    @endif

                                    @if (env('GITHUB_CLIENT_ID') && env('GITHUB_CLIENT_SECRET'))
                                        <a class="bg-background text-foreground flex w-full items-center justify-center space-x-2 rounded-lg border px-4 py-2 text-sm font-medium transition-colors duration-200 hover:opacity-70" type="button" href="{{ route('auth.redirect', ['provider' => 'github'] + (request()->has('redirect') ? ['redirect' => request()->get('redirect')] : [])) }}">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 0C5.374 0 0 5.373 0 12 0 17.302 3.438 21.8 8.207 23.387c.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23A11.509 11.509 0 0112 5.803c1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576C20.566 21.797 24 17.3 24 12c0-6.627-5.373-12-12-12z" />
                                            </svg>
                                            <span>
                                                {{ __('Continue with GitHub') }}
                                            </span>
                                        </a>
                                    @endif
                                </div>

                                <div class="my-4 mb-3 flex items-center text-xs uppercase text-gray-400 before:me-6 before:flex-1 before:border-t before:border-gray-200 after:ms-6 after:flex-1 after:border-t after:border-gray-200 dark:text-neutral-500 dark:before:border-neutral-600 dark:after:border-neutral-600">{{ __('or') }}</div>
                            @endif
                        </div>
                        <!-- END LOGIN PANEL -->

                        <!-- REGISTER PANEL -->
                        <div class="slide-panel" id="panel-register">
                            <h2 class="text-3xl font-serif font-semibold text-gray-900 mb-2">Join the Circle</h2>
                            <p class="text-gray-500 mb-6">Create an account to curate your wishlist.</p>

                            <form method="POST" action="{{ route('register.post') }}" class="space-y-4">
                                @csrf

                                @if(session('error') && ($activeTab ?? 'login') === 'register')
                                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
                                @endif

                                <!-- Name Fields -->
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}"
                                            class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-gray-900 placeholder-gray-400 transition-all"
                                            placeholder="First Name">
                                        @error('first_name')
                                            @if(($activeTab ?? 'login') === 'register')
                                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                            @endif
                                        @enderror
                                    </div>
                                    <div>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}"
                                            class="w-full px-4 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-gray-900 placeholder-gray-400 transition-all"
                                            placeholder="Last Name">
                                        @error('last_name')
                                            @if(($activeTab ?? 'login') === 'register')
                                                <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                            @endif
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                        <input type="email" name="email" value="{{ old('email', session('email')) }}"
                                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-gray-900 placeholder-gray-400 transition-all"
                                            placeholder="Email address">
                                    </div>
                                    @error('email')
                                        @if(($activeTab ?? 'login') === 'register')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </span>
                                        <input type="text" name="phone" value="{{ old('phone') }}"
                                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-gray-900 placeholder-gray-400 transition-all"
                                            placeholder="Mobile phone number"
                                            maxlength="11"
                                            oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 11) this.value = this.value.slice(0, 11);">
                                    </div>
                                    @error('phone')
                                        @if(($activeTab ?? 'login') === 'register')
                                            <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                                        @endif
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                        </span>
                                        <input id="register-password" type="password" name="password"
                                            class="w-full pl-12 pr-12 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-gray-900 placeholder-gray-400 transition-all"
                                            placeholder="Create Password">
                                        <button type="button" onclick="togglePasswordField('register-password', 'register-eye-open', 'register-eye-closed')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
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
                                </div>

                                <!-- Confirm Password -->
                                <div>
                                    <div class="relative">
                                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </span>
                                        <input id="register-password-confirm" type="password" name="password_confirmation"
                                            class="w-full pl-12 pr-12 py-4 bg-gray-50 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-600 focus:ring-1 focus:ring-amber-600 text-gray-900 placeholder-gray-400 transition-all"
                                            placeholder="Confirm Password">
                                        <button type="button" onclick="togglePasswordField('register-password-confirm', 'confirm-eye-open', 'confirm-eye-closed')" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg id="confirm-eye-open" class="w-5 h-5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <svg id="confirm-eye-closed" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <!-- Terms -->
                                <div class="flex items-start gap-3">
                                    <input type="checkbox" name="terms" id="terms" class="w-4 h-4 mt-0.5 rounded border-gray-300 text-amber-600 focus:ring-amber-500">
                                    <label for="terms" class="text-gray-600 text-sm leading-tight">
                                        I agree to the <a href="#" class="text-amber-700 hover:text-amber-800 underline">Terms of Service</a> and <a href="#" class="text-amber-700 hover:text-amber-800 underline">Privacy Policy</a>.
                                    </label>
                                </div>
                                @error('terms')
                                    @if(($activeTab ?? 'login') === 'register')
                                        <span class="text-red-500 text-sm block">{{ $message }}</span>
                                    @endif
                                @enderror

                                <button type="submit" class="w-full py-4 bg-amber-700 text-white font-medium rounded-xl hover:bg-amber-800 transition-all flex items-center justify-center gap-2 group">
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
                    <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <span class="text-white text-sm font-medium tracking-wider uppercase">Golden Hour Collection</span>
                </div>
                <h2 class="text-5xl lg:text-6xl font-serif text-white leading-tight mb-4">Elegance in<br>every detail.</h2>
                <p class="text-gray-300 text-lg max-w-md">Discover our handcrafted collection made with ethically sourced materials and timeless design principles.</p>
            </div>
        </div>
    </div>

    <script>
        const activeTab = '{{ $activeTab ?? 'login' }}';

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
                tabRegister.classList.add('text-amber-700');
                indicator.style.transform = 'translateX(' + (tabLogin.offsetWidth + 32) + 'px)';
                indicator.style.width = tabRegister.offsetWidth + 'px';
                history.pushState({}, '', '{{ route('register.form') }}');
                document.title = 'Register | Lumina';
            } else {
                track.classList.remove('show-register');
                tabLogin.classList.remove('text-gray-400');
                tabLogin.classList.add('text-amber-700');
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
    </script>
</body>
</html>
<!doctype html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    <title>@yield('title', 'Lumina')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    <link rel="shortcut icon" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/intl-tel-input@25.12.3/build/css/intlTelInput.css">
    <style>
        .iti {
            width: 100%;
        }
    </style>
</head>
<body class="bg-stone-100 text-gray-900 font-sans antialiased min-h-screen">
@php
    $settingsLink = route('profile.edit');
    $isProfileRoute = request()->routeIs('profile.*');
    $pendingOrderCount = 0;
    if (auth()->check()) {
        try {
            $pendingOrderCount = auth()->user()
                ->orders()
                ->whereIn('status', ['pending', 'confirmed', 'processing'])
                ->count();
        } catch (\Throwable $e) {
            $pendingOrderCount = 0;
        }
    }
@endphp

<div id="customerSidebarOverlay" class="fixed inset-0 z-30 hidden bg-black/40 backdrop-blur-sm lg:hidden" onclick="closeCustomerSidebar()"></div>

<div class="min-h-screen flex">
    <aside id="customerSidebar" class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full lg:translate-x-0 lg:static bg-white border-r border-amber-100 flex flex-col transition-transform duration-200 ease-out shadow-sm">
        <div class="px-6 py-6 border-b border-amber-100">
            <div class="flex items-center gap-2">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('IMAGES/Lumina (1).svg') }}" alt="Lumina" class="h-10 sm:h-11 w-auto origin-left scale-[2.90] transition-transform duration-300">
                </a>
            </div>
        </div>
        @auth
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                @if(Auth::user()->profile_photo_url)
                    <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="w-11 h-11 rounded-full object-cover border-2 border-amber-200">
                @else
                    <div class="w-11 h-11 bg-linear-to-br from-amber-300 to-amber-500 rounded-full flex items-center justify-center text-white text-lg font-bold shadow-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                @endif
                <div>
                    <p class="text-gray-900 text-base font-semibold leading-tight">{{ Auth::user()->name }}</p>
                    <p class="text-gray-400 text-xs mt-0.5 truncate max-w-[160px]">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>
        @endauth
        <nav class="px-3 py-4 space-y-4">
            <div>
                <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Overview</p>
                <div class="space-y-1">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('dashboard') ? 'text-amber-600 bg-amber-50 font-semibold' : 'text-gray-600 hover:text-amber-600 hover:bg-amber-50/70' }} transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                        </svg>
                        <span class="text-md font-medium">Dashboard</span>
                    </a>
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('orders.*') ? 'text-amber-600 bg-amber-50 font-semibold' : 'text-gray-600 hover:text-amber-600 hover:bg-amber-50/70' }} transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        <span class="text-md font-medium">My Orders</span>
                        @if($pendingOrderCount > 0)
                            <span class="ml-auto inline-flex items-center rounded-full bg-amber-100 px-2 py-0.5 text-[11px] font-semibold text-amber-700">{{ $pendingOrderCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('wishlist.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('wishlist.*') ? 'text-amber-600 bg-amber-50 font-semibold' : 'text-gray-600 hover:text-amber-600 hover:bg-amber-50/70' }} transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 8.25c0-2.485-2.015-4.5-4.5-4.5-1.74 0-3.247.99-3.998 2.437A4.502 4.502 0 0 0 8.5 3.75C6.015 3.75 4 5.765 4 8.25c0 7.22 8.5 12 8.5 12S21 15.47 21 8.25Z" />
                        </svg>
                        <span class="text-md font-medium">Wishlist</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Account</p>
                <div class="space-y-1">
                    <a href="{{ $settingsLink }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ $isProfileRoute ? 'text-amber-600 bg-amber-50 font-semibold' : 'text-gray-600 hover:text-amber-600 hover:bg-amber-50/70' }} transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="text-md font-medium">Settings</span>
                    </a>
                </div>
            </div>

            <div>
                <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Support</p>
                <div class="space-y-1">
                    <a href="{{ route('support.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl {{ request()->routeIs('support.*') ? 'text-amber-600 bg-amber-50 font-semibold' : 'text-gray-600 hover:text-amber-600 hover:bg-amber-50/70' }} transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.742-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.035.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.966-1.584A6.062 6.062 0 0 1 6 18.75m12-6.75a6 6 0 0 0-12 0v.75a6 6 0 0 0 12 0V12Z" />
                        </svg>
                        <span class="text-md font-medium">Help & Support</span>
                    </a>
                </div>
            </div>
        </nav>
        <div class="mt-auto p-4 border-t border-gray-100">
            <form method="POST" action="{{ route('logout') }}" data-logout-form>
                @csrf
                <button type="button" onclick="showLogoutModal()" class="w-full px-4 py-2.5 rounded-xl bg-red-50 text-red-500 hover:bg-red-100 transition-colors text-sm font-semibold">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="flex-1">
        <div class="lg:hidden sticky top-0 z-30 bg-white border-b border-amber-100 px-4 py-3 flex items-center justify-between shadow-sm">
            <button type="button" onclick="openCustomerSidebar()" class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600" aria-label="Open menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <img src="{{ asset('IMAGES/Lumina (1).svg') }}" alt="Lumina" class="h-8 w-auto">
            <a href="{{ $settingsLink }}" class="text-xs text-gray-500 hover:text-amber-600">Settings</a>
        </div>

        <main class="min-h-screen">
            @yield('content')
        </main>
    </div>
</div>

<div id="logoutModal" class="fixed inset-0 z-50 hidden" aria-labelledby="logout-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="hideLogoutModal()"></div>
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
        <div class="w-full max-w-sm rounded-2xl bg-white text-gray-900 border border-gray-200 shadow-xl">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-1">
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100">
                        <svg class="h-5 w-5 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </div>
                    <h3 id="logout-title" class="text-base font-semibold text-gray-900">Confirm Logout</h3>
                </div>
                <p class="mt-2 text-sm text-gray-500 ml-13">Are you sure you want to log out of your account?</p>
            </div>
            <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
                <button type="button" onclick="hideLogoutModal()" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 hover:bg-gray-200 text-sm font-medium transition-colors">Cancel</button>
                <form method="POST" action="{{ route('logout') }}" class="m-0" data-logout-form>
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-lg bg-red-600 text-white font-semibold text-sm hover:bg-red-500 transition-colors disabled:opacity-70 disabled:cursor-not-allowed">Logout</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function openCustomerSidebar() {
        const sidebar = document.getElementById('customerSidebar');
        const overlay = document.getElementById('customerSidebarOverlay');
        if (sidebar) sidebar.classList.remove('-translate-x-full');
        if (overlay) overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeCustomerSidebar() {
        const sidebar = document.getElementById('customerSidebar');
        const overlay = document.getElementById('customerSidebarOverlay');
        if (sidebar) sidebar.classList.add('-translate-x-full');
        if (overlay) overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function showLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) modal.classList.remove('hidden');
    }

    function hideLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) modal.classList.add('hidden');
    }

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 1024) {
            const overlay = document.getElementById('customerSidebarOverlay');
            if (overlay) overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        } else {
            const sidebar = document.getElementById('customerSidebar');
            if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.add('-translate-x-full');
            }
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('form[data-logout-form]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (form.dataset.submitting === '1') {
                    event.preventDefault();
                    return;
                }

                form.dataset.submitting = '1';
                form.querySelectorAll('button[type="submit"], input[type="submit"]').forEach(function (submitter) {
                    submitter.disabled = true;
                    if (submitter.tagName === 'BUTTON') {
                        submitter.dataset.originalText = submitter.textContent;
                        submitter.textContent = 'Logging out...';
                    }
                });
            });
        });
    });

</script>

@stack('scripts')
@include('partials.toast')
</body>
</html>

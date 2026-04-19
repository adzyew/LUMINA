<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @include('partials.theme_init')
    <title>@yield('title', 'Lumina Admin')</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    <link rel="shortcut icon" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    <link rel="apple-touch-icon" href="{{ asset('IMAGES/FinalIcon.png') }}" />
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <style>
        #adminSidebarNav {
            scrollbar-width: none;
            scrollbar-color: transparent transparent;
        }

        #adminSidebarNav.is-scrolling {
            scrollbar-width: thin;
            scrollbar-color: rgba(148, 163, 184, 0.85) transparent;
        }

        #adminSidebarNav::-webkit-scrollbar {
            width: 0;
            height: 0;
        }

        #adminSidebarNav.is-scrolling::-webkit-scrollbar {
            width: 8px;
        }

        #adminSidebarNav.is-scrolling::-webkit-scrollbar-track {
            background: transparent;
        }

        #adminSidebarNav.is-scrolling::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.85);
            border-radius: 9999px;
        }
    </style>
</head>
<body class="bg-[#F8F8F8] text-gray-900 font-sans antialiased flex h-screen overflow-hidden">

    <div id="adminSidebarOverlay" class="fixed inset-0 z-30 hidden bg-black/40 backdrop-blur-sm md:hidden" onclick="closeAdminSidebar()"></div>

    <aside id="adminSidebar" class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full md:translate-x-0 md:static bg-[#F8F8F8]/95 backdrop-blur-md border-r border-gray-200 flex flex-col shrink-0 transition-transform duration-200 ease-out">
        @php
            $isAdmin = auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false);
            $staffDepartment = null;
            if (!$isAdmin) {
                if (auth()->user()->hasRole('inventory_manager') || auth()->user()->can('inventory.view')) {
                    $staffDepartment = 'Inventory';
                } elseif (auth()->user()->hasRole('sales_staff') || auth()->user()->can('sales.view') || auth()->user()->can('returns.manage')) {
                    $staffDepartment = 'Sales';
                } elseif (auth()->user()->hasRole('delivery_staff') || auth()->user()->can('deliveries.manage')) {
                    $staffDepartment = 'Delivery';
                } elseif (auth()->user()->hasRole('feedback_moderator') || auth()->user()->can('reviews.moderate')) {
                    $staffDepartment = 'Feedback';
                }
            }
        @endphp
        <div class="p-6 flex flex-col gap-1 border-b border-gray-200 min-h-20 justify-center">
            <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center font-bold text-black">L</div>
                <span class="font-playfair font-bold text-xl text-black">{{ $isAdmin ? 'Lumina Admin' : 'Lumina Staff' }}</span>
            </div>
            @if($staffDepartment)
                <span class="text-xs text-gray-600 font-medium">{{ $staffDepartment }} Department</span>
            @endif
        </div>

        <nav id="adminSidebarNav" class="grow p-2 space-y-2 overflow-y-auto overflow-x-hidden pr-1">
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Main Menu</p>
            @php
                $dashboardUrl = (auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false))
                    ? route('admin.admin_dashboard')
                    : route('admin.staff.dashboard');
                $dashboardActive = request()->routeIs('admin.admin_dashboard') || request()->routeIs('admin.staff.dashboard') || request()->routeIs('admin.inventory.dashboard') || request()->routeIs('admin.sales.dashboard') || request()->routeIs('admin.delivery.dashboard');
            @endphp
            <a href="{{ $dashboardUrl }}" class="flex items-center gap-3 px-4 py-3 text-gray-900 {{ $dashboardActive ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
                </svg>
                Dashboard
            </a>

            {{-- Inventory department: Dashboard + Products only --}}
            @if($isAdmin || $staffDepartment === 'Inventory')
            @can('inventory.view')
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.products.*') ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Products
            </a>
            @endcan
            @endif
            <div></div>
            {{-- Sales department: Orders, Analytics, Sales only --}}
            @if($isAdmin || $staffDepartment === 'Sales')
            @can('sales.view')
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">Transactions</p>
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Orders
            </a>
            <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.analytics.*') ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Analytics
            </a>
            <a href="{{ route('admin.sales.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.sales.index') ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-philippine-peso-icon lucide-philippine-peso size-5">
                    <path d="M20 11H4"/><path d="M20 7H4"/><path d="M7 21V4a1 1 0 0 1 1-1h4a1 1 0 0 1 0 12H7"/>
                </svg>
                Sales
            </a>
            @endcan
            @can('returns.manage')
            <a href="{{ route('admin.returns.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.returns.*') ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7.5h13.5m0 0L13.5 4.5m3 3-3 3m3 6h4.5m0 0-3-3m3 3-3 3M3 16.5h9"></path></svg>
                Returns
            </a>
            @endcan
            @endif

            {{-- Delivery department: Deliveries only --}}
            @if($isAdmin || $staffDepartment === 'Delivery')
            @can('deliveries.manage')
            <a href="{{ route('admin.deliveries.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.deliveries.*') ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 18.75a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h6m-9 0H3.375a1.125 1.125 0 0 1-1.125-1.125V14.25m17.25 4.5a1.5 1.5 0 0 1-3 0m3 0a1.5 1.5 0 0 0-3 0m3 0h1.125c.621 0 1.129-.504 1.09-1.124a17.902 17.902 0 0 0-3.213-9.193 2.056 2.056 0 0 0-1.58-.86H14.25M16.5 18.75h-2.25m0-11.177v-.958c0-.568-.422-1.048-.987-1.106a48.554 48.554 0 0 0-10.026 0 1.106 1.106 0 0 0-.987 1.106v7.635m12-6.677v6.677m0 4.5v-4.5m0 0h-12" />
            </svg>
                Deliveries
            </a>
            @endcan
            @endif

            @if($isAdmin || $staffDepartment === 'Feedback')
            @can('reviews.moderate')
            <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">CRM</p>
            <a href="{{ route('admin.feedback.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.feedback.*') ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-message-square-more-icon lucide-message-square-more size-5">
                <path d="M22 17a2 2 0 0 1-2 2H6.828a2 2 0 0 0-1.414.586l-2.202 2.202A.71.71 0 0 1 2 21.286V5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2z"/><path d="M12 11h.01"/><path d="M16 11h.01"/><path d="M8 11h.01"/>
            </svg>               
                Feedback
            </a>
            @endcan
            @endif

            @role('admin')
                <a href="{{ route('admin.promos.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.promos.*') ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                    </svg>
                    Promos
                </a>
                <p class="px-3 pb-1 text-[11px] font-semibold uppercase tracking-wider text-gray-400">User Management</p>

                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.users.*') ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    Users
                </a>

                <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.roles.*') ? 'bg-amber-100/50 font-bold text-gray-900 border-l-amber-300 border-l-6' : 'text-gray-900 hover:bg-amber-50 font-bold' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Roles & Permissions
                </a>
            @endrole
        </nav>

        <div class="p-3 border-t border-gray-200">
            <button type="button" onclick="showLogoutModal()" class="w-full flex items-center justify-center gap-2 px-4 py-3 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 font-semibold transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Logout
            </button>
        </div>
    </aside>

    <main class="flex-1 min-w-0 overflow-y-auto bg-[#F4F4F4] p-4 sm:p-8 md:p-10">
        <div class="md:hidden sticky top-0 z-20 -mx-4 mb-4 px-4 py-3 bg-[#F8F8F8]/95 backdrop-blur-md border-b border-gray-200 flex items-center justify-between">
            <button type="button" onclick="openAdminSidebar()" class="inline-flex items-center justify-center rounded-lg border border-gray-300 p-2 text-gray-700" aria-label="Open sidebar menu">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
            </button>
            <span class="font-playfair font-bold text-lg text-black">Lumina Admin</span>
            <div class="w-10"></div>
        </div>
        @yield('content')
    </main>

    <div id="logoutModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="hideLogoutModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-200">
                    <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Confirm Logout</h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">Are you sure you want to log out of your account?</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <form id="adminLogoutForm" method="POST" action="{{ route('logout') }}" class="m-0">
                            @csrf
                            <button id="adminLogoutSubmitButton" type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors disabled:opacity-70 disabled:cursor-not-allowed">
                                Logout
                            </button>
                        </form>
                        <button type="button" onclick="hideLogoutModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ↓ THIS IS THE FIX: page-level scripts injected here (modals, charts, etc.) --}}
    @include('partials.toast')
    @stack('scripts')

    <script>
        function openAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminSidebarOverlay');
            if (sidebar) sidebar.classList.remove('-translate-x-full');
            if (overlay) overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminSidebarOverlay');
            if (sidebar) sidebar.classList.add('-translate-x-full');
            if (overlay) overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function initAdminSidebarAutoScrollbar() {
            const nav = document.getElementById('adminSidebarNav');
            if (!nav) return;

            let hideTimer;
            nav.addEventListener('scroll', function () {
                nav.classList.add('is-scrolling');
                clearTimeout(hideTimer);
                hideTimer = setTimeout(function () {
                    nav.classList.remove('is-scrolling');
                }, 700);
            }, { passive: true });
        }

        window.addEventListener('resize', function () {
            if (window.innerWidth >= 768) {
                const overlay = document.getElementById('adminSidebarOverlay');
                if (overlay) overlay.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            } else {
                const sidebar = document.getElementById('adminSidebar');
                if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                    sidebar.classList.add('-translate-x-full');
                }
            }
        });

        function showLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.remove('hidden');
        }

        function hideLogoutModal() {
            const modal = document.getElementById('logoutModal');
            modal.classList.add('hidden');
        }

        function toggleStaffProfileDropdown() {
            const menu = document.getElementById('staffProfileDropdownMenu');
            const chevron = document.getElementById('staffProfileChevron');
            if (!menu || !chevron) return;

            const isOpen = !menu.classList.contains('opacity-0');
            if (isOpen) {
                menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                menu.classList.remove('opacity-100', 'scale-100');
                chevron.classList.remove('rotate-180');
            } else {
                menu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                menu.classList.add('opacity-100', 'scale-100');
                chevron.classList.add('rotate-180');
            }
        }

        function closeStaffProfileDropdown() {
            const menu = document.getElementById('staffProfileDropdownMenu');
            const chevron = document.getElementById('staffProfileChevron');
            if (!menu || !chevron) return;

            menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            menu.classList.remove('opacity-100', 'scale-100');
            chevron.classList.remove('rotate-180');
        }

        function fetchAndSwapAdminSection(url, containerSelector, pushHistory) {
            const target = document.querySelector(containerSelector);
            if (!target) return;

            target.classList.add('opacity-60', 'pointer-events-none');

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/html',
                },
            })
                .then(function (response) {
                    if (!response.ok) throw new Error('Request failed');
                    return response.text();
                })
                .then(function (html) {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newSection = doc.querySelector(containerSelector);
                    const currentSection = document.querySelector(containerSelector);

                    if (!newSection || !currentSection) {
                        window.location.href = url;
                        return;
                    }

                    currentSection.innerHTML = newSection.innerHTML;

                    if (pushHistory) {
                        window.history.pushState({ adminAutoFilter: true }, '', url);
                    }

                    initAdminAutoFilters();
                    document.dispatchEvent(new CustomEvent('admin:content-refreshed'));
                })
                .catch(function () {
                    window.location.href = url;
                })
                .finally(function () {
                    const refreshedTarget = document.querySelector(containerSelector);
                    if (refreshedTarget) {
                        refreshedTarget.classList.remove('opacity-60', 'pointer-events-none');
                    }
                });
        }

        function buildAutoFilterUrl(form) {
            const url = new URL(form.action || window.location.href, window.location.origin);
            const params = new URLSearchParams();
            const formData = new FormData(form);

            formData.forEach(function (value, key) {
                if (value !== null && String(value).trim() !== '') {
                    params.append(key, String(value));
                }
            });

            const queryString = params.toString();
            url.search = queryString ? ('?' + queryString) : '';
            return url.toString();
        }

        function initAdminAutoFilters() {
            document.querySelectorAll('.js-admin-auto-filter').forEach(function (form, index) {
                if (form.dataset.autoFilterBound === '1') return;
                form.dataset.autoFilterBound = '1';

                const containerSelector = form.dataset.autofilterContainer || '#admin-auto-filter-content';
                const textDelay = Number(form.dataset.autofilterDelay || 450);
                const timerKey = '__autoFilterTimer' + index;

                form.addEventListener('submit', function (event) {
                    event.preventDefault();
                    fetchAndSwapAdminSection(buildAutoFilterUrl(form), containerSelector, true);
                });

                form.querySelectorAll('input, select, textarea').forEach(function (field) {
                    if (field.type === 'hidden') return;

                    if (field.tagName === 'SELECT' || field.type === 'checkbox' || field.type === 'radio') {
                        field.addEventListener('change', function () {
                            fetchAndSwapAdminSection(buildAutoFilterUrl(form), containerSelector, true);
                        });
                        return;
                    }

                    field.addEventListener('input', function () {
                        clearTimeout(form[timerKey]);
                        form[timerKey] = setTimeout(function () {
                            fetchAndSwapAdminSection(buildAutoFilterUrl(form), containerSelector, true);
                        }, textDelay);
                    });
                });
            });

            document.querySelectorAll('a[data-autofilter-link="1"]').forEach(function (link) {
                if (link.dataset.autoFilterBound === '1') return;
                link.dataset.autoFilterBound = '1';

                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const selector = link.dataset.autofilterContainer || '#admin-auto-filter-content';
                    fetchAndSwapAdminSection(link.href, selector, true);
                });
            });

            document.querySelectorAll('a[data-autofilter-reset="1"]').forEach(function (link) {
                if (link.dataset.autoFilterBound === '1') return;
                link.dataset.autoFilterBound = '1';

                link.addEventListener('click', function (event) {
                    event.preventDefault();
                    const selector = link.dataset.autofilterContainer || '#admin-auto-filter-content';
                    fetchAndSwapAdminSection(link.href, selector, true);
                });
            });
        }

        document.addEventListener('click', function (e) {
            const staffWrapper = document.getElementById('staffProfileDropdownWrapper');
            if (staffWrapper && !staffWrapper.contains(e.target)) {
                closeStaffProfileDropdown();
            }
        });

        window.addEventListener('popstate', function () {
            const activeContainer = document.querySelector('[data-admin-autofilter-root="1"]');
            if (!activeContainer) return;

            fetchAndSwapAdminSection(window.location.href, '#' + activeContainer.id, false);
        });

        document.addEventListener('DOMContentLoaded', function () {
            initAdminSidebarAutoScrollbar();
            initAdminAutoFilters();

            const logoutForm = document.getElementById('adminLogoutForm');
            const logoutButton = document.getElementById('adminLogoutSubmitButton');
            if (!logoutForm || !logoutButton) return;

            logoutForm.addEventListener('submit', function (event) {
                if (logoutForm.dataset.submitting === '1') {
                    event.preventDefault();
                    return;
                }

                logoutForm.dataset.submitting = '1';
                logoutButton.disabled = true;
                logoutButton.textContent = 'Logging out...';
            });

            const originalHideLogoutModal = hideLogoutModal;
            hideLogoutModal = function () {
                logoutForm.dataset.submitting = '0';
                logoutButton.disabled = false;
                logoutButton.textContent = 'Confirm';
                originalHideLogoutModal();
            };
        });
    </script>
</body>
</html>

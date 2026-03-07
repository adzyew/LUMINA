<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    <title>@yield('title', 'Lumina Admin')</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-[#F8F8F8] text-gray-900 dark:bg-gray-900 dark:text-gray-100 font-sans antialiased flex h-screen overflow-hidden transition-colors">

    <aside class="w-64 bg-[#F8F8F8]/95 dark:bg-gray-900 backdrop-blur-md border-r border-gray-200 dark:border-white/10 hidden md:flex flex-col shrink-0 flex-shrink-0">
        @php
            $isAdmin = auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false);
            $staffDepartment = null;
            if (!$isAdmin) {
                if (auth()->user()->hasRole('inventory_manager') || auth()->user()->can('inventory.view')) {
                    $staffDepartment = 'Inventory';
                } elseif (auth()->user()->hasRole('sales_staff') || auth()->user()->can('sales.view')) {
                    $staffDepartment = 'Sales';
                } elseif (auth()->user()->hasRole('delivery_staff') || auth()->user()->can('deliveries.manage')) {
                    $staffDepartment = 'Delivery';
                }
            }
        @endphp
        <div class="p-6 flex flex-col gap-1 border-b border-gray-200 dark:border-white/10 min-h-[5rem] justify-center">
            <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center font-bold text-black">L</div>
                <span class="font-playfair font-bold text-xl text-black dark:text-white">{{ $isAdmin ? 'Lumina Admin' : 'Lumina Staff' }}</span>
            </div>
            @if($staffDepartment)
                <span class="text-xs text-gray-600 dark:text-gray-400 font-medium">{{ $staffDepartment }} Department</span>
            @endif
        </div>

        <nav class="grow p-2 space-y-2">
            @php
                $dashboardUrl = (auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false))
                    ? route('admin.admin_dashboard')
                    : route('admin.staff.dashboard');
                $dashboardActive = request()->routeIs('admin.admin_dashboard') || request()->routeIs('admin.inventory.dashboard') || request()->routeIs('admin.sales.dashboard') || request()->routeIs('admin.delivery.dashboard');
            @endphp
            <a href="{{ $dashboardUrl }}" class="flex items-center gap-3 px-4 py-3 {{ $dashboardActive ? 'bg-amber-300 text-black font-bold' : 'text-black dark:text-gray-200 hover:text-amber-300 font-bold' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            {{-- Inventory department: Dashboard + Products only --}}
            @if($isAdmin || $staffDepartment === 'Inventory')
            @can('inventory.view')
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.products.*') ? 'bg-amber-300 text-black font-bold' : 'text-black dark:text-gray-200 hover:text-amber-300 font-bold' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Products
            </a>
            @endcan
            @endif

            {{-- Sales department: Orders, Analytics, Sales only --}}
            @if($isAdmin || $staffDepartment === 'Sales')
            @can('sales.view')
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'bg-amber-300 text-black font-bold' : 'text-black dark:text-gray-200 hover:text-amber-300 font-bold' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Orders
            </a>
            <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.analytics.*') ? 'bg-amber-300 text-black font-bold' : 'text-black dark:text-gray-200 hover:text-amber-300 font-bold' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Analytics
            </a>
            <a href="{{ route('admin.sales.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.sales.*') ? 'bg-amber-300 text-black font-bold' : 'text-black dark:text-gray-200 hover:text-amber-300 font-bold' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Sales
            </a>
            @endcan
            @endif

            {{-- Delivery department: Deliveries only --}}
            @if($isAdmin || $staffDepartment === 'Delivery')
            @can('deliveries.manage')
            <a href="{{ route('admin.deliveries.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.deliveries.*') ? 'bg-amber-300 text-black font-bold' : 'text-black dark:text-gray-200 hover:text-amber-300 font-bold' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                Deliveries
            </a>
            @endcan
            @endif

            @role('admin')
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.users.*') ? 'bg-amber-300 text-black font-bold' : 'text-black dark:text-gray-200 hover:text-amber-300 font-bold' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    Users
                </a>

                <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.roles.*') ? 'bg-amber-300 text-black font-bold' : 'text-black dark:text-gray-200 hover:text-amber-300 font-bold' }} rounded-lg transition-colors">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Roles & Permissions
                </a>
            @endrole
        </nav>

        <div class="p-4 border-t border-gray-200 dark:border-white/10 flex flex-col gap-2">
            <div class="flex items-center gap-2 px-4 py-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">Theme</span>
                @include('partials.theme_toggle')
            </div>
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Are you sure you want to logout?');">
                @csrf
                <button type="button" onclick="showLogoutModal()" class="w-full mt-4 flex items-center justify-center gap-2 px-4 py-2 bg-red-500/10 text-red-400 border border-red-500/20 rounded-lg hover:bg-red-500/20 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 min-w-0 overflow-y-auto bg-[#F4F4F4] dark:bg-gray-900 p-6 sm:p-10 transition-colors">
        @yield('content')
    </main>

    <div id="logoutModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="hideLogoutModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-200 dark:border-white/10">
                <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">Confirm Logout</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to log out of your account?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors">
                            Confirm
                        </button>
                    </form>
                    <button type="button" onclick="hideLogoutModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
        <script>
            function showLogoutModal() {
                const modal = document.getElementById('logoutModal');
                modal.classList.remove('hidden');
            }

            function hideLogoutModal() {
                const modal = document.getElementById('logoutModal');
                modal.classList.add('hidden');
            }
        </script>
</html>
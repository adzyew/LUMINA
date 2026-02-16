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
<body class="bg-[#F4F4F4] text-gray-900 dark:bg-gray-900 dark:text-gray-100 font-sans antialiased flex h-screen overflow-hidden transition-colors">

    <aside class="w-64 bg-gray-900 border-r border-white/10 hidden md:flex flex-col shrink-0 flex-shrink-0">
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
        <div class="p-6 flex flex-col gap-1 border-b border-white/10 min-h-[5rem] justify-center">
            <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center font-bold text-black">L</div>
                <span class="font-playfair font-bold text-xl text-white">{{ $isAdmin ? 'Lumina Admin' : 'Lumina Staff' }}</span>
            </div>
            @if($staffDepartment)
                <span class="text-xs text-gray-400 font-medium">{{ $staffDepartment }} Department</span>
            @endif
        </div>

        <nav class="grow p-4 space-y-2">
            @php
                $dashboardUrl = (auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false))
                    ? route('admin.admin_dashboard')
                    : route('admin.staff.dashboard');
                $dashboardActive = request()->routeIs('admin.admin_dashboard') || request()->routeIs('admin.inventory.dashboard') || request()->routeIs('admin.sales.dashboard') || request()->routeIs('admin.delivery.dashboard');
            @endphp
            <a href="{{ $dashboardUrl }}" class="flex items-center gap-3 px-4 py-3 {{ $dashboardActive ? 'bg-amber-300 text-black font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            {{-- Inventory department: Dashboard + Products only --}}
            @if($isAdmin || $staffDepartment === 'Inventory')
            @can('inventory.view')
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.products.*') ? 'bg-amber-300 text-black font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Products
            </a>
            @endcan
            @endif

            {{-- Sales department: Orders, Analytics, Sales only --}}
            @if($isAdmin || $staffDepartment === 'Sales')
            @can('sales.view')
            <a href="{{ route('admin.orders.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'bg-amber-300 text-black font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Orders
            </a>
            <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.analytics.*') ? 'bg-amber-300 text-black font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Analytics
            </a>
            <a href="{{ route('admin.sales.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.sales.*') ? 'bg-amber-300 text-black font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Sales
            </a>
            @endcan
            @endif

            {{-- Delivery department: Deliveries only --}}
            @if($isAdmin || $staffDepartment === 'Delivery')
            @can('deliveries.manage')
            <a href="{{ route('admin.deliveries.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.deliveries.*') ? 'bg-amber-300 text-black font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                Deliveries
            </a>
            @endcan
            @endif

            @role('admin')
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.users.*') || request()->routeIs('admin.roles.*') ? 'bg-amber-300 text-black font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Users & Roles
            </a>
            @endrole
        </nav>

        <div class="p-4 border-t border-white/10 flex flex-col gap-2">
            <div class="flex items-center gap-2 px-4 py-2">
                <span class="text-sm text-gray-500">Theme</span>
                @include('partials.theme_toggle')
            </div>
            <form action="{{ route('logout') }}" method="POST" onsubmit="return confirm('Are you sure you want to logout?');">
                @csrf
                <button class="w-full mt-4 flex items-center justify-center gap-2 px-4 py-2 bg-red-500/10 text-red-400 border border-red-500/20 rounded-lg hover:bg-red-500/20 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 min-w-0 overflow-y-auto bg-[#F4F4F4] dark:bg-gray-900 p-6 sm:p-10 transition-colors">
        @yield('content')
    </main>

</body>
</html>
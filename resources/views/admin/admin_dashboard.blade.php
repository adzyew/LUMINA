@extends('admin.admin_layout')

@section('title', 'Admin Dashboard | Lumina')

@section('content')

    {{-- Top Bar --}}
    <div class="flex items-center justify-between mb-10 bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl px-6 py-3 shadow-sm dark:shadow-none">
        {{-- Search --}}
        <header class="flex items-center gap-3">
        <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Overview</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-3">Welcome back, Admin!</p>
    </header>
        <div class="">
        </div>
        {{-- Right Side --}}
        <div class="flex items-center gap-5">
            
           
            {{-- Divider --}}
            <div class="w-px h-6 bg-gray-200 dark:bg-white/10"></div>

            {{-- Profile Dropdown (vanilla JS, no Alpine dependency) --}}
            <div class="relative" id="profileDropdownWrapper">
                <button onclick="toggleProfileDropdown()" id="profileDropdownBtn" class="flex items-center gap-3 cursor-pointer">
                    {{-- Avatar --}}
                    <div class="w-9 h-9 rounded-full bg-amber-400 dark:bg-amber-300 flex items-center justify-center text-black font-bold text-sm shrink-0">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    {{-- Name + Role --}}
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white leading-tight">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400 leading-tight">Administrator</p>
                    </div>
                    {{-- Chevron --}}
                    <svg id="profileChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown Menu --}}
                <div
                    id="profileDropdownMenu"
                    class="absolute right-0 mt-3 w-52 bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 rounded-2xl shadow-xl z-50 overflow-hidden opacity-0 scale-95 pointer-events-none transition-all duration-150 ease-out origin-top-right"
                >
                    {{-- User info header --}}
                    <div class="px-4 py-3 border-b border-gray-100 dark:border-white/10">
                        <p class="text-sm font-semibold text-gray-800 dark:text-white">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                    {{-- Links --}}
                    <div class="py-1">
                        <a href="{{ route('admin.profile.show') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            My Profile
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
                            Settings
                        </a>
                        <div class="my-1 border-t border-gray-100 dark:border-white/10"></div>
                        <button type="button" onclick="showLogoutModal(); closeProfileDropdown();" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleProfileDropdown() {
            const menu    = document.getElementById('profileDropdownMenu');
            const chevron = document.getElementById('profileChevron');
            const isOpen  = !menu.classList.contains('opacity-0');

            if (isOpen) {
                // Close
                menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                menu.classList.remove('opacity-100', 'scale-100');
                chevron.classList.remove('rotate-180');
            } else {
                // Open
                menu.classList.remove('opacity-0', 'scale-95', 'pointer-events-none');
                menu.classList.add('opacity-100', 'scale-100');
                chevron.classList.add('rotate-180');
            }
        }

        function closeProfileDropdown() {
            const menu = document.getElementById('profileDropdownMenu');
            const chevron = document.getElementById('profileChevron');

            if (!menu || !chevron) return;

            menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
            menu.classList.remove('opacity-100', 'scale-100');
            chevron.classList.remove('rotate-180');
        }

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('profileDropdownWrapper');
            const menu    = document.getElementById('profileDropdownMenu');
            const chevron = document.getElementById('profileChevron');

            if (wrapper && !wrapper.contains(e.target)) {
                closeProfileDropdown();
            }
        });
        
    </script>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-linear-to-br from-amber-50 to-white dark:from-gray-900 dark:to-gray-900 border border-amber-200/60 dark:border-white/10 rounded-3xl p-6 relative overflow-hidden group shadow-sm dark:shadow-none">
            <div class="absolute -right-5 -top-5 w-20 h-20 bg-amber-300/20 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-gray-600 dark:text-gray-400 text-md font-semibold">Revenue</h3>
                <div class="w-13 h-13 rounded-2xl bg-amber-400/20 text-amber-700 dark:text-amber-300 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-amber-700 dark:text-amber-300">₱{{ number_format($totalRevenue ?? 0, 2) }}</p>
            <p class="mt-3 text-xs {{ ($revenueChange ?? 0) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                {{ ($revenueChange ?? 0) >= 0 ? '+' : '' }}{{ $revenueChange ?? 0 }}% vs last month
            </p>
        </div>

        <div class="bg-linear-to-br from-blue-50 to-white dark:from-gray-900 dark:to-gray-900 border border-blue-200/60 dark:border-white/10 rounded-3xl p-6 relative overflow-hidden shadow-sm dark:shadow-none">
            <div class="absolute -right-5 -top-5 w-20 h-20 bg-blue-300/20 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-gray-600 dark:text-gray-400 text-md font-semibold">Orders</h3>
                <div class="w-13 h-13 rounded-2xl bg-blue-400/20 text-blue-700 dark:text-blue-300 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                {{ $ordersByStatus['pending'] ?? 0 }} pending, {{ $ordersByStatus['processing'] ?? 0 }} processing
            </p>
        </div>

        <div class="bg-linear-to-br from-emerald-50 to-white dark:from-gray-900 dark:to-gray-900 border border-emerald-200/60 dark:border-white/10 rounded-3xl p-6 relative overflow-hidden shadow-sm dark:shadow-none">
            <div class="absolute -right-5 -top-5 w-20 h-20 bg-emerald-300/20 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-gray-600 dark:text-gray-400 text-md font-semibold">Products</h3>
                <div class="w-13 h-13 rounded-2xl bg-emerald-400/20 text-emerald-700 dark:text-emerald-300 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                {{ $inventoryStatuses['in_stock'] ?? 0 }} in stock, {{ $inventoryStatuses['low_stock'] ?? 0 }} low stock
            </p>
        </div>

        <div class="bg-linear-to-br from-violet-50 to-white dark:from-gray-900 dark:to-gray-900 border border-violet-200/60 dark:border-white/10 rounded-3xl p-6 relative overflow-hidden shadow-sm dark:shadow-none">
            <div class="absolute -right-5 -top-5 w-20 h-20 bg-violet-300/20 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-gray-600 dark:text-gray-400 text-md font-semibold">Users</h3>
                <div class="w-13 h-13 rounded-2xl bg-violet-400/20 text-violet-700 dark:text-violet-300 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 00-4-4h-1m-4 5H3v-1a4 4 0 014-4h6a4 4 0 014 4v1zM9 7a4 4 0 118 0 4 4 0 01-8 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
            <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                {{ $staffUsers ?? 0 }} staff, {{ $customerUsers ?? 0 }} customers
            </p>
        </div>
    </div>

    {{-- Department Status Overview --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Inventory Status</h3>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl border border-blue-500/20 bg-blue-500/10 p-3">
                    <p class="text-[11px] uppercase tracking-wide text-blue-600 dark:text-blue-400">Total</p>
                    <p class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ $inventoryStatuses['total'] ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-green-500/20 bg-green-500/10 p-3">
                    <p class="text-[11px] uppercase tracking-wide text-green-600 dark:text-green-400">In Stock</p>
                    <p class="text-xl font-bold text-green-700 dark:text-green-300">{{ $inventoryStatuses['in_stock'] ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-amber-500/20 bg-amber-500/10 p-3">
                    <p class="text-[11px] uppercase tracking-wide text-amber-600 dark:text-amber-400">Low Stock</p>
                    <p class="text-xl font-bold text-amber-700 dark:text-amber-300">{{ $inventoryStatuses['low_stock'] ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-3">
                    <p class="text-[11px] uppercase tracking-wide text-red-600 dark:text-red-400">Out Stock</p>
                    <p class="text-xl font-bold text-red-700 dark:text-red-300">{{ $inventoryStatuses['out_of_stock'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Sales Status</h3>
            </div>
            <div class="grid grid-cols-3 gap-2">
                <div class="rounded-lg border border-amber-500/20 bg-amber-500/10 p-2 text-center">
                    <p class="text-[10px] uppercase text-amber-600 dark:text-amber-400">Pending</p>
                    <p class="font-bold text-amber-700 dark:text-amber-300">{{ $salesStatuses['pending'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-blue-500/20 bg-blue-500/10 p-2 text-center">
                    <p class="text-[10px] uppercase text-blue-600 dark:text-blue-400">Confirmed</p>
                    <p class="font-bold text-blue-700 dark:text-blue-300">{{ $salesStatuses['confirmed'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-indigo-500/20 bg-indigo-500/10 p-2 text-center">
                    <p class="text-[10px] uppercase text-indigo-600 dark:text-indigo-400">Processing</p>
                    <p class="font-bold text-indigo-700 dark:text-indigo-300">{{ $salesStatuses['processing'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-purple-500/20 bg-purple-500/10 p-2 text-center">
                    <p class="text-[10px] uppercase text-purple-600 dark:text-purple-400">Shipped</p>
                    <p class="font-bold text-purple-700 dark:text-purple-300">{{ $salesStatuses['shipped'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-green-500/20 bg-green-500/10 p-2 text-center">
                    <p class="text-[10px] uppercase text-green-600 dark:text-green-400">Delivered</p>
                    <p class="font-bold text-green-700 dark:text-green-300">{{ $salesStatuses['delivered'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-red-500/20 bg-red-500/10 p-2 text-center">
                    <p class="text-[10px] uppercase text-red-600 dark:text-red-400">Cancelled</p>
                    <p class="font-bold text-red-700 dark:text-red-300">{{ $salesStatuses['cancelled'] ?? 0 }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Delivery Status</h3>
            </div>
            <div class="grid grid-cols-2 gap-3">
                <div class="rounded-xl border border-amber-500/20 bg-amber-500/10 p-3">
                    <p class="text-[11px] uppercase tracking-wide text-amber-600 dark:text-amber-400">To Ship</p>
                    <p class="text-xl font-bold text-amber-700 dark:text-amber-300">{{ $deliveryStatuses['to_ship'] ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-blue-500/20 bg-blue-500/10 p-3">
                    <p class="text-[11px] uppercase tracking-wide text-blue-600 dark:text-blue-400">In Transit</p>
                    <p class="text-xl font-bold text-blue-700 dark:text-blue-300">{{ $deliveryStatuses['in_transit'] ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-green-500/20 bg-green-500/10 p-3">
                    <p class="text-[11px] uppercase tracking-wide text-green-600 dark:text-green-400">Delivered</p>
                    <p class="text-xl font-bold text-green-700 dark:text-green-300">{{ $deliveryStatuses['delivered'] ?? 0 }}</p>
                </div>
                <div class="rounded-xl border border-red-500/20 bg-red-500/10 p-3">
                    <p class="text-[11px] uppercase tracking-wide text-red-600 dark:text-red-400">Cancelled</p>
                    <p class="text-xl font-bold text-red-700 dark:text-red-300">{{ $deliveryStatuses['cancelled'] ?? 0 }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Bottom Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Orders --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-amber-500 dark:text-amber-300 hover:text-amber-600 dark:hover:text-amber-200 transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-500 dark:text-gray-400 text-sm border-b border-gray-200 dark:border-white/10">
                            <th class="pb-3 font-medium">Order ID</th>
                            <th class="pb-3 font-medium">Customer</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($recentOrders as $order)
                        <tr class="border-b border-gray-100 dark:border-white/5 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <td class="py-4 text-gray-900 dark:text-white">#{{ $order->id }}</td>
                            <td class="py-4 text-gray-600 dark:text-gray-300">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $order->status === 'delivered' ? 'bg-green-500/20 text-green-600 dark:text-green-400' : 'bg-amber-500/20 text-amber-600 dark:text-amber-400' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-4 text-right text-gray-900 dark:text-white">₱{{ number_format($order->total_price ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-gray-500 dark:text-gray-400">No recent orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 h-fit shadow-sm dark:shadow-none">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Order Status</h3>
            <div class="space-y-2 mb-6">
                @php
                    $statusLabels = ['pending'=>'Pending','confirmed'=>'Confirmed','processing'=>'Processing','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled'];
                @endphp
                @foreach($statusLabels as $key => $label)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 dark:text-gray-400">{{ $label }}</span>
                    <span class="text-gray-900 dark:text-white font-medium">{{ $ordersByStatus[$key] ?? 0 }}</span>
                </div>
                @endforeach
            </div>

            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="space-y-4">
                <a href="{{ route('admin.analytics.index') }}" class="block w-full py-3 bg-amber-400 dark:bg-amber-300 text-black font-bold text-center rounded-lg hover:bg-amber-500 dark:hover:bg-amber-400 transition-colors">
                    View Analytics
                </a>
                <a href="{{ route('admin.products.create') }}" class="block w-full py-3 bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white font-semibold text-center rounded-lg hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-colors">
                    + Add New Product
                </a>
                <a href="{{ route('admin.users.index') }}" class="block w-full py-3 bg-gray-100 dark:bg-white/5 text-gray-900 dark:text-white font-semibold text-center rounded-lg hover:bg-gray-200 dark:hover:bg-white/10 border border-gray-200 dark:border-white/10 transition-colors">
                    Manage Users & Roles
                </a>
            </div>
        </div>
    </div>

@endsection
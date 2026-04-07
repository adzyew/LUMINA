@extends('admin.admin_layout')

@section('title', 'Admin Dashboard | Lumina')

@section('content')

    {{-- Top Bar --}}
    <div class="flex items-center justify-between mb-10 bg-white border border-gray-200 rounded-2xl px-6 py-3 shadow-sm">
        {{-- Search --}}
        <header class="flex items-center gap-3">
    @include('partials.favicon')
        <h1 class="text-3xl font-playfair font-bold text-gray-900">Overview</h1>
        <p class="text-gray-600 text-sm mt-3">Welcome back, Admin!</p>
    </header>
        <div class="">
        </div>
        {{-- Right Side --}}
        <div class="flex items-center gap-5">
            
           
            {{-- Divider --}}
            <div class="w-px h-6 bg-gray-200"></div>

            {{-- Profile Dropdown (vanilla JS, no Alpine dependency) --}}
            <div class="relative" id="profileDropdownWrapper">
                <button onclick="toggleProfileDropdown()" id="profileDropdownBtn" class="flex items-center gap-3 cursor-pointer">
                    {{-- Avatar --}}
                    <div class="w-9 h-9 rounded-full bg-amber-400 flex items-center justify-center text-black font-bold text-sm shrink-0">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    {{-- Name + Role --}}
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-semibold text-gray-800 leading-tight">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400 leading-tight">Administrator</p>
                    </div>
                    {{-- Chevron --}}
                    <svg id="profileChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown Menu --}}
                <div id="profileDropdownMenu" class="absolute right-0 mt-3 w-52 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 overflow-hidden opacity-0 scale-95 pointer-events-none transition-all duration-150 ease-out origin-top-right">
                    {{-- User info header --}}
                    <div class="px-4 py-3 border-b border-gray-100">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Admin' }}</p>
                        <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                    </div>
                    {{-- Links --}}
                    <div class="py-1">
                        <a href="{{ route('admin.profile.show') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            My Profile
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
                            Settings
                        </a>
                        <div class="my-1 border-t border-gray-100"></div>
                        <button type="button" onclick="showLogoutModal(); closeProfileDropdown();" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
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
        <div class="bg-linear-to-br from-amber-50 to-white border border-amber-200/60 rounded-3xl p-6 relative overflow-hidden group shadow-sm">
            <div class="absolute -right-5 -top-5 w-20 h-20 bg-amber-300/20 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-gray-600 text-md font-semibold">Revenue</h3>
                <div class="w-13 h-13 rounded-2xl bg-amber-400/20 text-amber-700 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-amber-700">₱{{ number_format($totalRevenue ?? 0, 2) }}</p>
            <p class="mt-3 text-xs {{ ($revenueChange ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                {{ ($revenueChange ?? 0) >= 0 ? '+' : '' }}{{ $revenueChange ?? 0 }}% vs last month
            </p>
        </div>

        <div class="bg-linear-to-br from-blue-50 to-white border border-blue-200/60 rounded-3xl p-6 relative overflow-hidden shadow-sm">
            <div class="absolute -right-5 -top-5 w-20 h-20 bg-blue-300/20 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-gray-600 text-md font-semibold">Orders</h3>
                <div class="w-13 h-13 rounded-2xl bg-blue-400/20 text-blue-700 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalOrders }}</p>
            
        </div>

        <div class="bg-linear-to-br from-emerald-50 to-white border border-emerald-200/60 rounded-3xl p-6 relative overflow-hidden shadow-sm">
            <div class="absolute -right-5 -top-5 w-20 h-20 bg-emerald-300/20 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-gray-600 text-md font-semibold">Products</h3>
                <div class="w-13 h-13 rounded-2xl bg-emerald-400/20 text-emerald-700 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalProducts }}</p>
        </div>

        <div class="bg-linear-to-br from-violet-50 to-white border border-violet-200/60 rounded-3xl p-6 relative overflow-hidden shadow-sm">
            <div class="absolute -right-5 -top-5 w-20 h-20 bg-violet-300/20 rounded-full blur-2xl"></div>
            <div class="flex items-start justify-between mb-4">
                <h3 class="text-gray-600 text-md font-semibold">Users</h3>
                <div class="w-13 h-13 rounded-2xl bg-violet-400/20 text-violet-700 flex items-center justify-center">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-1a4 4 0 00-4-4h-1m-4 5H3v-1a4 4 0 014-4h6a4 4 0 014 4v1zM9 7a4 4 0 118 0 4 4 0 01-8 0z"/></svg>
                </div>
            </div>
            <p class="text-3xl font-bold text-gray-900">{{ $totalUsers }}</p>
        </div>
    </div>

    {{-- Department Status Overview --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- Inventory Status Chart --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Inventory Status</h3>
            <div id="inventoryChart"></div>
        </div>

        {{-- Sales Status Chart --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Sales Status</h3>
            <div id="salesStatusChart"></div>
        </div>

        {{-- Delivery Status Chart --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-1">Delivery Status</h3>
            <div id="deliveryChart"></div>
        </div>
    </div>

    <script>
        const isDark = document.documentElement.classList.contains('dark');

        new ApexCharts(document.getElementById('inventoryChart'), {
            chart: { type: 'donut', height: 220, background: 'transparent' },
            series: [
                {{ $inventoryStatuses['in_stock'] ?? 0 }},
                {{ $inventoryStatuses['low_stock'] ?? 0 }},
                {{ $inventoryStatuses['out_of_stock'] ?? 0 }}
            ],
            labels: ['In Stock', 'Low Stock', 'Out of Stock'],
            colors: ['#10b981', '#f59e0b', '#ef4444'],
            legend: { position: 'bottom', labels: { colors: isDark ? '#9ca3af' : '#6b7280' } },
            dataLabels: { enabled: false },
            plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'In Stock', color: isDark ? '#fff' : '#111', formatter: () => '{{ $inventoryStatuses["in_stock"] ?? 0 }}' } } } } },
            stroke: { show: false },
            theme: { mode: isDark ? 'dark' : 'light' },
            tooltip: { theme: isDark ? 'dark' : 'light' },
        }).render();

        new ApexCharts(document.getElementById('salesStatusChart'), {
            chart: { type: 'donut', height: 220, background: 'transparent' },
            series: [
                {{ $salesStatuses['pending'] ?? 0 }},
                {{ $salesStatuses['confirmed'] ?? 0 }},
                {{ $salesStatuses['processing'] ?? 0 }},
                {{ $salesStatuses['shipped'] ?? 0 }},
                {{ $salesStatuses['delivered'] ?? 0 }},
                {{ $salesStatuses['cancelled'] ?? 0 }}
            ],
            labels: ['Pending', 'Confirmed', 'Processing', 'Shipped', 'Delivered', 'Cancelled'],
            colors: ['#f59e0b', '#3b82f6', '#6366f1', '#a855f7', '#10b981', '#ef4444'],
            legend: { position: 'bottom', labels: { colors: isDark ? '#9ca3af' : '#6b7280' } },
            dataLabels: { enabled: false },
            plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Total', color: isDark ? '#fff' : '#111' } } } } },
            stroke: { show: false },
            theme: { mode: isDark ? 'dark' : 'light' },
            tooltip: { theme: isDark ? 'dark' : 'light' },
        }).render();

        new ApexCharts(document.getElementById('deliveryChart'), {
            chart: { type: 'donut', height: 220, background: 'transparent' },
            series: [
                {{ $deliveryStatuses['to_ship'] ?? 0 }},
                {{ $deliveryStatuses['in_transit'] ?? 0 }},
                {{ $deliveryStatuses['delivered'] ?? 0 }},
                {{ $deliveryStatuses['cancelled'] ?? 0 }}
            ],
            labels: ['To Ship', 'In Transit', 'Delivered', 'Cancelled'],
            colors: ['#f59e0b', '#3b82f6', '#10b981', '#ef4444'],
            legend: { position: 'bottom', labels: { colors: isDark ? '#9ca3af' : '#6b7280' } },
            dataLabels: { enabled: false },
            plotOptions: { pie: { donut: { size: '65%', labels: { show: true, total: { show: true, label: 'Delivered', color: isDark ? '#fff' : '#111', formatter: () => '{{ $deliveryStatuses["delivered"] ?? 0 }}' } } } } },
            stroke: { show: false },
            theme: { mode: isDark ? 'dark' : 'light' },
            tooltip: { theme: isDark ? 'dark' : 'light' },
        }).render();
    </script>

    {{-- Bottom Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- Recent Orders --}}
        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-bold text-gray-900">Recent Orders</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-amber-500 hover:text-amber-600 transition-colors">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="text-gray-500 text-sm border-b border-gray-200">
                            <th class="pb-3 font-medium">Order ID</th>
                            <th class="pb-3 font-medium">Customer</th>
                            <th class="pb-3 font-medium">Payment</th>
                            <th class="pb-3 font-medium">Status</th>
                            <th class="pb-3 font-medium text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm">
                        @forelse($recentOrders as $order)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="py-4 text-gray-900">#{{ $order->display_order_number }}</td>
                            <td class="py-4 text-gray-600">{{ $order->user->name ?? 'N/A' }}</td>
                            <td class="py-4 text-gray-600">{{ $order->payment_channel_label }}</td>
                            <td class="py-4">
                                <span class="px-2 py-1 rounded-full text-xs font-bold {{ $order->status === 'delivered' ? 'bg-green-500/20 text-green-600' : 'bg-amber-500/20 text-amber-600' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td class="py-4 text-right text-gray-900">₱{{ number_format($order->total_price ?? 0, 2) }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-gray-500">No recent orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Right Panel --}}
        <div class="bg-white border border-gray-200 rounded-2xl p-4 h-fit shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Order Status</h3>
            <div class="space-y-2 mb-6">
                @php
                    $statusLabels = ['pending'=>'Pending','confirmed'=>'Confirmed','processing'=>'Processing','shipped'=>'Shipped','delivered'=>'Delivered','cancelled'=>'Cancelled'];
                @endphp
                @foreach($statusLabels as $key => $label)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500">{{ $label }}</span>
                    <span class="text-gray-900 font-medium">{{ $ordersByStatus[$key] ?? 0 }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

@endsection

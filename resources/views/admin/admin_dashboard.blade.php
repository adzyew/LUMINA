@extends('admin.admin_layout')

@section('title', 'Admin Dashboard | Lumina')

@section('content')

    {{-- Top Bar --}}
    <div class="flex items-center justify-between mb-10 bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl px-6 py-3 shadow-sm dark:shadow-none">
        {{-- Search --}}
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
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            My Profile
                        </a>
                        <a href="#" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3" stroke-width="2"/></svg>
                            Settings
                        </a>
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

        // Close when clicking outside
        document.addEventListener('click', function (e) {
            const wrapper = document.getElementById('profileDropdownWrapper');
            const menu    = document.getElementById('profileDropdownMenu');
            const chevron = document.getElementById('profileChevron');

            if (wrapper && !wrapper.contains(e.target)) {
                menu.classList.add('opacity-0', 'scale-95', 'pointer-events-none');
                menu.classList.remove('opacity-100', 'scale-100');
                chevron.classList.remove('rotate-180');
            }
        });
        
    </script>

    {{-- Page Header --}}
    <header class="mb-8">
        <h1 class="text-3xl font-playfair font-bold text-gray-900 dark:text-white">Overview</h1>
        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Welcome back, Admin.</p>
    </header>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 relative overflow-hidden group shadow-sm dark:shadow-none">
            <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <svg class="w-24 h-24 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
            </div>
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Revenue</h3>
            <p class="text-3xl font-bold text-amber-600 dark:text-amber-300">₱{{ number_format($totalRevenue ?? 0, 2) }}</p>
            <div class="mt-4 flex items-center text-xs {{ ($revenueChange ?? 0) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                <span>{{ ($revenueChange ?? 0) >= 0 ? '+' : '' }}{{ $revenueChange ?? 0 }}% vs last month</span>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Orders</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Total Products</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalProducts }}</p>
        </div>

        <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/5 rounded-2xl p-6 shadow-sm dark:shadow-none">
            <h3 class="text-gray-500 dark:text-gray-400 text-sm font-medium mb-1">Active Users</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $totalUsers }}</p>
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
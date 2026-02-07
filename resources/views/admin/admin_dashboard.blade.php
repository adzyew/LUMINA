<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard | Lumina</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> </head>
<body class="bg-black text-white font-sans antialiased flex h-screen overflow-hidden">

    <aside class="w-64 bg-gray-900 border-r border-white/10 hidden md:flex flex-col">
        <div class="p-6 flex items-center gap-2 border-b border-white/10 h-20">
            <div class="w-8 h-8 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center font-bold text-black">L</div>
            <span class="font-playfair font-bold text-xl text-white">Lumina Admin</span>
        </div>

        <nav class="grow p-4 space-y-2">
            <a href="#" class="flex items-center gap-3 px-4 py-3 bg-amber-300 text-black rounded-lg font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Products
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                Orders
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 text-gray-400 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Users
            </a>
        </nav>

        <div class="p-4 border-t border-white/10">
            <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-500 hover:text-white transition-colors">
                &larr; Back to Website
            </a>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full mt-4 flex items-center justify-center gap-2 px-4 py-2 bg-red-500/10 text-red-400 border border-red-500/20 rounded-lg hover:bg-red-500/20 transition-colors">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <main class="grow overflow-y-auto bg-black p-6 sm:p-10">
        
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-playfair font-bold text-white">Overview</h1>
                <p class="text-gray-400 text-sm">Welcome back, Admin.</p>
            </div>
            <button class="md:hidden p-2 text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
            </button>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            
            <div class="bg-gray-900 border border-white/5 rounded-2xl p-6 relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity">
                    <svg class="w-24 h-24 text-amber-300" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"></path><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd"></path></svg>
                </div>
                <h3 class="text-gray-400 text-sm font-medium mb-1">Total Revenue</h3>
                <p class="text-3xl font-bold text-amber-300">${{ number_format($totalRevenue, 2) }}</p>
                <div class="mt-4 flex items-center text-xs text-green-400">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>+12.5% from last month</span>
                </div>
            </div>

            <div class="bg-gray-900 border border-white/5 rounded-2xl p-6">
                <h3 class="text-gray-400 text-sm font-medium mb-1">Total Orders</h3>
                <p class="text-3xl font-bold text-white">{{ $totalOrders }}</p>
            </div>

            <div class="bg-gray-900 border border-white/5 rounded-2xl p-6">
                <h3 class="text-gray-400 text-sm font-medium mb-1">Total Products</h3>
                <p class="text-3xl font-bold text-white">{{ $totalProducts }}</p>
            </div>

            <div class="bg-gray-900 border border-white/5 rounded-2xl p-6">
                <h3 class="text-gray-400 text-sm font-medium mb-1">Active Users</h3>
                <p class="text-3xl font-bold text-white">{{ $totalUsers }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-2 bg-gray-900 border border-white/5 rounded-2xl p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-white">Recent Orders</h3>
                    <a href="#" class="text-sm text-amber-300 hover:text-amber-200">View All</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-gray-500 text-sm border-b border-white/10">
                                <th class="pb-3 font-medium">Order ID</th>
                                <th class="pb-3 font-medium">Customer</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 font-medium text-right">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse($recentOrders as $order)
                                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors">
                                    <td class="py-4 text-white">#{{ $order->id }}</td>
                                    <td class="py-4 text-gray-300">{{ $order->user->name }}</td>
                                    <td class="py-4">
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-500/20 text-green-400">Completed</span>
                                    </td>
                                    <td class="py-4 text-right text-white">${{ number_format($order->total_price, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-500">No recent orders found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gray-900 border border-white/5 rounded-2xl p-6 h-fit">
                <h3 class="text-lg font-bold text-white mb-6">Quick Actions</h3>
                <div class="space-y-4">
                    <a href="{{ route('products.create') }}" class="block w-full py-3 bg-amber-300 text-black font-bold text-center rounded-lg hover:bg-amber-400 transition-transform transform hover:scale-[1.02]">
                        + Add New Product
                    </a>
                    <button class="block w-full py-3 bg-white/5 text-white font-semibold text-center rounded-lg hover:bg-white/10 border border-white/10">
                        Manage Users
                    </button>
                    <button class="block w-full py-3 bg-white/5 text-white font-semibold text-center rounded-lg hover:bg-white/10 border border-white/10">
                        View Analytics
                    </button>
                </div>
            </div>
        </div>

    </main>

</body>
</html>
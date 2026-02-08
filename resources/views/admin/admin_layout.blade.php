<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Lumina Admin')</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-black text-white font-sans antialiased flex h-screen overflow-hidden">

    <aside class="w-64 bg-gray-900 border-r border-white/10 hidden md:flex flex-col shrink-0">
        <div class="p-6 flex items-center gap-2 border-b border-white/10 h-20">
            <div class="w-8 h-8 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center font-bold text-black">L</div>
            <span class="font-playfair font-bold text-xl text-white">Lumina Admin</span>
        </div>

        <nav class="grow p-4 space-y-2">
            <a href="{{ route('admin.admin_dashboard') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'bg-amber-300 text-black font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>
            
            <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-4 py-3 {{ request()->routeIs('products.*') ? 'bg-amber-300 text-black font-bold' : 'text-gray-400 hover:text-white hover:bg-white/5' }} rounded-lg transition-colors">
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
            <a href="/" class="flex items-center gap-3 px-4 py-2 text-sm text-gray-500 hover:text-white transition-colors">
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
        @yield('content')
    </main>

</body>
</html>
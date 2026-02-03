<!doctype html>
<html class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Dashboard | Lumina</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-black text-white font-sans antialiased min-h-screen flex flex-col">

    <nav class="bg-gray-900 border-b border-white/10 sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4">
            <div class="flex items-center justify-between">
                
                <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                    <div class="flex space-x-1 transform group-hover:scale-110 transition-transform">
                        <div class="w-1.5 h-5 bg-amber-300 rounded-full -rotate-12"></div>
                        <div class="w-1.5 h-5 bg-amber-300 rounded-full"></div>
                        <div class="w-1.5 h-5 bg-amber-300 rounded-full rotate-12"></div>
                    </div>
                    <span class="font-playfair font-bold text-xl text-white">Lumina</span>
                </a>

                <div class="flex items-center gap-6">
                    <span class="hidden sm:block text-gray-400 text-sm">
                        Signed in as <span class="text-amber-300 font-bold">{{ Auth::user()->name }}</span>
                    </span>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-sm font-semibold text-red-400 hover:text-red-300 transition-colors border border-red-500/30 px-4 py-2 rounded-lg hover:bg-red-500/10">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <div class="grow container mx-auto px-4 sm:px-6 py-12">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-white/10 pb-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-playfair font-bold text-white mb-2">My Account</h1>
                <p class="text-gray-400">Manage your profile and view your order history.</p>
            </div>
            <a href="{{ route('products.index') }}" class="mt-4 md:mt-0 text-amber-300 hover:text-white transition-colors text-sm font-semibold flex items-center gap-1">
                Continue Shopping &rarr;
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-gray-900 rounded-2xl p-6 border border-white/5 shadow-xl sticky top-24">
                    <div class="w-20 h-20 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center text-black text-3xl font-bold mb-6 mx-auto">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    
                    <h2 class="text-center text-xl font-bold text-white mb-1">{{ Auth::user()->name }}</h2>
                    <p class="text-center text-gray-500 text-sm mb-6">{{ Auth::user()->email }}</p>

                    <div class="space-y-3">
                        <div class="flex justify-between p-3 bg-black/50 rounded-lg border border-white/5">
                            <span class="text-gray-400 text-sm">Member Since</span>
                            <span class="text-white text-sm">{{ Auth::user()->created_at->format('M Y') }}</span>
                        </div>
                        <div class="flex justify-between p-3 bg-black/50 rounded-lg border border-white/5">
                            <span class="text-gray-400 text-sm">Account Status</span>
                            <span class="text-green-400 text-sm font-bold flex items-center gap-1">
                                <span class="w-2 h-2 bg-green-400 rounded-full"></span> Active
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 space-y-8">
                
                <div class="bg-gray-900 rounded-2xl p-6 border border-white/5 shadow-xl">
                    <h3 class="text-xl font-playfair font-bold text-white mb-6 flex items-center gap-3">
                        <svg class="w-6 h-6 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Recent Orders
                    </h3>

                    <div class="text-center py-12 border border-dashed border-white/10 rounded-xl bg-black/20">
                        <div class="w-16 h-16 bg-gray-800 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-500">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        </div>
                        <p class="text-gray-400 font-medium mb-2">No orders found</p>
                        <p class="text-gray-600 text-sm mb-6">You haven't purchased any luxury items yet.</p>
                        <a href="{{ route('products.index') }}" class="px-6 py-2 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-all">
                            Browse Collection
                        </a>
                    </div>
                </div>

                <div class="bg-gray-900 rounded-2xl p-6 border border-white/5 shadow-xl opacity-60">
                    <h3 class="text-xl font-playfair font-bold text-white mb-2">Security</h3>
                    <p class="text-gray-400 text-sm mb-4">Update your password or account details.</p>
                    <button class="text-amber-300 text-sm hover:underline" disabled>Feature coming soon...</button>
                </div>

            </div>
        </div>
    </div>

    @include('partials.footer')

</body>
</html>
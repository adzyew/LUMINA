<!doctype html>
<html class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    <title>My Dashboard | Lumina</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white font-sans antialiased min-h-screen flex flex-col transition-colors pt-16">

    @include('partials.navbar')

    <div class="grow container mx-auto px-4 sm:px-6 py-12">
        
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-500/10 border border-green-500/20 rounded-xl text-green-400 text-sm">
                {{ session('success') }}
            </div>
        @endif
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-12 border-b border-white/10 pb-6">
            <div>
                <h1 class="text-3xl md:text-4xl font-playfair font-bold text-white mb-2">My Account</h1>
                <p class="text-gray-400">Manage your profile and view your order history.</p>
            </div>
            <a href="{{ route('products.index') }}" class="mt-4 md:mt-0 text-amber-300 hover:text-white transition-colors text-sm font-semibold flex items-center gap-1">
                Continue Shopping &rarr;
            </a>
        </div>

        <div class="bg-gradient-to-r from-amber-300 to-amber-500 rounded-2xl p-6 text-black shadow-lg mb-8">
            <h2 class="text-xl font-bold font-playfair mb-1">Lumina Rewards</h2>
            <p class="text-sm opacity-90 mb-4">You earn 1 point for every ₱100 spent!</p>
            <div class="text-4xl font-black">
                {{ auth()->user()->points_balance }} <span class="text-lg font-medium">pts</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <div class="lg:col-span-1">
                <div class="bg-gray-900 rounded-2xl p-6 border border-white/5 shadow-xl sticky top-24">
                    @if(Auth::user()->profile_photo_url)
                        <img src="{{ Auth::user()->profile_photo_url }}" alt="Profile" class="w-20 h-20 rounded-full object-cover mx-auto mb-6 border-2 border-amber-300/30">
                    @else
                    <div class="w-20 h-20 bg-linear-to-br from-amber-300 to-amber-600 rounded-full flex items-center justify-center text-black text-3xl font-bold mb-6 mx-auto">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    @endif
                    <h2 class="text-center text-xl font-bold text-white mb-1">{{ Auth::user()->name }}</h2>
                    <p class="text-center text-gray-500 text-sm mb-6">{{ Auth::user()->email }}</p>
                    <a href="{{ route('profile.edit') }}" class="block w-full py-2.5 text-center bg-amber-300 text-black font-bold rounded-xl hover:bg-amber-400 transition-colors text-sm mb-6">
                        Edit Profile
                    </a>

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
                
                <div id="orders" class="bg-gray-900 rounded-2xl p-6 border border-white/5 shadow-xl scroll-mt-24">
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

                <div id="settings" class="bg-gray-900 rounded-2xl p-6 border border-white/5 shadow-xl scroll-mt-24">
                    <h3 class="text-xl font-playfair font-bold text-white mb-2">Profile & Security</h3>
                    <p class="text-gray-400 text-sm mb-4">Update your name, profile photo, and phone.</p>
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center gap-2 text-amber-300 text-sm font-semibold hover:text-amber-200 transition-colors">
                        Edit profile & photo
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

            </div>
        </div>
    </div>

    @include('partials.footer')

</body>
</html>
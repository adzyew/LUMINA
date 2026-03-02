@extends('layouts.admin') {{-- Change this to match your admin/staff layout wrapper --}}

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-12">
        <div>
            <h1 class="text-3xl sm:text-4xl font-playfair font-bold text-white mb-2">
                Welcome back, {{ auth()->user()->name }}
            </h1>
            <p class="text-gray-400">Here is your daily overview for Lumina Jewelry.</p>
        </div>
        <div class="px-4 py-2 bg-amber-500/10 border border-amber-500/20 rounded-full text-amber-300 text-sm font-bold uppercase tracking-widest flex items-center gap-2 shadow-lg">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            Staff Portal
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        
        @can('inventory.view')
        <a href="{{ route('inventory.dashboard') }}" class="group bg-gray-900/60 rounded-[2rem] p-8 border border-white/5 hover:border-amber-300/30 transition-all duration-300 hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 bg-blue-500/10 rounded-full flex items-center justify-center mb-6 border border-blue-500/20 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Inventory</h3>
            <p class="text-gray-400 text-sm leading-relaxed mb-6">Monitor stock levels, manage products, and view the inventory movement logs.</p>
            <span class="text-amber-300 text-sm font-semibold flex items-center gap-1 group-hover:gap-2 transition-all">
                Manage Inventory <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </span>
        </a>
        @endcan

        @can('sales.view')
        <a href="{{ route('sales.dashboard') }}" class="group bg-gray-900/60 rounded-[2rem] p-8 border border-white/5 hover:border-amber-300/30 transition-all duration-300 hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 bg-green-500/10 rounded-full flex items-center justify-center mb-6 border border-green-500/20 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Sales & Orders</h3>
            <p class="text-gray-400 text-sm leading-relaxed mb-6">Process incoming customer orders, review transactions, and verify payments.</p>
            <span class="text-amber-300 text-sm font-semibold flex items-center gap-1 group-hover:gap-2 transition-all">
                View Orders <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </span>
        </a>
        @endcan

        @can('deliveries.manage')
        <a href="{{ route('delivery.dashboard') }}" class="group bg-gray-900/60 rounded-[2rem] p-8 border border-white/5 hover:border-amber-300/30 transition-all duration-300 hover:-translate-y-1 shadow-xl">
            <div class="w-14 h-14 bg-purple-500/10 rounded-full flex items-center justify-center mb-6 border border-purple-500/20 group-hover:scale-110 transition-transform">
                <svg class="w-7 h-7 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Deliveries</h3>
            <p class="text-gray-400 text-sm leading-relaxed mb-6">Update shipping statuses, print dispatch labels, and track outgoing packages.</p>
            <span class="text-amber-300 text-sm font-semibold flex items-center gap-1 group-hover:gap-2 transition-all">
                Manage Shipping <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </span>
        </a>
        @endcan

    </div>

    <div class="bg-black/40 rounded-[2rem] p-8 border border-white/5 shadow-xl">
        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
            <svg class="w-5 h-5 text-amber-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            System Notice
        </h3>
        <div class="flex items-start gap-4 p-4 bg-gray-900/50 rounded-xl border border-white/5">
            <div class="p-2 bg-amber-500/10 rounded-lg text-amber-400 mt-1">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-1">Remember to log out</h4>
                <p class="text-gray-400 text-sm leading-relaxed">Please ensure you securely log out of the staff portal at the end of your shift to maintain data security. For technical support, contact the system administrator.</p>
            </div>
        </div>
    </div>

</div>
@endsection
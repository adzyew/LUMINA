@extends('admin.admin_layout')

@section('title', 'Incoming Orders | Lumina Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <header class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-4xl font-playfair font-bold text-gray-900">Incoming Orders</h1>
            <p class="text-sm text-gray-600 mt-1">Order operations are available in the redesigned Orders page.</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl bg-amber-300 text-black font-bold hover:bg-amber-400">Open Orders</a>
    </header>

    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm">
        <p class="text-gray-600">This legacy page is kept for compatibility. Please use <span class="font-semibold text-gray-900">Admin > Orders</span> for full order management, status updates, and actions.</p>
    </div>
</div>
@endsection

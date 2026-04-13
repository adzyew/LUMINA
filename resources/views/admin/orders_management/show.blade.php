@extends('admin.admin_layout')

@section('title', 'Order Assignment | Lumina Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <header class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-4xl font-playfair font-bold text-gray-900">Order Assignment</h1>
            <p class="text-sm text-gray-600 mt-1">Order detail and assignment are now centralized in Orders and Deliveries.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-xl bg-amber-300 text-black font-bold hover:bg-amber-400">Open Orders</a>
            <a href="{{ route('admin.deliveries.index') }}" class="px-4 py-2 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-100">Open Deliveries</a>
        </div>
    </header>

    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm">
        <p class="text-gray-600">This page is preserved only for route compatibility. Use the redesigned modules for updates and assignment workflows.</p>
    </div>
</div>
@endsection

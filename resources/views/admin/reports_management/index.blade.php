@extends('admin.admin_layout')

@section('title', 'Reports | Lumina Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <header>
        <h1 class="text-4xl font-playfair font-bold text-gray-900">Reports</h1>
        <p class="text-sm text-gray-600 mt-1">Quick access to sales, inventory, and shipment reporting pages.</p>
    </header>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ url('/admin/sales') }}" class="rounded-3xl border border-amber-200 bg-gradient-to-br from-amber-50 to-white p-6 shadow-sm hover:shadow transition">
            <h2 class="text-2xl font-playfair font-bold text-gray-900">Sales Report</h2>
            <p class="text-sm text-gray-600 mt-2">Revenue trend and order totals.</p>
        </a>
        <a href="{{ url('/admin/products?stock=low_stock') }}" class="rounded-3xl border border-blue-200 bg-gradient-to-br from-blue-50 to-white p-6 shadow-sm hover:shadow transition">
            <h2 class="text-2xl font-playfair font-bold text-gray-900">Inventory Report</h2>
            <p class="text-sm text-gray-600 mt-2">Low-stock and product inventory view.</p>
        </a>
        <a href="{{ url('/admin/deliveries') }}" class="rounded-3xl border border-purple-200 bg-gradient-to-br from-purple-50 to-white p-6 shadow-sm hover:shadow transition">
            <h2 class="text-2xl font-playfair font-bold text-gray-900">Shipment Report</h2>
            <p class="text-sm text-gray-600 mt-2">Delivery and fulfillment status summary.</p>
        </a>
    </div>
</div>
@endsection


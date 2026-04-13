@extends('admin.admin_layout')

@section('title', 'Shipment Report | Lumina Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <header class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-4xl font-playfair font-bold text-gray-900">Shipment Report</h1>
            <p class="text-sm text-gray-600 mt-1">Use the Deliveries module for real-time shipping updates.</p>
        </div>
        <a href="{{ url('/admin/deliveries') }}" class="px-4 py-2 rounded-xl bg-amber-300 text-black font-bold hover:bg-amber-400">Open Deliveries</a>
    </header>

    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm">
        <p class="text-gray-600">Shipment tracking is handled in the redesigned <span class="font-semibold text-gray-900">Deliveries</span> page with live status and update modal support.</p>
    </div>
</div>
@endsection


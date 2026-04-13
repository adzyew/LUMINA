@extends('admin.admin_layout')

@section('title', 'Sales Report | Lumina Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <header class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-4xl font-playfair font-bold text-gray-900">Sales Report</h1>
            <p class="text-sm text-gray-600 mt-1">Use Analytics and Sales pages for complete revenue reporting.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ url('/admin/analytics') }}" class="px-4 py-2 rounded-xl bg-amber-300 text-black font-bold hover:bg-amber-400">Open Analytics</a>
            <a href="{{ url('/admin/sales') }}" class="px-4 py-2 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-100">Open Sales</a>
        </div>
    </header>

    <div class="rounded-3xl border border-gray-200 bg-white p-8 shadow-sm">
        <p class="text-gray-600">This report module is now consolidated into the redesigned <span class="font-semibold text-gray-900">Analytics</span> and <span class="font-semibold text-gray-900">Sales</span> pages so metrics stay synchronized.</p>
    </div>
</div>
@endsection


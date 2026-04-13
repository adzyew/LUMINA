@extends('admin.admin_layout')

@section('title', 'Inventory Report | Lumina Admin')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <header class="flex flex-wrap justify-between items-center gap-3">
        <div>
            <h1 class="text-4xl font-playfair font-bold text-gray-900">Inventory Report</h1>
            <p class="text-sm text-gray-600 mt-1">Products with stock visibility for restocking decisions.</p>
        </div>
        <a href="{{ url('/admin/analytics') }}" class="px-4 py-2 rounded-xl border border-gray-300 text-sm font-semibold text-gray-700 hover:bg-gray-100">Back to Reports</a>
    </header>

    <div class="bg-white rounded-3xl overflow-hidden border border-gray-200 shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[760px] text-left">
                <thead>
                    <tr class="bg-gray-50 text-gray-700 border-b border-gray-200 text-sm">
                        <th class="p-4 font-semibold">Product Name</th>
                        <th class="p-4 font-semibold">Category</th>
                        <th class="p-4 font-semibold">Stock Level</th>
                        <th class="p-4 font-semibold">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($lowStockItems as $item)
                    <tr class="hover:bg-amber-50/50 transition-colors">
                        <td class="p-4 text-gray-900 font-semibold">{{ $item->name }}</td>
                        <td class="p-4 text-gray-600">{{ is_object($item->category ?? null) ? ($item->category->name ?? '-') : ($item->category ?? '-') }}</td>
                        <td class="p-4 text-gray-900">{{ $item->stock_quantity }}</td>
                        <td class="p-4">
                            @if($item->stock_quantity < 5)
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Low Stock</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">In Stock</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="p-10 text-center text-gray-500">No inventory records found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection


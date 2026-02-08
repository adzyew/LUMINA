@extends('admin.admin_layout')

@section('title', 'Manage Products')

@section('content')

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-white">All Products</h1>
        <a href="{{ route('products.create') }}" class="px-4 py-2 bg-amber-300 text-black font-bold rounded hover:bg-amber-400">
            + Add New Product
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-500/20 text-green-400 p-4 rounded mb-6 border border-green-500/30">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-gray-900 rounded-lg overflow-hidden border border-white/10">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-white/5 text-gray-400 border-b border-white/10 text-sm">
                    <th class="p-4">Image</th>
                    <th class="p-4">Name</th>
                    <th class="p-4">Category</th>
                    <th class="p-4">Price</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/10">
                @foreach($products as $product)
                <tr class="hover:bg-white/5 transition">
                    <td class="p-4">
                        <img src="{{ $product->image_path }}" class="w-12 h-12 object-cover rounded border border-white/10">
                    </td>
                    <td class="p-4 font-medium">{{ $product->name }}</td>
                    <td class="p-4 text-gray-400">{{ $product->category }}</td>
                    <td class="p-4 text-amber-300 font-bold">${{ number_format($product->price, 2) }}</td>
                    <td class="p-4 text-right flex justify-end gap-3">
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-400 hover:text-red-300 text-sm font-semibold">Delete</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($products->isEmpty())
            <div class="p-8 text-center text-gray-500">
                No products found.
            </div>
        @endif
    </div>

@endsection
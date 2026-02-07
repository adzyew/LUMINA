<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report</title>
</head>
<body>
    <div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Inventory Report</h2>
    <table class="w-full text-left border-collapse">
        <thead>
            <tr>
                <th class="border-b p-2">Product Name</th>
                <th class="border-b p-2">Category</th>
                <th class="border-b p-2">Stock Level</th>
                <th class="border-b p-2">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lowStockItems as $item)
            <tr class="hover:bg-gray-100">
                <td class="p-2">{{ $item->name }}</td>
                <td class="p-2">{{ $item->category->name }}</td>
                <td class="p-2">{{ $item->stock_quantity }}</td>
                <td class="p-2">
                    @if($item->stock_quantity < 5)
                        <span class="text-red-500 font-bold">Low Stock</span>
                    @else
                        <span class="text-green-500">In Stock</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</body>
</html>
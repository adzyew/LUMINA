<!doctype html>
<html class="scroll-smooth">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    <title>My Orders | Lumina</title>
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white font-sans antialiased min-h-screen flex flex-col transition-colors pt-16">

    @include('partials.navbar')

    <div class="grow container mx-auto px-4 sm:px-6 py-12 max-w-5xl">
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl md:text-4xl font-playfair font-bold text-white mb-2">My Orders</h1>
                <p class="text-gray-400">Track all your orders and their latest status.</p>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm text-amber-300 hover:text-amber-200 font-semibold">Back to Dashboard</a>
        </div>

        <div class="space-y-4">
            @forelse($orders as $order)
                <div class="bg-gray-900/60 rounded-2xl p-5 border border-white/10">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-white font-bold text-lg">Order #{{ $order->id }}</p>
                            <p class="text-gray-400 text-sm">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider
                            {{ $order->status === 'pending' ? 'bg-yellow-500/10 text-yellow-500 border border-yellow-500/20' : '' }}
                            {{ $order->status === 'confirmed' ? 'bg-blue-500/10 text-blue-300 border border-blue-500/20' : '' }}
                            {{ $order->status === 'processing' ? 'bg-indigo-500/10 text-indigo-300 border border-indigo-500/20' : '' }}
                            {{ $order->status === 'shipped' ? 'bg-purple-500/10 text-purple-400 border border-purple-500/20' : '' }}
                            {{ $order->status === 'delivered' ? 'bg-green-500/10 text-green-400 border border-green-500/20' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-500/10 text-red-400 border border-red-500/20' : '' }}
                        ">{{ $order->status }}</span>
                    </div>

                    <div class="mt-4 pt-4 border-t border-white/10 grid grid-cols-1 sm:grid-cols-3 gap-3 text-sm">
                        <div>
                            <p class="text-gray-500">Items</p>
                            <p class="text-white font-medium">{{ $order->items->count() }} item(s)</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Total</p>
                            <p class="text-amber-300 font-bold">Php {{ number_format($order->total_price, 2) }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Tracking Number</p>
                            <p class="text-white font-medium">{{ $order->tracking_number ?? 'Pending assignment' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-gray-900/40 rounded-2xl border border-white/10">
                    <p class="text-white font-bold text-lg mb-2">No orders yet</p>
                    <p class="text-gray-400 mb-6">Start shopping to see your order history here.</p>
                    <a href="{{ route('products.index') }}" class="px-6 py-3 bg-amber-300 text-black font-bold rounded-full hover:bg-amber-400 transition-colors">
                        Browse Collection
                    </a>
                </div>
            @endforelse
        </div>

        @if($orders->hasPages())
            <div class="mt-8">{{ $orders->links() }}</div>
        @endif
    </div>

    @include('partials.footer')

</body>
</html>

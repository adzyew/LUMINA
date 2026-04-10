<!doctype html>
<html>
<head>
    @include('partials.favicon')
    <title>Order Confirmation | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
</head>
<body class="bg-stone-100 text-gray-900 font-sans antialiased flex flex-col min-h-screen">
@include('partials.navbar')

<div class="grow container mx-auto px-4 py-12 max-w-6xl">
    <h1 class="text-3xl font-playfair font-bold text-amber-600 mb-4 mt-8">Order Confirmation</h1>

    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="mb-6 bg-blue-100 text-blue-800 p-4 rounded-lg">{{ session('info') }}</div>
    @endif

    <div class="mb-6 rounded-2xl border border-amber-100 bg-white p-4 sm:p-5 shadow-sm">
        <ol class="grid grid-cols-1 sm:grid-cols-4 gap-4 sm:gap-3">
            @foreach([1 => 'Details', 2 => 'Review', 3 => 'Payment', 4 => 'Confirmation'] as $stepNumber => $label)
                <li class="flex items-center gap-3">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full border-2 border-amber-400 bg-amber-400 text-white">
                        @if($stepNumber < 4)
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/></svg>
                        @else
                            <span class="text-sm font-semibold">4</span>
                        @endif
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $label }}</p>
                    </div>
                </li>
            @endforeach
        </ol>
    </div>

    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm space-y-6">
        <div class="pb-5 border-b border-gray-100">
            <p class="text-xs uppercase tracking-wide text-gray-500">Order Number</p>
            <p class="text-3xl font-bold text-gray-900 mt-1">#{{ $order->display_order_number }}</p>
            <p class="text-sm text-gray-500 mt-2">A confirmation email was sent to {{ $order->contact_email }}.</p>
        </div>

        <div>
            <h2 class="text-lg font-bold text-gray-900 mb-3">Order Information</h2>

            <div class="mb-6">
                <h3 class="text-base font-bold text-gray-900 mb-3">Items Ordered</h3>
                <div class="space-y-3">
                    @foreach($order->items as $item)
                        <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 flex items-center gap-4">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-900">{{ $item->product?->name ?? ('Product #' . $item->product_id) }}</p>
                                <p class="text-xs text-gray-500">Qty: {{ (int) $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-semibold text-amber-600">P{{ number_format((float) $item->unit_price * (int) $item->quantity, 2) }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                    <span class="font-semibold text-gray-600 uppercase tracking-wide">Delivery Address:</span>
                    <span class="text-gray-900 text-right">{{ $order->formatted_shipping_address ?: 'Not provided' }}</span>
                </div>
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                    <span class="font-semibold text-gray-600 uppercase tracking-wide">Contact Number:</span>
                    <span class="text-gray-900 text-right">{{ $order->contact_phone ?: 'Not provided' }}</span>
                </div>
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                    <span class="font-semibold text-gray-600 uppercase tracking-wide">Email Address:</span>
                    <span class="text-gray-900 text-right">{{ $order->contact_email ?: 'Not provided' }}</span>
                </div>
                <div class="flex justify-between gap-4 border-b border-gray-100 pb-2">
                    <span class="font-semibold text-gray-600 uppercase tracking-wide">Tracking Number:</span>
                    <span class="text-gray-900 text-right font-mono">{{ $order->tracking_number ?? 'Pending assignment' }}</span>
                </div>
            </div>

            <div class="mt-6">
                <h3 class="text-base font-bold text-gray-900 mb-3">Order Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal:</span>
                        <span>P{{ number_format((float) $order->total_price + (float) $order->discount_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Payment:</span>
                        <span>{{ $order->payment_display }}</span>
                    </div>
                    <div class="border-t border-gray-100 pt-3 mt-3 flex justify-between font-bold text-base">
                        <span class="text-gray-900">Total Amount</span>
                        <span class="text-amber-600">P{{ number_format((float) $order->total_price, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 pt-2">
            <a href="{{ route('orders.invoice', $order) }}" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-800 font-semibold transition-colors">Download Invoice (PDF)</a>
            <a href="{{ route('orders.show', $order) }}" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-800 font-semibold transition-colors">View Full Order</a>
            <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-lg bg-amber-300 hover:bg-amber-400 text-black font-semibold transition-colors">Continue Shopping</a>
        </div>
    </div>
</div>

@include('partials.footer')
</body>
</html>

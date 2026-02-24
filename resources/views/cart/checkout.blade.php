<!doctype html>
<html>
<head>
    <title>Checkout | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white font-sans antialiased flex flex-col min-h-screen transition-colors">
@include('partials.navbar')

<div class="grow container mx-auto px-4 py-12 max-w-2xl">
    <h1 class="text-3xl font-playfair font-bold text-amber-600 dark:text-amber-300 mb-4 mt-8">Checkout</h1>

    @if(session('error'))
        <div class="mb-6 bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300 p-4 rounded-lg">{{ session('error') }}</div>
    @endif

    <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-white/10 rounded-2xl p-6 mb-6 shadow-sm dark:shadow-none">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Order Summary</h3>
        @php $total = 0; @endphp
        @foreach(session('cart') as $id => $item)
            @php $total += $item['price'] * $item['quantity']; @endphp
            <div class="flex justify-between py-2 border-b border-gray-100 dark:border-white/5">
                <span class="text-gray-600 dark:text-gray-300">{{ $item['name'] }} × {{ $item['quantity'] }}</span>
                <span class="text-amber-600 dark:text-amber-300">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
            </div>
        @endforeach
        <div class="flex justify-between mt-4 text-lg font-bold">
            <span class="text-gray-900 dark:text-white">Total</span>
            <span class="text-amber-600 dark:text-amber-300">₱{{ number_format($total, 2) }}</span>
        </div>
    </div>

    <form method="POST" action="{{ route('place.order') }}" class="space-y-4">
        @csrf
        <div class="space-y-4 p-4 rounded-xl bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-white/5">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Contact &amp; Shipping Details</h3>
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Contact Number <span class="text-amber-500">*</span></label>
                <input type="tel" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" required
                    class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 outline-none transition-colors"
                    placeholder="e.g. 09XX XXX XXXX">
                @error('contact_phone')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Street / Building / House No. <span class="text-amber-500">*</span></label>
                <input type="text" name="shipping_street" value="{{ old('shipping_street', auth()->user()->shipping_street ?? '') }}" required
                    class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 outline-none transition-colors"
                    placeholder="Street, building name, house number">
                @error('shipping_street')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">City / Municipality <span class="text-amber-500">*</span></label>
                    <input type="text" name="shipping_city" value="{{ old('shipping_city', auth()->user()->shipping_city ?? '') }}" required
                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 outline-none transition-colors"
                        placeholder="City or municipality">
                    @error('shipping_city')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Province / State <span class="text-amber-500">*</span></label>
                    <input type="text" name="shipping_province" value="{{ old('shipping_province', auth()->user()->shipping_province ?? '') }}" required
                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 outline-none transition-colors"
                        placeholder="Province or state">
                    @error('shipping_province')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Postal / ZIP Code</label>
                    <input type="text" name="shipping_postal_code" value="{{ old('shipping_postal_code', auth()->user()->shipping_postal_code ?? '') }}"
                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 outline-none transition-colors"
                        placeholder="e.g. 1000">
                    @error('shipping_postal_code')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Country <span class="text-amber-500">*</span></label>
                    <input type="text" name="shipping_country" value="{{ old('shipping_country', auth()->user()->shipping_country ?? 'Philippines') }}" required
                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 outline-none transition-colors"
                        placeholder="Country">
                    @error('shipping_country')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Additional Notes (optional)</label>
                <textarea name="notes" rows="2" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 outline-none transition-colors" placeholder="Delivery instructions, landmark, etc.">{{ old('notes') }}</textarea>
                @error('notes')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        <button type="submit" class="w-full py-4 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400">Place Order</button>
    </form>

    <a href="{{ route('cart.index') }}" class="block text-center mt-4 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">← Back to Cart</a>
</div>

@include('partials.footer')
</body>
</html>

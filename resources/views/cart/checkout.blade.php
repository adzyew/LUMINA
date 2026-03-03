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
                        <select id="city" name="shipping_city" required
                            class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 outline-none transition-colors">
                            <option value="">Loading cities...</option>
                        </select>
                        @error('shipping_city')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>          
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Barangay <span class="text-amber-500">*</span></label>
                    <select id="barangay" name="shipping_barangay" required
                        class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 outline-none transition-colors">
                        <option value="">Select Barangay</option>
                    </select>
                    @error('shipping_barangay')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
           <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                        <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Postal / ZIP Code</label>
                    <input type="text" id="zip" name="shipping_postal_code" value="{{ old('shipping_postal_code', auth()->user()->shipping_postal_code ?? '') }}" readonly
                        class="w-full bg-gray-100 dark:bg-gray-700/50 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-500 dark:text-gray-400 cursor-not-allowed outline-none"
                        placeholder="Auto-filled">
                </div>  
                <div>
                    <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Country</label>
                    <input type="text" name="shipping_country" value="Philippines" readonly
                        class="w-full bg-gray-100 dark:bg-gray-700/50 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-500 dark:text-gray-400 cursor-not-allowed outline-none">
                </div>
            </div>

            <input type="hidden" name="shipping_region" value="National Capital Region (NCR)">
            <input type="hidden" name="shipping_province" value="Metro Manila">

            <div>
                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Additional Notes (optional)</label>
                <textarea name="notes" rows="2" class="w-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-white/10 rounded-lg p-3 text-gray-900 dark:text-white focus:border-amber-400 outline-none transition-colors" placeholder="Delivery instructions, landmark, etc.">{{ old('notes') }}</textarea>
                @error('notes')<p class="text-red-500 dark:text-red-400 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
        </div>
        @if(auth()->user()->points_balance > 0)
        <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-500/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h4 class="font-bold text-amber-800 dark:text-amber-300 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                    Lumina Rewards
                </h4>
                <p class="text-sm text-amber-700 dark:text-amber-400 mt-1">
                    You have <strong>{{ auth()->user()->points_balance }} points</strong> (worth ₱{{ number_format(auth()->user()->points_balance, 2) }}).
                </p>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <input type="checkbox" name="use_points" value="1" id="use_points" class="w-5 h-5 text-amber-600 rounded focus:ring-amber-500 bg-white border-gray-300 dark:border-gray-600 dark:bg-gray-800">
                <label for="use_points" class="text-sm font-bold text-gray-900 dark:text-white cursor-pointer">Apply Discount</label>
            </div>
        </div>
        @endif
        <button type="submit" class="w-full py-4 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400">Place Order</button>
    </form>

    <a href="{{ route('cart.index') }}" class="block text-center mt-4 text-sm text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors">← Back to Cart</a>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const citySelect = document.getElementById('city');
    const barangaySelect = document.getElementById('barangay');
    const zipInput = document.getElementById('zip');

    const zipCodes = {
    "City of Manila": "1000",
    "Quezon City": "1100",
    "City of Caloocan": "1400",
    "City of Makati": "1200",
    "City of Taguig": "1630",
    "City of Pasig": "1600",
    "City of Parañaque": "1700",
    "City of Las Piñas": "1740",
    "City of Mandaluyong": "1550",
    "City of Marikina": "1800",
    "City of Navotas": "1485",
    "City of Malabon": "1470",
    "City of Valenzuela": "1440",
    "City of San Juan": "1500",
    "City of Muntinlupa": "1770",
    "Pasay City": "1300",
    "Pateros": "1620"
};

    // Load NCR Cities
    fetch('https://psgc.gitlab.io/api/regions/130000000/cities-municipalities/')
        .then(response => response.json())
        .then(data => {
            citySelect.innerHTML = '<option value="">Select City</option>';
            data.forEach(city => {
                const option = document.createElement('option');
                option.value = city.name;
                option.textContent = city.name;
                option.dataset.code = city.code;
                
                // Pre-select if user has a saved city
                if(option.value === "{{ auth()->user()->shipping_city }}") {
                    option.selected = true;
                }
                
                citySelect.appendChild(option);
            });
            
            // Trigger change to load barangays if a city is pre-selected
            if (citySelect.value) citySelect.dispatchEvent(new Event('change'));
        });

    citySelect.addEventListener('change', function () {
        const selectedOption = this.selectedOptions[0];
        if (!selectedOption || !selectedOption.dataset.code) return;

        const cityCode = selectedOption.dataset.code;
        const cityName = selectedOption.value;

        zipInput.value = zipCodes[cityName] ?? '';
        barangaySelect.innerHTML = '<option value="">Loading barangays...</option>';

        fetch(`https://psgc.gitlab.io/api/cities-municipalities/${cityCode}/barangays/`)
            .then(response => response.json())
            .then(data => {
                barangaySelect.innerHTML = '<option value="">Select Barangay</option>';
                data.forEach(brgy => {
                    const option = document.createElement('option');
                    option.value = brgy.name;
                    option.textContent = brgy.name;
                    
                    if(option.value === "{{ auth()->user()->shipping_barangay }}") {
                        option.selected = true;
                    }
                    
                    barangaySelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                barangaySelect.innerHTML = '<option value="">Failed to load</option>';
            });
    });
});
</script>

@include('partials.footer')
</body>
</html>

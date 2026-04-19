<!doctype html>
<html>
<head>
    @include('partials.favicon')
    <title>Checkout | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-stone-100 text-gray-900 font-sans antialiased flex flex-col min-h-screen">
@include('partials.navbar')

<div class="grow container mx-auto px-4 py-12 max-w-6xl">
    <h1 class="text-3xl font-playfair font-bold text-amber-600 mb-4 mt-8">Checkout</h1>

    @if(session('error'))
        <div class="mb-6 bg-red-100 text-red-800 p-4 rounded-lg">{{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="mb-6 bg-green-100 text-green-800 p-4 rounded-lg">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="mb-6 bg-blue-100 text-blue-800 p-4 rounded-lg">{{ session('info') }}</div>
    @endif

    @php
        $errorKeys = $errors->keys();
        $initialCheckoutStep = collect($errorKeys)->contains(fn ($key) => str_starts_with((string) $key, 'payment_') || str_starts_with((string) $key, 'promo_')) ? 3 : 1;
    @endphp

    <div id="checkout-stepper" class="mb-6 rounded-2xl border border-amber-100 bg-white p-4 sm:p-5 shadow-sm">
        <ol class="grid grid-cols-1 sm:grid-cols-4 gap-4 sm:gap-3">
            <li class="checkout-step" data-step="1">
                <div class="flex items-center gap-3">
                    <span class="step-dot flex h-9 w-9 items-center justify-center rounded-full border-2 border-gray-300 bg-white text-gray-400">
                        <span class="text-sm font-semibold">1</span>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Details</p>
                        <p class="text-xs text-gray-500">Contact & shipping</p>
                    </div>
                </div>
            </li>
            <li class="checkout-step" data-step="2">
                <div class="flex items-center gap-3">
                    <span class="step-dot flex h-9 w-9 items-center justify-center rounded-full border-2 border-gray-300 bg-white text-gray-400">
                        <span class="text-sm font-semibold">2</span>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Review</p>
                        <p class="text-xs text-gray-500">Check your details</p>
                    </div>
                </div>
            </li>
            <li class="checkout-step" data-step="3">
                <div class="flex items-center gap-3">
                    <span class="step-dot flex h-9 w-9 items-center justify-center rounded-full border-2 border-gray-300 bg-white text-gray-400">
                        <span class="text-sm font-semibold">3</span>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Payment</p>
                        <p class="text-xs text-gray-500">Choose and place order</p>
                    </div>
                </div>
            </li>
            <li class="checkout-step" data-step="4">
                <div class="flex items-center gap-3 opacity-80">
                    <span class="step-dot flex h-9 w-9 items-center justify-center rounded-full border-2 border-gray-300 bg-white text-gray-400">
                        <span class="text-sm font-semibold">4</span>
                    </span>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">Confirmation</p>
                        <p class="text-xs text-gray-500">Order details summary</p>
                    </div>
                </div>
            </li>
        </ol>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[minmax(0,1.65fr)_minmax(320px,1fr)] gap-6 items-start">
        <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm lg:order-2 lg:sticky lg:top-28">
            @php $total = 0; @endphp
            @foreach(session('cart') as $id => $item)
                @php $total += $item['price'] * $item['quantity']; @endphp
                <div class="py-3 border-b border-gray-100">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0 flex items-start gap-3">
                            <div class="w-14 h-14 rounded-md overflow-hidden border border-gray-200 bg-gray-100 shrink-0">
                                @if(!empty($item['image']))
                                    <img src="{{ asset($item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-8h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                    </div>
                                @endif
                            </div>
                            <span class="text-black text-md block truncate mt-4">{{ $item['name'] }} x {{ $item['quantity'] }}</span>
                        </div>
                        <span class="text-amber-600 shrink-0 mt-4">P{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                    </div>
                </div>
            @endforeach
            @php
                $promoHas = (bool) data_get($promoView ?? [], 'hasPromo', false);
                $promoCode = data_get($promoView ?? [], 'code');
                $promoPercent = (float) data_get($promoView ?? [], 'discount_percent', 0);
                $promoAmount = (float) data_get($promoView ?? [], 'discount_amount', 0);
                $grandTotal = max(0, $total - $promoAmount);
            @endphp
            <div class="mt-4 space-y-2 border-t border-gray-100 pt-4">
                <div class="flex justify-between text-base text-gray-700">
                    <span>Subtotal</span>
                    <span>P{{ number_format($total, 2) }}</span>
                </div>
                <div class="flex justify-between text-base text-gray-700">
                    <span>Shipping</span>
                    <span class="text-amber-600 font-medium">Free</span>
                </div>
                @if($promoHas)
                    <div class="flex justify-between text-base text-gray-700">
                        <span>Promo <span class="font-medium text-amber-600">({{ $promoCode }} - {{ number_format($promoPercent, 0) }}%)</span></span>
                        <span class="text-green-600">-P{{ number_format($promoAmount, 2) }}</span>
                    </div>
                @endif
            </div>
            <div class="flex items-end justify-between mt-4">
                <span class="text-2xl font-bold text-gray-900">Total</span>
                <div class="text-right">
                    <span class="text-base text-gray-500 mr-1">PHP</span>
                    <span class="text-2xl font-bold text-amber-600">P{{ number_format($grandTotal, 2) }}</span>
                </div>
            </div>
        </div>

        <form id="checkout-form" method="POST" action="{{ route('place.order', [], false) }}" class="space-y-4 lg:order-1" novalidate>
            @csrf

            <input type="hidden" name="shipping_region" value="National Capital Region (NCR)">
            <input type="hidden" name="shipping_province" value="Metro Manila">
            <input type="hidden" name="shipping_country" value="Philippines">

            <div class="space-y-4 p-4 rounded-xl bg-gray-50 border border-gray-200" data-step-panel="1">
                <h3 class="text-xl font-bold text-gray-900">Contact Information</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Contact Number <span class="text-amber-500">*</span></label>
                    <input type="tel" name="contact_phone" value="{{ old('contact_phone', auth()->user()->phone ?? '') }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-900 focus:border-amber-400 outline-none transition-colors"
                        placeholder="e.g. 09XX XXX XXXX">
                    @error('contact_phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Email Address <span class="text-amber-500">*</span></label>
                    <input type="email" name="contact_email" value="{{ old('contact_email', auth()->user()->email ?? '') }}" required
                        class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-900 focus:border-amber-400 outline-none transition-colors"
                        placeholder="e.g. john.doe@example.com">
                    @error('contact_email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <h3 class="text-xl font-bold text-gray-900 pt-2">Delivery Information</h3>
                @php
                    $savedStreet = auth()->user()->shipping_street ?? '';
                    $savedCity = auth()->user()->shipping_city ?? '';
                    $savedBarangay = auth()->user()->shipping_barangay ?? '';
                    $savedPostalCode = auth()->user()->shipping_postal_code ?? '';
                    $savedAddressPayload = [
                        'street' => $savedStreet,
                        'city' => $savedCity,
                        'barangay' => $savedBarangay,
                        'postal_code' => $savedPostalCode,
                    ];
                    $addressMode = old('address_mode', $savedStreet ? 'saved' : 'another');
                    $shippingStreetValue = $addressMode === 'another' ? old('shipping_street', '') : old('shipping_street', $savedStreet);
                    $shippingCityValue = $addressMode === 'another' ? old('shipping_city', '') : old('shipping_city', $savedCity);
                    $shippingBarangayValue = $addressMode === 'another' ? old('shipping_barangay', '') : old('shipping_barangay', $savedBarangay);
                    $shippingPostalValue = $addressMode === 'another' ? old('shipping_postal_code', '') : old('shipping_postal_code', $savedPostalCode);
                @endphp

                <div class="rounded-lg border border-gray-200 bg-white p-4 space-y-3">
                    <p class="text-sm font-semibold text-gray-700">Choose delivery address</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <label class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer">
                            <input type="radio" id="address_mode_saved" name="address_mode" value="saved" class="mt-1 h-4 w-4 text-amber-500 border-gray-300 focus:ring-amber-400" {{ $addressMode === 'saved' ? 'checked' : '' }}>
                            <span>
                                <span class="block text-sm font-semibold text-gray-900">Use saved address</span>
                                <span class="block text-xs text-gray-500">Use your default address from settings.</span>
                            </span>
                        </label>
                        <label class="flex items-start gap-3 p-3 rounded-lg border border-gray-200 cursor-pointer">
                            <input type="radio" id="address_mode_another" name="address_mode" value="another" class="mt-1 h-4 w-4 text-amber-500 border-gray-300 focus:ring-amber-400" {{ $addressMode === 'another' ? 'checked' : '' }}>
                            <span>
                                <span class="block text-sm font-semibold text-gray-900">Use another address</span>
                                <span class="block text-xs text-gray-500">Fill up a different delivery address for this order.</span>
                            </span>
                        </label>
                    </div>
                </div>

                <div id="saved-address-summary" class="rounded-lg border border-amber-200 bg-amber-50 p-4 space-y-1 text-sm text-gray-700">
                    <p><span class="font-semibold">Street:</span> {{ $savedStreet ?: 'No saved street address yet.' }}</p>
                    <p><span class="font-semibold">City/Barangay:</span> {{ trim(($savedCity ?: '-') . ', ' . ($savedBarangay ?: '-')) }}</p>
                    <p><span class="font-semibold">ZIP:</span> {{ $savedPostalCode ?: '-' }}</p>
                </div>

                <input type="hidden" id="shipping_street_hidden" name="shipping_street" value="{{ $shippingStreetValue }}">
                <input type="hidden" id="shipping_city_hidden" name="shipping_city" value="{{ $shippingCityValue }}">
                <input type="hidden" id="shipping_barangay_hidden" name="shipping_barangay" value="{{ $shippingBarangayValue }}">
                <input type="hidden" id="shipping_postal_code_hidden" name="shipping_postal_code" value="{{ $shippingPostalValue }}">

                <div id="another-address-fields" class="space-y-4 {{ $addressMode === 'another' ? '' : 'hidden' }}">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-2">Street / Building / House No. <span class="text-amber-500">*</span></label>
                        <input type="text" id="another_shipping_street" value="{{ $addressMode === 'another' ? old('shipping_street', '') : '' }}"
                            class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-900 focus:border-amber-400 outline-none transition-colors"
                            placeholder="Street, building name, house number">
                        @error('shipping_street')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">City / Municipality <span class="text-amber-500">*</span></label>
                            <select id="another_city"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-900 focus:border-amber-400 outline-none transition-colors">
                                <option value="">Loading cities...</option>
                            </select>
                            @error('shipping_city')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Barangay <span class="text-amber-500">*</span></label>
                            <select id="another_barangay"
                                class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-900 focus:border-amber-400 outline-none transition-colors">
                                <option value="">Select Barangay</option>
                            </select>
                            @error('shipping_barangay')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Postal / ZIP Code</label>
                            <input type="text" id="another_zip" value="{{ $addressMode === 'another' ? old('shipping_postal_code', '') : '' }}" readonly
                                class="w-full bg-gray-100 border border-gray-200 rounded-lg p-3 text-gray-500 cursor-not-allowed outline-none"
                                placeholder="Auto-filled">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-2">Country</label>
                            <input type="text" value="Philippines" readonly
                                class="w-full bg-gray-100 border border-gray-200 rounded-lg p-3 text-gray-500 cursor-not-allowed outline-none">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-2">Additional Notes (optional)</label>
                    <textarea name="notes" rows="2" class="w-full bg-gray-50 border border-gray-200 rounded-lg p-3 text-gray-900 focus:border-amber-400 outline-none transition-colors" placeholder="Delivery instructions, landmark, etc.">{{ old('notes') }}</textarea>
                    @error('notes')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" id="next-to-review" class="px-5 py-2.5 rounded-lg bg-amber-300 hover:bg-amber-400 text-black font-semibold transition-colors">Continue to Review</button>
                </div>
            </div>

            <div class="space-y-4 p-4 rounded-xl bg-gray-50 border border-gray-200 hidden" data-step-panel="2">
                <h3 class="text-xl font-bold text-gray-900">Review Your Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-lg border border-gray-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Contact</p>
                        <p class="text-sm text-gray-900"><strong>Phone:</strong> <span id="review-contact-phone">-</span></p>
                        <p class="text-sm text-gray-900"><strong>Email:</strong> <span id="review-contact-email">-</span></p>
                    </div>
                    <div class="rounded-lg border border-gray-200 bg-white p-4">
                        <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Delivery</p>
                        <p class="text-sm text-gray-900"><strong>Street:</strong> <span id="review-shipping-street">-</span></p>
                        <p class="text-sm text-gray-900"><strong>City/Barangay:</strong> <span id="review-shipping-city-brgy">-</span></p>
                        <p class="text-sm text-gray-900"><strong>ZIP:</strong> <span id="review-shipping-zip">-</span></p>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Additional Notes</p>
                    <p class="text-sm text-gray-900" id="review-notes">No additional notes.</p>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4">
                    <p class="text-xs uppercase tracking-wide text-gray-500 mb-2">Order Total</p>
                    <p class="text-lg font-bold text-amber-600">P{{ number_format($grandTotal, 2) }}</p>
                    @if($promoHas)
                        <p class="text-sm text-green-600 mt-1">Promo {{ $promoCode }} applied ({{ number_format($promoPercent, 0) }}% off)</p>
                    @endif
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" id="back-to-details" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-800 font-semibold transition-colors">Back</button>
                    <button type="button" id="next-to-payment" class="px-5 py-2.5 rounded-lg bg-amber-300 hover:bg-amber-400 text-black font-semibold transition-colors">Continue to Payment</button>
                </div>
            </div>

            <div class="space-y-3 p-4 rounded-xl bg-gray-50 border border-gray-200 hidden" data-step-panel="3">
                <h3 class="text-lg font-semibold text-gray-900">Payment Method</h3>

                

                <label for="payment_cod" class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg bg-white cursor-pointer has-checked:border-amber-400 has-checked:ring-2 has-checked:ring-amber-100 transition">
                    <input id="payment_cod" type="radio" name="payment_method" value="cod" {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }} class="mt-1 h-4 w-4 text-amber-500 border-gray-300 focus:ring-amber-400">
                    <span>
                        <span class="block text-sm font-semibold text-gray-900">Cash on Delivery (COD)</span>
                        <span class="block text-xs text-gray-500">Pay with cash when your order arrives.</span>
                    </span>
                </label>

                <label for="payment_paymongo" class="flex items-start gap-3 p-4 border border-gray-200 rounded-lg bg-white cursor-pointer has-checked:border-amber-400 has-checked:ring-2 has-checked:ring-amber-100 transition">
                    <input id="payment_paymongo" type="radio" name="payment_method" value="paymongo" {{ old('payment_method') === 'paymongo' ? 'checked' : '' }} class="mt-1 h-4 w-4 text-amber-500 border-gray-300 focus:ring-amber-400">
                    <span>
                        <span class="block text-sm font-semibold text-gray-900">Secure Payments via PayMongo</span>
                        <span class="block text-xs text-gray-500">Card, e-wallet, and online payment options via PayMongo checkout.</span>
                    </span>
                </label>
                @error('payment_method')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror

                <div class="rounded-lg border border-amber-200 bg-amber-50 p-4">
                    <p class="text-xs uppercase tracking-wide text-amber-700 mb-2">Promo Code</p>
                    @if($promoHas)
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                            <p class="text-sm text-gray-900">
                                <span class="font-semibold text-amber-700">{{ $promoCode }}</span> applied
                                ({{ number_format($promoPercent, 0) }}% off, -P{{ number_format($promoAmount, 2) }})
                            </p>
                            <button
                                type="submit"
                                formaction="{{ route('checkout.promo.remove') }}"
                                formmethod="POST"
                                formnovalidate
                                class="px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-sm font-semibold text-gray-800 transition-colors"
                            >
                                Remove
                            </button>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row gap-2">
                            <input
                                type="text"
                                name="promo_code"
                                maxlength="50"
                                class="flex-1 bg-white border border-amber-200 rounded-lg p-2.5 text-gray-900 focus:border-amber-400 outline-none"
                                placeholder="Enter promo code"
                            >
                            <button
                                type="submit"
                                formaction="{{ route('checkout.promo.apply') }}"
                                formmethod="POST"
                                formnovalidate
                                class="px-4 py-2.5 rounded-lg bg-amber-400 hover:bg-amber-500 text-black font-semibold transition-colors"
                            >
                                Apply
                            </button>
                        </div>
                        @error('promo_code')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                        <p class="text-xs text-gray-500 mt-2">Codes are single-use per user and expire quickly after claim.</p>
                    @endif
                </div>

                @if(auth()->user()->points_balance > 0)
                <div class="p-4 rounded-xl bg-amber-50 border border-amber-200 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="font-bold text-amber-800 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"></path></svg>
                            Lumina Rewards
                        </h4>
                        <p class="text-sm text-amber-700 mt-1">You have <strong>{{ auth()->user()->points_balance }} points</strong> (worth P{{ number_format(auth()->user()->points_balance, 2) }}).</p>
                    </div>
                    <div class="flex items-center gap-2 shrink-0">
                        <input type="checkbox" name="use_points" value="1" id="use_points" class="w-5 h-5 text-amber-600 rounded focus:ring-amber-500 bg-white border-gray-300">
                        <label for="use_points" class="text-sm font-bold text-gray-900 cursor-pointer">Apply Discount</label>
                    </div>
                </div>
                @endif

                <div class="flex items-center justify-end gap-3 pt-2">
                    <button type="button" id="back-to-review" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white hover:bg-gray-100 text-gray-800 font-semibold transition-colors">Back</button>
                    <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-300 hover:bg-amber-400 text-black font-semibold transition-colors">Place Order</button>
                </div>
            </div>
        </form>
    </div>

    <a href="{{ route('cart.index') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-gray-900 transition-colors">&larr; Back to Cart</a>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stepperEl = document.getElementById('checkout-stepper');
    const checkoutForm = document.getElementById('checkout-form');
    const stepItems = stepperEl ? Array.from(stepperEl.querySelectorAll('.checkout-step')) : [];
    const stepPanels = checkoutForm ? Array.from(checkoutForm.querySelectorAll('[data-step-panel]')) : [];
    const initialStep = Number(@json($initialCheckoutStep));

    const nextToReviewBtn = document.getElementById('next-to-review');
    const backToDetailsBtn = document.getElementById('back-to-details');
    const nextToPaymentBtn = document.getElementById('next-to-payment');
    const backToReviewBtn = document.getElementById('back-to-review');
    const addressModeSaved = document.getElementById('address_mode_saved');
    const addressModeAnother = document.getElementById('address_mode_another');
    const savedAddressSummary = document.getElementById('saved-address-summary');
    const anotherAddressFields = document.getElementById('another-address-fields');
    const anotherStreetInput = document.getElementById('another_shipping_street');
    const anotherCitySelect = document.getElementById('another_city');
    const anotherBarangaySelect = document.getElementById('another_barangay');
    const anotherZipInput = document.getElementById('another_zip');
    const shippingStreetHidden = document.getElementById('shipping_street_hidden');
    const shippingCityHidden = document.getElementById('shipping_city_hidden');
    const shippingBarangayHidden = document.getElementById('shipping_barangay_hidden');
    const shippingPostalHidden = document.getElementById('shipping_postal_code_hidden');
    const savedAddress = @json($savedAddressPayload);
    const previousAnotherCity = @json($addressMode === 'another' ? old('shipping_city', '') : '');
    const previousAnotherBarangay = @json($addressMode === 'another' ? old('shipping_barangay', '') : '');
    const previousAnotherPostalCode = @json($addressMode === 'another' ? old('shipping_postal_code', '') : '');

    let activeStep = initialStep >= 1 && initialStep <= 3 ? initialStep : 1;

    function setActiveStep(step) {
        if (!stepItems.length) return;

        stepItems.forEach((item) => {
            const currentStep = Number(item.dataset.step || 0);
            const dot = item.querySelector('.step-dot');
            if (!dot) return;

            if (currentStep <= step) {
                dot.classList.remove('border-gray-300', 'bg-white', 'text-gray-400');
                dot.classList.add('border-amber-400', 'bg-amber-400', 'text-white');
                if (currentStep < step) {
                    dot.innerHTML = '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M5 13l4 4L19 7"/></svg>';
                } else {
                    dot.innerHTML = '<span class="text-sm font-semibold">' + currentStep + '</span>';
                }
            } else {
                dot.classList.remove('border-amber-400', 'bg-amber-400', 'text-white');
                dot.classList.add('border-gray-300', 'bg-white', 'text-gray-400');
                dot.innerHTML = '<span class="text-sm font-semibold">' + currentStep + '</span>';
            }
        });
    }

    function renderStep(step) {
        activeStep = step;
        setActiveStep(step);
        stepPanels.forEach((panel) => {
            const panelStep = Number(panel.getAttribute('data-step-panel') || 0);
            panel.classList.toggle('hidden', panelStep !== step);
        });
        if (step === 2) {
            updateReviewDetails();
        }
    }

    function usingAnotherAddress() {
        return Boolean(addressModeAnother?.checked);
    }

    function syncShippingFields() {
        if (!shippingStreetHidden || !shippingCityHidden || !shippingBarangayHidden || !shippingPostalHidden) return;

        if (usingAnotherAddress()) {
            shippingStreetHidden.value = (anotherStreetInput?.value || '').trim();
            shippingCityHidden.value = anotherCitySelect?.value || '';
            shippingBarangayHidden.value = anotherBarangaySelect?.value || '';
            shippingPostalHidden.value = anotherZipInput?.value || '';
            return;
        }

        shippingStreetHidden.value = savedAddress.street || '';
        shippingCityHidden.value = savedAddress.city || '';
        shippingBarangayHidden.value = savedAddress.barangay || '';
        shippingPostalHidden.value = savedAddress.postal_code || '';
    }

    function setAddressModeUI() {
        const anotherMode = usingAnotherAddress();

        anotherAddressFields?.classList.toggle('hidden', !anotherMode);
        savedAddressSummary?.classList.toggle('hidden', anotherMode);

        if (anotherStreetInput) anotherStreetInput.required = anotherMode;
        if (anotherCitySelect) anotherCitySelect.required = anotherMode;
        if (anotherBarangaySelect) anotherBarangaySelect.required = anotherMode;

        syncShippingFields();
    }

    function validateStepOne() {
        if (!checkoutForm) return false;
        syncShippingFields();
        const requiredFields = checkoutForm.querySelectorAll('[data-step-panel="1"] [required]');
        for (const field of requiredFields) {
            if (typeof field.reportValidity === 'function' && !field.reportValidity()) {
                return false;
            }
        }
        return true;
    }

    function readValue(name) {
        const field = checkoutForm ? checkoutForm.querySelector('[name="' + name + '"]') : null;
        return field ? (field.value || '').trim() : '';
    }

    function updateReviewDetails() {
        const city = readValue('shipping_city');
        const barangay = readValue('shipping_barangay');
        const notes = readValue('notes');

        const mappings = {
            'review-contact-phone': readValue('contact_phone') || '-',
            'review-contact-email': readValue('contact_email') || '-',
            'review-shipping-street': readValue('shipping_street') || '-',
            'review-shipping-city-brgy': [city, barangay].filter(Boolean).join(', ') || '-',
            'review-shipping-zip': readValue('shipping_postal_code') || '-',
            'review-notes': notes || 'No additional notes.'
        };

        Object.entries(mappings).forEach(([id, value]) => {
            const el = document.getElementById(id);
            if (el) el.textContent = value;
        });
    }

    nextToReviewBtn?.addEventListener('click', function () {
        if (!validateStepOne()) return;
        renderStep(2);
    });

    backToDetailsBtn?.addEventListener('click', function () {
        renderStep(1);
    });

    nextToPaymentBtn?.addEventListener('click', function () {
        renderStep(3);
    });

    backToReviewBtn?.addEventListener('click', function () {
        renderStep(2);
    });

    checkoutForm?.addEventListener('submit', function () {
        syncShippingFields();
        if (!validateStepOne()) {
            renderStep(1);
            return;
        }
        setActiveStep(3);
    });

    renderStep(activeStep);

    const citySelect = anotherCitySelect;
    const barangaySelect = anotherBarangaySelect;
    const zipInput = anotherZipInput;

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

    if (citySelect && barangaySelect && zipInput) {
        fetch('https://psgc.gitlab.io/api/regions/130000000/cities-municipalities/')
            .then(response => response.json())
            .then(data => {
                citySelect.innerHTML = '<option value="">Select City</option>';
                data.forEach(city => {
                    const option = document.createElement('option');
                    option.value = city.name;
                    option.textContent = city.name;
                    option.dataset.code = city.code;
                    if (option.value === previousAnotherCity) option.selected = true;
                    citySelect.appendChild(option);
                });

                if (citySelect.value) {
                    zipInput.value = previousAnotherPostalCode || '';
                    citySelect.dispatchEvent(new Event('change'));
                }
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
                        if (option.value === previousAnotherBarangay) option.selected = true;
                        barangaySelect.appendChild(option);
                    });
                    syncShippingFields();
                })
                .catch(() => {
                    barangaySelect.innerHTML = '<option value="">Failed to load</option>';
                });
        });
    }

    addressModeSaved?.addEventListener('change', setAddressModeUI);
    addressModeAnother?.addEventListener('change', setAddressModeUI);
    anotherStreetInput?.addEventListener('input', syncShippingFields);
    anotherCitySelect?.addEventListener('change', syncShippingFields);
    anotherBarangaySelect?.addEventListener('change', syncShippingFields);
    anotherZipInput?.addEventListener('input', syncShippingFields);
    setAddressModeUI();
});
</script>

@include('partials.footer')
</body>
</html>

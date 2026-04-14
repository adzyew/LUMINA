@extends('layouts.customer')

@section('title', 'Help & Support | Lumina')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-4 py-10 max-w-7xl">
        <div class="flex items-center justify-between gap-3 mb-6">
            <h1 class="text-3xl font-playfair font-bold text-gray-900">Help & Support</h1>
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-amber-300 text-black font-semibold hover:bg-amber-400 transition-colors">
                Continue Shopping
            </a>
        </div>

        <div class="bg-white rounded-xl p-5 sm:p-6 border border-gray-200 shadow-sm min-h-[72vh]">
            <p class="text-sm text-gray-600">Need help with your orders, account, shipping, or returns? Pick a topic on the left to view details.</p>

            <div class="mt-5 grid grid-cols-1 xl:grid-cols-[280px_minmax(0,1fr)] gap-5">
                <div class="rounded-xl border border-gray-200 bg-gray-50 p-3 space-y-2 h-fit">
                    <button type="button" class="help-tab w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors bg-amber-100 text-amber-800" data-target="emailPanel" aria-pressed="true">
                        Email Support
                    </button>
                    <button type="button" class="help-tab w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors text-gray-700 hover:bg-amber-50 hover:text-amber-700" data-target="shippingPanel" aria-pressed="false">
                        Shipping
                    </button>
                    <button type="button" class="help-tab w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors text-gray-700 hover:bg-amber-50 hover:text-amber-700" data-target="returnsPanel" aria-pressed="false">
                        Returns & Exchange
                    </button>
                    <button type="button" class="help-tab w-full text-left px-3 py-2.5 rounded-lg text-sm font-semibold transition-colors text-gray-700 hover:bg-amber-50 hover:text-amber-700" data-target="warrantyPanel" aria-pressed="false">
                        Warranty
                    </button>
                </div>

                <div class="rounded-xl border border-gray-200 p-5 sm:p-6 min-h-[420px]">
                    <section id="emailPanel" class="help-panel">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Email Support</p>
                        <h2 class="mt-2 text-2xl font-bold text-gray-900">Get help from our team</h2>
                        <p class="mt-3 text-sm text-gray-600">For order concerns, account updates, product questions, and payment issues, send us an email and include your order number for faster assistance.</p>
                        <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4">
                            <p class="text-xs uppercase tracking-wide text-amber-700">Support Email</p>
                            <a href="mailto:luminajewelryaccessories@gmail.com" class="mt-1 inline-block text-base font-semibold text-gray-900 break-all hover:text-amber-700">
                                luminajewelryaccessories@gmail.com
                            </a>
                        </div>
                    </section>

                    <section id="shippingPanel" class="help-panel hidden">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Shipping</p>
                        <h2 class="mt-2 text-2xl font-bold text-gray-900">Shipping information</h2>
                        <ul class="mt-4 space-y-2 text-sm text-gray-700 list-disc pl-5">
                            <li>Orders are processed after payment confirmation.</li>
                            <li>You will receive tracking details once your parcel is shipped.</li>
                            <li>Delivery lead time depends on your location and courier schedules.</li>
                        </ul>
                        <a href="{{ route('legal.shipping') }}" class="mt-5 inline-flex items-center rounded-lg border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 transition-colors">
                            Read Full Shipping Policy
                        </a>
                    </section>

                    <section id="returnsPanel" class="help-panel hidden">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Returns & Exchange</p>
                        <h2 class="mt-2 text-2xl font-bold text-gray-900">Returns and exchange policy</h2>
                        <ul class="mt-4 space-y-2 text-sm text-gray-700 list-disc pl-5">
                            <li>Items must be unused and in original condition to qualify.</li>
                            <li>Requests should include your order number and proof of issue.</li>
                            <li>Approved returns or exchanges follow our standard process.</li>
                        </ul>
                        <a href="{{ route('legal.returns') }}" class="mt-5 inline-flex items-center rounded-lg border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 transition-colors">
                            Read Full Returns Policy
                        </a>
                    </section>

                    <section id="warrantyPanel" class="help-panel hidden">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Warranty</p>
                        <h2 class="mt-2 text-2xl font-bold text-gray-900">Lifetime warranty details</h2>
                        <ul class="mt-4 space-y-2 text-sm text-gray-700 list-disc pl-5">
                            <li>Warranty covers eligible manufacturing defects.</li>
                            <li>Assessment may require item photos and order details.</li>
                            <li>Coverage exclusions are listed in our warranty terms.</li>
                        </ul>
                        <a href="{{ route('legal.warranty') }}" class="mt-5 inline-flex items-center rounded-lg border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 transition-colors">
                            Read Full Warranty Policy
                        </a>
                    </section>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tabs = document.querySelectorAll('.help-tab');
        const panels = document.querySelectorAll('.help-panel');

        tabs.forEach(function (tab) {
            tab.addEventListener('click', function () {
                const targetId = tab.getAttribute('data-target');

                tabs.forEach(function (button) {
                    button.classList.remove('bg-amber-100', 'text-amber-800');
                    button.classList.add('text-gray-700', 'hover:bg-amber-50', 'hover:text-amber-700');
                    button.setAttribute('aria-pressed', 'false');
                });

                panels.forEach(function (panel) {
                    panel.classList.add('hidden');
                });

                tab.classList.add('bg-amber-100', 'text-amber-800');
                tab.classList.remove('text-gray-700', 'hover:bg-amber-50', 'hover:text-amber-700');
                tab.setAttribute('aria-pressed', 'true');

                const targetPanel = document.getElementById(targetId);
                if (targetPanel) {
                    targetPanel.classList.remove('hidden');
                }
            });
        });
    });
</script>
@endpush

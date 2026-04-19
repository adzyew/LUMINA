@extends('layouts.customer')

@section('title')
    Order #{{ $order->display_order_number }} | Lumina
@endsection

@php
    $openRefundOnLoad = request()->boolean('open_refund') || $errors->hasAny(['reason', 'other_reason', 'details', 'proof_image']);
@endphp

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const refundModal = document.getElementById('refundRequestModal');
        const openRefundBtn = document.getElementById('openRefundRequestModal');
        const closeRefundButtons = document.querySelectorAll('[data-close-refund-modal]');
        const reasonSelect = document.getElementById('refund_reason');
        const otherReasonWrap = document.getElementById('refundOtherReasonWrap');
        const otherReasonInput = document.getElementById('refund_other_reason');
        const proofInput = document.getElementById('refund_proof_image');
        const proofPreviewWrap = document.getElementById('refundProofPreviewWrap');
        const proofPreviewImage = document.getElementById('refundProofPreviewImage');

        const openRefundModal = function () {
            if (!refundModal) return;
            refundModal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeRefundModal = function () {
            if (!refundModal) return;
            refundModal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        if (openRefundBtn && refundModal) {
            openRefundBtn.addEventListener('click', openRefundModal);
        }

        closeRefundButtons.forEach((button) => {
            button.addEventListener('click', closeRefundModal);
        });

        if (reasonSelect && otherReasonWrap && otherReasonInput) {
            const toggleOtherReason = function () {
                const isOther = reasonSelect.value === 'Other';
                otherReasonWrap.classList.toggle('hidden', !isOther);
                otherReasonInput.required = isOther;
                if (!isOther) otherReasonInput.value = '';
            };

            reasonSelect.addEventListener('change', toggleOtherReason);
            toggleOtherReason();
        }

        if (proofInput && proofPreviewWrap && proofPreviewImage) {
            proofInput.addEventListener('change', function () {
                const [file] = proofInput.files || [];
                if (!file) {
                    proofPreviewWrap.classList.add('hidden');
                    proofPreviewImage.src = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function (event) {
                    proofPreviewImage.src = event.target?.result || '';
                    proofPreviewWrap.classList.remove('hidden');
                };
                reader.readAsDataURL(file);
            });
        }

        const stars = document.querySelectorAll('input[name="rating"]');
        const applyStarState = () => {
            const selected = Number(document.querySelector('input[name="rating"]:checked')?.value || 0);
            stars.forEach((input) => {
                const wrap = input.closest('label')?.querySelector('span');
                if (!wrap) return;
                const starValue = Number(input.value);
                const active = starValue <= selected;
                wrap.classList.toggle('border-amber-300', active);
                wrap.classList.toggle('bg-amber-50', active);
                wrap.classList.toggle('text-amber-500', active);
                wrap.classList.toggle('border-gray-300', !active);
                wrap.classList.toggle('bg-white', !active);
                wrap.classList.toggle('text-gray-400', !active);
            });
        };

        if (stars.length) {
            stars.forEach((input) => input.addEventListener('change', applyStarState));
            applyStarState();
        }

        const shouldOpenRefund = @json($openRefundOnLoad);
        if (shouldOpenRefund && refundModal) {
            openRefundModal();
        }
    });
</script>
@endpush

@section('content')
    @php
        $reasonOptions = [
            'Damaged item',
            'Wrong item received',
            'Missing item/part',
            'Item not as described',
            'Late delivery',
            'Other',
        ];

        $isPendingRefund = (bool) ($refundRequest && $refundRequest->status === 'pending');
        $isApprovedRefund = (bool) ($refundRequest && $refundRequest->status === 'approved');
        $isRejectedRefund = (bool) ($refundRequest && $refundRequest->status === 'rejected');
        $canSubmitRefund = !$refundRequest || $isRejectedRefund;
        $existingReason = $refundRequest->reason ?? '';
        $isExistingOtherReason = $existingReason !== '' && !in_array($existingReason, array_slice($reasonOptions, 0, 5), true);
        $selectedRefundReason = old('reason', $isExistingOtherReason ? 'Other' : $existingReason);
        $selectedOtherReason = old('other_reason', $isExistingOtherReason ? $existingReason : '');
    @endphp

    <div class="container mx-auto px-4 sm:px-6 lg:px-10 py-12 max-w-6xl">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4 mb-8">
            <div>
                <a href="{{ route('orders.index') }}" class="text-gray-500 hover:text-amber-600 text-sm transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M11.707 4.293a1 1 0 010 1.414L8.414 9H16a1 1 0 110 2H8.414l3.293 3.293a1 1 0 01-1.414 1.414l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
                <h1 class="text-3xl font-playfair font-bold text-gray-900 mt-2">Order #{{ $order->display_order_number }}</h1>
                <p class="text-gray-600 text-sm mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
                <div class="mt-3 flex flex-wrap items-center gap-2">
                    <a title="Download Invoice (PDF)" href="{{ route('orders.invoice', $order) }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-semibold transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                    </a>
                    @if($order->status === 'delivered')
                    @if($isPendingRefund)
                    <a title="View Refund Status" href="#refund-status" class="inline-flex items-center px-4 py-2 rounded-lg border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-semibold transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </a>
                    @elseif($canSubmitRefund)
                    <button id="openRefundRequestModal" type="button" title="{{ $isRejectedRefund ? 'Resubmit Refund Request' : 'Request Refund' }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 text-sm font-semibold transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75h4.875a2.625 2.625 0 0 1 0 5.25H12M8.25 9.75 10.5 7.5M8.25 9.75 10.5 12m9-7.243V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185Z" />
                        </svg>
                    </button>
                    @else
                    <a title="View Refund Status" href="#refund-status" class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm font-semibold transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75h4.875a2.625 2.625 0 0 1 0 5.25H12M8.25 9.75 10.5 7.5M8.25 9.75 10.5 12m9-7.243V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185Z" />
                        </svg>
                    </a>
                    @endif
                    @endif
                </div>
            </div>
            <span class="self-start px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                {{ $order->status === 'pending'    ? 'bg-amber-100 text-amber-700 border border-amber-200' : '' }}
                {{ $order->status === 'confirmed'  ? 'bg-blue-100 text-blue-700 border border-blue-200' : '' }}
                {{ $order->status === 'processing' ? 'bg-indigo-100 text-indigo-700 border border-indigo-200' : '' }}
                {{ $order->status === 'shipped'    ? 'bg-purple-100 text-purple-700 border border-purple-200' : '' }}
                {{ $order->status === 'delivered'  ? 'bg-green-100 text-green-700 border border-green-200' : '' }}
                {{ $order->status === 'cancelled'  ? 'bg-red-100 text-red-700 border border-red-200' : '' }}
            ">{{ str_replace('_', ' ', $order->status) }}</span>
        </div>

        @php
            $progressSteps = [
                ['key' => 'processed', 'label' => 'Order Processed'],
                ['key' => 'shipped', 'label' => 'Order Shipped'],
                ['key' => 'en_route', 'label' => 'Order En Route'],
                ['key' => 'arrived', 'label' => 'Order Arrived'],
            ];

            $statusToStep = [
                'pending' => 1,
                'confirmed' => 1,
                'processing' => 2,
                'shipped' => 3,
                'delivered' => 4,
                'cancelled' => 1,
                'awaiting_payment' => 0,
            ];

            $currentStep = $statusToStep[$order->status] ?? 0;
            $isCancelled = $order->status === 'cancelled';
        @endphp

        <section class="mb-6 bg-white rounded-2xl border border-gray-200 shadow-sm p-5 sm:p-6">
            <div class="flex flex-wrap items-center justify-between gap-2 mb-5">
                <h2 class="text-sm sm:text-base font-bold text-gray-900 tracking-wide">Order Progress</h2>
                <p class="text-xs sm:text-sm text-gray-500">Order #{{ $order->display_order_number }}</p>
            </div>

            <div class="relative">
                <div class="absolute left-0 right-0 top-5 h-1 rounded-full bg-gray-200"></div>
                <div class="absolute left-0 top-5 h-1 rounded-full {{ $isCancelled ? 'bg-red-400' : 'bg-indigo-500' }}"
                     style="width: {{ max(0, min(100, (($currentStep - 1) / 3) * 100)) }}%;"></div>

                <div class="grid grid-cols-4 gap-2 relative z-10">
                    @foreach($progressSteps as $index => $step)
                        @php
                            $stepNumber = $index + 1;
                            $isDone = $stepNumber <= $currentStep;
                            $dotClass = $isDone
                                ? ($isCancelled ? 'bg-red-500 border-red-500 text-white' : 'bg-indigo-500 border-indigo-500 text-white')
                                : 'bg-white border-gray-300 text-gray-400';
                        @endphp
                        <div class="flex flex-col items-center text-center">
                            <span class="w-10 h-10 rounded-full border-2 inline-flex items-center justify-center {{ $dotClass }}">
                                @if($isDone)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                @else
                                    <span class="text-xs font-semibold">{{ $stepNumber }}</span>
                                @endif
                            </span>
                            <p class="mt-2 text-[11px] sm:text-xs font-semibold text-gray-700 leading-tight">{{ $step['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            @if($isCancelled)
                <p class="mt-4 text-xs sm:text-sm font-semibold text-red-600">This order was cancelled.</p>
            @endif
        </section>

        <div class="space-y-5">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h2 class="text-gray-900 text-lg font-bold mb-4">Order Information</h2>

                <div class="mb-6">
                    <h3 class="text-base font-bold text-gray-900 mb-3">Items Ordered</h3>
                    <div class="space-y-3">
                        @foreach($order->items as $item)
                            <div class="rounded-xl border border-gray-100 bg-gray-50 px-4 py-3 flex items-center gap-4">
                                @if($item->product && $item->product->image_url)
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-14 h-14 rounded-xl object-cover shrink-0">
                                @else
                                    <div class="w-14 h-14 rounded-xl bg-amber-50 flex items-center justify-center shrink-0">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 3h18M3 21h18" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-gray-900 font-semibold truncate">{{ $item->product->name ?? 'Product Unavailable' }}</p>
                                    <p class="text-gray-600 text-sm">Quantity: {{ $item->quantity }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-amber-600 font-bold">Php {{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                                    <p class="text-gray-500 text-xs">Php {{ number_format($item->unit_price, 2) }} each</p>
                                </div>
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
                            <span>Php {{ number_format($order->total_price + $order->discount_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span>Payment:</span>
                            <span>{{ $order->payment_display }}</span>
                        </div>
                        <div class="flex justify-between text-gray-600">
                            <span class="text-gray-900">Discount: {{ $order->discount_percentage }}%</span>
                            <span class="text-amber-600">- Php {{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 mt-3 flex justify-between font-bold text-lg">
                            <span class="text-gray-900">Total Amount</span>
                            <span class="text-amber-600">Php {{ number_format($order->total_price, 2) }}</span>
                        </div>
                        
                    </div>
                </div>

                @if($order->tracking_url)
                    <a href="{{ $order->tracking_url }}" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-1.5 text-amber-600 hover:text-amber-700 font-semibold transition-colors mt-5">
                        Track Shipment
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                        </svg>
                    </a>
                @endif
            </div>

            @if($order->status === 'delivered')
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <h2 class="text-gray-900 text-lg font-bold mb-4">Courier Feedback</h2>
                    <p class="text-sm text-gray-600 mb-4">Help us improve deliveries by rating your courier experience.</p>

                    <form method="POST" action="{{ route('orders.courier-feedback.store', $order) }}" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Rating</label>
                            <div class="flex items-center gap-2">
                                @for($i = 1; $i <= 5; $i++)
                                    @php
                                        $currentRating = (int) old('rating', $courierFeedback->rating ?? 0);
                                        $active = $i <= $currentRating;
                                    @endphp
                                    <label class="cursor-pointer">
                                        <input type="radio" name="rating" value="{{ $i }}" class="sr-only" {{ $currentRating === $i ? 'checked' : '' }} required>
                                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-full border {{ $active ? 'border-amber-300 bg-amber-50 text-amber-500' : 'border-gray-300 bg-white text-gray-400' }}">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        </span>
                                    </label>
                                @endfor
                            </div>
                            @error('rating')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="courier-comment" class="block text-sm font-semibold text-gray-700 mb-2">Comment (optional)</label>
                            <textarea id="courier-comment" name="comment" rows="4" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300" placeholder="How was your delivery experience?">{{ old('comment', $courierFeedback->comment ?? '') }}</textarea>
                            @error('comment')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <p class="text-xs text-gray-500">Courier: {{ $order->courier_name ?: 'Not assigned' }}</p>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-300 text-black font-semibold hover:bg-amber-400 transition-colors">
                                {{ $courierFeedback ? 'Update Feedback' : 'Submit Feedback' }}
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            @if($refundRequest)
                <div id="refund-status" class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                    <h2 class="text-gray-900 text-lg font-bold mb-4">Refund Request</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Reason</p>
                            <p class="font-semibold text-gray-900">{{ $refundRequest->reason }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Status</p>
                            <p class="font-semibold uppercase {{ $refundRequest->status === 'approved' ? 'text-green-700' : ($refundRequest->status === 'rejected' ? 'text-red-700' : 'text-amber-700') }}">{{ $refundRequest->status }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Requested Amount</p>
                            <p class="font-semibold text-gray-900">Php {{ number_format((float) ($refundRequest->requested_amount ?? $order->total_price), 2) }}</p>
                        </div>
                    </div>
                    @if($refundRequest->details)
                        <p class="text-sm text-gray-700 mt-3">{{ $refundRequest->details }}</p>
                    @endif
                    @if($refundRequest->proof_image_path)
                        <a href="{{ asset('storage/' . $refundRequest->proof_image_path) }}" target="_blank" rel="noopener noreferrer" class="inline-block mt-3">
                            <img src="{{ asset('storage/' . $refundRequest->proof_image_path) }}" alt="Refund proof photo" class="h-20 w-20 rounded-lg border border-gray-200 object-cover">
                        </a>
                    @endif
                    @if($refundRequest->admin_notes)
                        <div class="mt-3 rounded-lg border border-gray-200 bg-gray-50 p-3 text-sm text-gray-700">
                            <span class="font-semibold">Admin note:</span> {{ $refundRequest->admin_notes }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    @if($order->status === 'delivered' && $canSubmitRefund)
        <div id="refundRequestModal" class="fixed inset-0 z-[80] hidden" role="dialog" aria-modal="true" aria-labelledby="refund-request-title">
            <div class="absolute inset-0 bg-black/60" data-close-refund-modal></div>
            <div class="absolute inset-0 p-4 sm:p-6 flex items-center justify-center">
                <div class="w-full max-w-2xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl border border-gray-200 shadow-2xl">
                    <div class="sticky top-0 bg-white border-b border-gray-100 px-5 py-4 flex items-center justify-between">
                        <div>
                            <h2 id="refund-request-title" class="text-xl font-bold text-gray-900">Request Refund</h2>
                            <p class="text-sm text-gray-500">Order #{{ $order->display_order_number }}</p>
                        </div>
                        <button type="button" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100" data-close-refund-modal>&times;</button>
                    </div>

                    <form method="POST" action="{{ route('orders.refund.store', $order) }}" class="p-5 space-y-4" enctype="multipart/form-data">
                        @csrf

                        <div>
                            <label for="refund_reason" class="block text-sm font-semibold text-gray-700 mb-2">Reason</label>
                            <select id="refund_reason" name="reason" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300">
                                <option value="">Select a reason</option>
                                @foreach($reasonOptions as $reasonOption)
                                    <option value="{{ $reasonOption }}" {{ $selectedRefundReason === $reasonOption ? 'selected' : '' }}>{{ $reasonOption }}</option>
                                @endforeach
                            </select>
                            @error('reason')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="refundOtherReasonWrap" class="{{ $selectedRefundReason === 'Other' ? '' : 'hidden' }}">
                            <label for="refund_other_reason" class="block text-sm font-semibold text-gray-700 mb-2">Please specify your reason</label>
                            <input
                                id="refund_other_reason"
                                name="other_reason"
                                type="text"
                                maxlength="120"
                                value="{{ $selectedOtherReason }}"
                                placeholder="Enter your reason"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300"
                            >
                            @error('other_reason')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="refund_details" class="block text-sm font-semibold text-gray-700 mb-2">Details</label>
                            <textarea id="refund_details" name="details" rows="4" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300" placeholder="Tell us what happened and why you want a refund.">{{ old('details', $refundRequest->details ?? '') }}</textarea>
                            @error('details')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="refund_proof_image" class="block text-sm font-semibold text-gray-700 mb-2">Proof Photo <span class="text-gray-500 font-normal">(optional)</span></label>
                            <input
                                id="refund_proof_image"
                                name="proof_image"
                                type="file"
                                accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900"
                            >
                            <p class="text-xs text-gray-500 mt-1">Accepted: JPG, PNG, WEBP up to 5MB.</p>
                            <div id="refundProofPreviewWrap" class="mt-3 hidden">
                                <p class="text-xs text-gray-500 mb-1">Selected proof preview</p>
                                <img id="refundProofPreviewImage" src="" alt="Selected refund proof preview" class="h-28 w-28 rounded-lg border border-gray-200 object-cover">
                            </div>
                            @error('proof_image')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-2">
                            <button type="button" data-close-refund-modal class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-100 transition-colors">
                                Cancel
                            </button>
                            <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-300 text-black font-semibold hover:bg-amber-400 transition-colors">
                                Submit Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endsection

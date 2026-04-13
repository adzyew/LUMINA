@extends('layouts.customer')

@section('title', 'Request Refund | Lumina')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-10 py-10 max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('orders.show', $order) }}" class="text-sm text-gray-500 hover:text-amber-600 transition-colors">&larr; Back to Order Details</a>
            <h1 class="text-3xl font-playfair font-bold text-gray-900 mt-2">Request Refund</h1>
            <p class="text-gray-600 text-sm mt-1">Order #{{ $order->display_order_number }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
            @php
                $reasonOptions = [
                    'Damaged item',
                    'Wrong item received',
                    'Missing item/part',
                    'Item not as described',
                    'Late delivery',
                    'Other',
                ];
                $isPending = (bool) ($refundRequest && $refundRequest->status === 'pending');
                $existingReason = $refundRequest->reason ?? '';
                $isExistingOtherReason = $existingReason !== '' && !in_array($existingReason, array_slice($reasonOptions, 0, 5), true);
                $selectedReason = old('reason', $isExistingOtherReason ? 'Other' : $existingReason);
                $otherReasonValue = old('other_reason', $isExistingOtherReason ? $existingReason : '');
            @endphp

            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Refund Details</h2>

                @if($isPending)
                    <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 p-3 text-sm text-amber-800">
                        You already submitted a pending request. Editing is disabled until the request is resolved.
                    </div>
                @endif

                <form method="POST" action="{{ route('orders.refund.store', $order) }}" class="space-y-4" enctype="multipart/form-data">
                    @csrf

                    <div>
                        <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">Reason</label>
                        <select id="reason" name="reason" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300" {{ $isPending ? 'disabled' : '' }}>
                            <option value="">Select a reason</option>
                            @foreach($reasonOptions as $reasonOption)
                                <option value="{{ $reasonOption }}" {{ $selectedReason === $reasonOption ? 'selected' : '' }}>{{ $reasonOption }}</option>
                            @endforeach
                        </select>
                        @if($isPending)
                            <input type="hidden" name="reason" value="{{ $selectedReason }}">
                        @endif
                        @error('reason')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div id="otherReasonWrap" class="{{ $selectedReason === 'Other' ? '' : 'hidden' }}">
                        <label for="other_reason" class="block text-sm font-semibold text-gray-700 mb-2">Please specify your reason</label>
                        <input
                            id="other_reason"
                            name="other_reason"
                            type="text"
                            maxlength="120"
                            value="{{ $otherReasonValue }}"
                            placeholder="Enter your reason"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300"
                            {{ $isPending ? 'disabled' : '' }}
                        >
                        @if($isPending)
                            <input type="hidden" name="other_reason" value="{{ $otherReasonValue }}">
                        @endif
                        @error('other_reason')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="details" class="block text-sm font-semibold text-gray-700 mb-2">Details</label>
                        <textarea id="details" name="details" rows="5" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300" placeholder="Tell us what happened and why you want a refund." {{ $isPending ? 'disabled' : '' }}>{{ old('details', $refundRequest->details ?? '') }}</textarea>
                        @if($isPending)
                            <input type="hidden" name="details" value="{{ old('details', $refundRequest->details ?? '') }}">
                        @endif
                        @error('details')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="proof_image" class="block text-sm font-semibold text-gray-700 mb-2">Proof Photo <span class="text-gray-500 font-normal">(optional)</span></label>
                        <input
                            id="proof_image"
                            name="proof_image"
                            type="file"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900"
                            {{ $isPending ? 'disabled' : '' }}
                        >
                        <p class="text-xs text-gray-500 mt-1">Accepted: JPG, PNG, WEBP up to 5MB.</p>
                        <div id="proofPreviewWrap" class="mt-3 hidden">
                            <p class="text-xs text-gray-500 mb-1">Selected proof preview</p>
                            <img id="proofPreviewImage" src="" alt="Selected proof preview" class="h-28 w-28 rounded-lg border border-gray-200 object-cover">
                        </div>
                        @if($refundRequest?->proof_image_path)
                            <div class="mt-3">
                                <p class="text-xs text-gray-500 mb-1">Current submitted proof</p>
                                <a href="{{ asset('storage/' . $refundRequest->proof_image_path) }}" target="_blank" rel="noopener noreferrer" class="inline-block">
                                    <img src="{{ asset('storage/' . $refundRequest->proof_image_path) }}" alt="Current proof photo" class="h-28 w-28 rounded-lg border border-gray-200 object-cover">
                                </a>
                            </div>
                        @endif
                        @error('proof_image')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-300 text-black font-semibold hover:bg-amber-400 transition-colors {{ $isPending ? 'opacity-60 cursor-not-allowed' : '' }}" {{ $isPending ? 'disabled' : '' }}>
                            {{ $refundRequest ? 'Update Request' : 'Submit Request' }}
                        </button>
                        <a href="{{ route('orders.show', $order) }}" class="px-5 py-2.5 rounded-lg border border-gray-300 bg-white text-gray-700 font-semibold hover:bg-gray-100 transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 h-max">
                <h3 class="text-base font-bold text-gray-900 mb-3">Order Summary</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between text-gray-600">
                        <span>Status</span>
                        <span class="font-semibold text-gray-900 uppercase">{{ str_replace('_', ' ', $order->status) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Payment</span>
                        <span class="font-semibold text-gray-900">{{ $order->payment_display }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600">
                        <span>Amount</span>
                        <span class="font-bold text-amber-600">Php {{ number_format($order->total_price, 2) }}</span>
                    </div>
                </div>

                @if($refundRequest)
                    <div class="mt-4 rounded-xl border border-gray-200 bg-gray-50 p-3">
                        <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold">Current Request</p>
                        <p class="mt-1 text-sm font-semibold text-gray-900">{{ $refundRequest->reason }}</p>
                        <p class="text-xs mt-1 text-gray-600">Status: <span class="font-semibold uppercase">{{ $refundRequest->status }}</span></p>
                        @if($refundRequest->proof_image_path)
                            <a href="{{ asset('storage/' . $refundRequest->proof_image_path) }}" target="_blank" rel="noopener noreferrer" class="inline-block mt-2">
                                <img src="{{ asset('storage/' . $refundRequest->proof_image_path) }}" alt="Submitted proof photo" class="h-20 w-20 rounded-lg border border-gray-200 object-cover">
                            </a>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    (function () {
        const reasonSelect = document.getElementById('reason');
        const otherWrap = document.getElementById('otherReasonWrap');
        const otherInput = document.getElementById('other_reason');
        const proofInput = document.getElementById('proof_image');
        const proofPreviewWrap = document.getElementById('proofPreviewWrap');
        const proofPreviewImage = document.getElementById('proofPreviewImage');
        if (!reasonSelect || !otherWrap || !otherInput) return;

        const toggleOtherReason = function () {
            const isOther = reasonSelect.value === 'Other';
            otherWrap.classList.toggle('hidden', !isOther);
            otherInput.required = isOther;
            if (!isOther) {
                otherInput.value = '';
            }
        };

        reasonSelect.addEventListener('change', toggleOtherReason);
        toggleOtherReason();

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
    })();
</script>
@endpush

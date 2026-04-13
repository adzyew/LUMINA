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
            <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-200 shadow-sm p-5">
                <h2 class="text-lg font-bold text-gray-900 mb-4">Refund Details</h2>

                <form method="POST" action="{{ route('orders.refund.store', $order) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="reason" class="block text-sm font-semibold text-gray-700 mb-2">Reason</label>
                        <select id="reason" name="reason" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300">
                            @php
                                $reasonOptions = [
                                    'Damaged item',
                                    'Wrong item received',
                                    'Missing item/part',
                                    'Item not as described',
                                    'Late delivery',
                                    'Other',
                                ];
                                $selectedReason = old('reason', $refundRequest->reason ?? '');
                            @endphp
                            <option value="">Select a reason</option>
                            @foreach($reasonOptions as $reason)
                                <option value="{{ $reason }}" {{ $selectedReason === $reason ? 'selected' : '' }}>{{ $reason }}</option>
                            @endforeach
                        </select>
                        @error('reason')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="details" class="block text-sm font-semibold text-gray-700 mb-2">Details</label>
                        <textarea id="details" name="details" rows="5" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm text-gray-900 focus:outline-none focus:ring-2 focus:ring-amber-300" placeholder="Tell us what happened and why you want a refund.">{{ old('details', $refundRequest->details ?? '') }}</textarea>
                        @error('details')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-2">
                        <button type="submit" class="px-5 py-2.5 rounded-lg bg-amber-300 text-black font-semibold hover:bg-amber-400 transition-colors">
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
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


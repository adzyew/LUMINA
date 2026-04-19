@extends('layouts.customer')

@section('title', 'My Orders | Lumina')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 lg:px-3 py-3">
        <div class="flex items-center justify-between mb-5 px-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-playfair font-bold text-gray-900 mb-2 mt-4">My Orders</h1>
            </div>
        </div>

        @php
            $statusFilters = [
                '' => 'All',
                'pending' => 'Pending',
                'confirmed' => 'Confirmed',
                'processing' => 'Processing',
                'shipped' => 'Shipped',
                'delivered' => 'Delivered',
                'cancelled' => 'Cancelled',
            ];
            $activeStatus = $selectedStatus ?? '';
            $queryBase = request()->except(['status', 'page']);
        @endphp

        <div class="mb-5 px-4">
            <div class="flex flex-wrap gap-2">
                @foreach($statusFilters as $statusValue => $label)
                    <a href="{{ route('orders.index', array_merge($queryBase, $statusValue !== '' ? ['status' => $statusValue] : [])) }}"
                       class="h-10 px-4 inline-flex items-center justify-center rounded-lg border text-sm font-semibold transition-colors {{ $activeStatus === $statusValue ? 'bg-amber-300 border-amber-300 text-black' : 'bg-white border-gray-200 text-gray-700 hover:bg-amber-50 hover:text-amber-700 hover:border-amber-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="space-y-1">
            @forelse($orders as $order)
                @php
                    $primaryItem = $order->items->first();
                    $primaryProduct = $primaryItem?->product;
                    $totalQuantity = (int) $order->items->sum('quantity');
                    $modalId = 'order-modal-' . $order->id;
                    $modalStatusToStep = [
                        'pending' => 1,
                        'confirmed' => 1,
                        'processing' => 2,
                        'shipped' => 3,
                        'delivered' => 4,
                        'cancelled' => 1,
                        'awaiting_payment' => 0,
                    ];
                    $modalCurrentStep = $modalStatusToStep[$order->status] ?? 0;
                    $modalCancelled = $order->status === 'cancelled';
                @endphp

                <div class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm">
                    <div class="flex flex-col sm:flex-row  sm:justify-between gap-3">
                        <div>
                            <p class="text-gray-900 font-normal text-sm">Order Number: {{ $order->display_order_number }} </p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                    </div>

                    <div class="mt-2 pt-4 border-t border-gray-100 grid grid-cols-1 sm:grid-cols-7 gap-2 text-sm">
                        <div>
                            <div class="flex items-center gap-3">
                                @if($primaryProduct && ($primaryProduct->image_url ?? null))
                                    <img src="{{ $primaryProduct->image_url }}" alt="{{ $primaryProduct->name }}" class="w-12 h-12 rounded-lg object-cover border border-gray-200 shrink-0">
                                @else
                                    <div class="w-12 h-12 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 3h18M3 21h18" />
                                        </svg>
                                    </div>
                                @endif
                                <p class="text-gray-700 font-medium">x{{ max($totalQuantity, 1) }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-500">Product</p>
                            <p class="text-gray-700 font-medium">{{ $primaryProduct->name ?? 'Product Unavailable' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Total</p>
                            <p class="text-amber-600 font-bold">Php {{ number_format($order->total_price, 2) }}</p>
                            
                        </div>
                        <div>
                            <p class="text-gray-500">Payment</p>
                            <p class="text-gray-700 font-medium">{{ $order->payment_display }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Tracking Number</p>
                            <div class="flex items-center justify-between gap-3">
                                <p class="text-gray-700 font-medium">{{ $order->tracking_number ?? 'Pending assignment' }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-gray-500">Status</p>
                            <div class="flex items-center justify-between">
                                <span class="px-1 text-xs font-bold uppercase tracking-wider
                                {{ $order->status === 'pending' ? ' text-amber-700 ' : '' }}
                                {{ $order->status === 'confirmed' ? ' text-blue-700 ' : '' }}
                                {{ $order->status === 'processing' ? ' text-indigo-700  ' : '' }}
                                {{ $order->status === 'shipped' ? ' text-purple-700  ' : '' }}
                                {{ $order->status === 'delivered' ? ' text-green-700  ' : '' }}
                                {{ $order->status === 'cancelled' ? ' text-red-700  ' : '' }}">
                                {{ $order->status }}
                            </span>
                            </div>
                            
                        </div>
                        <div>
                            <div class="flex items-center justify-center">
                             <button type="button" data-open-order-modal="{{ $modalId }}" title="View Details" class="w-10 h-10 flex items-center justify-center rounded-xl bg-amber-400/10 hover:bg-amber-400 text-amber-500 hover:text-black transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-7">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                    </svg>
                            </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="{{ $modalId }}" class="fixed inset-0 z-[70] hidden" role="dialog" aria-modal="true" aria-labelledby="{{ $modalId }}-title">
                    <div class="absolute inset-0 bg-black/50" data-close-order-modal></div>
                    <div class="absolute inset-0 p-4 sm:p-6 flex items-center justify-center">
                        <div class="w-full max-w-3xl max-h-[90vh] overflow-y-auto bg-white rounded-2xl border border-gray-200 shadow-2xl">
                            <div class="sticky top-0 bg-white border-b border-gray-100 px-5 py-4 flex items-center justify-between">
                                <div>
                                    <h2 id="{{ $modalId }}-title" class="text-xl font-bold text-gray-900">Order Number:{{ $order->display_order_number }}</h2>
                                    <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                                </div>
                                <button type="button" class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-100" data-close-order-modal>&times;</button>
                            </div>

                            <div class="p-5 space-y-4">
                                <section class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                    <div class="relative">
                                        <div class="absolute left-0 right-0 top-4 h-1 rounded-full bg-gray-200"></div>
                                        <div class="absolute left-0 top-4 h-1 rounded-full {{ $modalCancelled ? 'bg-red-400' : 'bg-indigo-500' }}"
                                             style="width: {{ max(0, min(100, (($modalCurrentStep - 1) / 3) * 100)) }}%;"></div>

                                        <div class="grid grid-cols-4 gap-2 relative z-10">
                                            @foreach(['Order Processed', 'Order Shipped', 'Order En Route', 'Order Arrived'] as $index => $label)
                                                @php
                                                    $step = $index + 1;
                                                    $done = $step <= $modalCurrentStep;
                                                    $dotClass = $done
                                                        ? ($modalCancelled ? 'bg-red-500 border-red-500 text-white' : 'bg-indigo-500 border-indigo-500 text-white')
                                                        : 'bg-white border-gray-300 text-gray-400';
                                                @endphp
                                                <div class="flex flex-col items-center text-center">
                                                    <span class="w-8 h-8 rounded-full border-2 inline-flex items-center justify-center {{ $dotClass }}">
                                                        @if($done)
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                        @else
                                                            <span class="text-[10px] font-semibold">{{ $step }}</span>
                                                        @endif
                                                    </span>
                                                    <p class="mt-1 text-[10px] sm:text-[11px] font-semibold text-gray-700 leading-tight">{{ $label }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    @if($modalCancelled)
                                        <p class="mt-3 text-xs font-semibold text-red-600">This order was cancelled.</p>
                                    @endif
                                </section>

                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3 text-sm">
                                    <div>
                                        <p class="text-gray-500">Items</p>
                                        <p class="text-gray-900 font-semibold">{{ $totalQuantity }} item(s)</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Total</p>
                                        <p class="text-amber-600 font-bold">Php {{ number_format($order->total_price, 2) }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Payment</p>
                                        <p class="text-gray-900 font-semibold">{{ $order->payment_display }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Tracking Number:</p>
                                        <p class="text-gray-900 font-semibold">{{ $order->tracking_number ?? 'Pending assignment' }}</p>
                                    </div>
                                </div>

                                <div class="rounded-xl border border-gray-200 divide-y divide-gray-100 overflow-hidden">
                                    @foreach($order->items as $item)
                                        <div class="p-4 flex items-center gap-3">
                                            @if($item->product && ($item->product->image_url ?? null))
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" class="w-14 h-14 rounded-lg object-cover border border-gray-200 shrink-0">
                                            @else
                                                <div class="w-14 h-14 rounded-lg bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3 3h18M3 21h18" />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="font-semibold text-gray-900 truncate">{{ $item->product->name ?? 'Product Unavailable' }}</p>
                                                <p class="text-sm text-gray-500">x{{ $item->quantity }} • Php {{ number_format($item->unit_price, 2) }} each</p>
                                            </div>
                                            <p class="font-bold text-amber-600">Php {{ number_format($item->unit_price * $item->quantity, 2) }}</p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="flex justify-end">
                                    <div class="flex items-center gap-2">
                                        @if($order->status === 'delivered')
                                        <a title="Request Refund" href="{{ route('orders.show', ['order' => $order, 'open_refund' => 1]) }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-amber-300 bg-amber-50 text-amber-700 font-semibold hover:bg-amber-100 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9.75h4.875a2.625 2.625 0 0 1 0 5.25H12M8.25 9.75 10.5 7.5M8.25 9.75 10.5 12m9-7.243V21.75l-3.75-1.5-3.75 1.5-3.75-1.5-3.75 1.5V4.757c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0 1 11.186 0c1.1.128 1.907 1.077 1.907 2.185Z" />
                                            </svg>
                                        </a>
                                        @endif
                                        <a title="Download Invoice (PDF)" href="{{ route('orders.invoice', $order) }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold transition-colors" >
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                            </svg>
                                        </a>
                                        <a title="View Order Details" href="{{ route('orders.show', $order) }}" class="inline-flex items-center px-4 py-2 rounded-lg border border-amber-300 bg-amber-50 hover:bg-amber-100 text-amber-700 font-semibold transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Zm3.75 11.625a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-amber-50 rounded-2xl border border-amber-100">
                    <p class="text-gray-900 font-bold text-lg mb-2">No orders yet</p>
                    <p class="text-gray-600 mb-6">Start shopping to see your order history here.</p>
                    <a href="{{ route('products.index') }}" class="px-6 py-3 bg-amber-300 text-black font-bold rounded-full hover:bg-amber-400 transition-colors">
                        Browse Collection
                    </a>
                </div>
            @endforelse
        </div>

        <div class="mt-5 flex flex-col sm:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
            </p>

            <div class="flex items-center gap-2">
                @if($orders->onFirstPage())
                    <span class="px-3 py-2 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed">Previous</span>
                @else
                    <a href="{{ $orders->previousPageUrl() }}" class="px-3 py-2 text-sm rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors">Previous</a>
                @endif

                @foreach($orders->getUrlRange(max(1, $orders->currentPage() - 1), min($orders->lastPage(), $orders->currentPage() + 1)) as $page => $url)
                    @if($page == $orders->currentPage())
                        <span class="px-3 py-2 text-sm rounded-lg bg-amber-300 text-black font-bold">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="px-3 py-2 text-sm rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors">{{ $page }}</a>
                    @endif
                @endforeach

                @if($orders->hasMorePages())
                    <a href="{{ $orders->nextPageUrl() }}" class="px-3 py-2 text-sm rounded-lg border border-gray-200 bg-white text-gray-700 hover:bg-gray-50 transition-colors">Next</a>
                @else
                    <span class="px-3 py-2 text-sm rounded-lg border border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed">Next</span>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function closeModal(modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }

            function openModal(modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            document.querySelectorAll('[data-open-order-modal]').forEach((button) => {
                button.addEventListener('click', function () {
                    const modalId = button.getAttribute('data-open-order-modal');
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        openModal(modal);
                    }
                });
            });

            document.querySelectorAll('[data-close-order-modal]').forEach((button) => {
                button.addEventListener('click', function () {
                    const modal = button.closest('[role="dialog"]');
                    if (modal) {
                        closeModal(modal);
                    }
                });
            });

            document.addEventListener('keydown', function (event) {
                if (event.key !== 'Escape') {
                    return;
                }

                const activeModal = document.querySelector('[role="dialog"]:not(.hidden)');
                if (activeModal) {
                    closeModal(activeModal);
                }
            });
        });
    </script>
@endsection

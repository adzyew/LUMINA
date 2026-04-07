<!doctype html>
<html>
<head>
    @include('partials.favicon')
    <title>Your Cart | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-stone-100 text-gray-900 font-sans antialiased flex flex-col min-h-screen">

    @include('partials.navbar')

    <div class="grow container mx-auto px-4 sm:px-6 py-24">
        
        <h1 class="text-3xl sm:text-4xl font-playfair font-bold text-amber-600 mb-8 text-center sm:text-left">Your Shopping Cart</h1>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="lg:w-3/4">
                    <div class="bg-white border border-gray-200 rounded-lg overflow-hidden shadow-lg">
                        <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[620px]">
                            <thead class="bg-amber-50 text-gray-900 text-lg font-bold tracking-wider border-b border-amber-200">
                                <tr>
                                    <th class="p-4 sm:p-6">Product</th>
                                    <th class="p-4 sm:p-6 hidden sm:table-cell">Price</th>
                                    <th class="p-4 sm:p-6 text-center">Quantity</th>
                                    <th class="p-4 sm:p-6 text-right">Subtotal</th>
                                    <th class="p-4 sm:p-6"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @php $total = 0; @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <tr id="cart-row-{{ $id }}" class="bg-white hover:bg-amber-50/50 transition-colors">
                                        
                                        <td class="p-4 sm:p-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-lg overflow-hidden shrink-0 border border-gray-200">
                                                        <img src="{{ asset($details['image']) }}" class="w-full h-full object-cover">
                                                    </div>
                                                <div>
                                                    <h3 class="font-bold text-gray-900 text-sm sm:text-base">{{ $details['name'] }}</h3>
                                                    <p class="text-gray-400 text-xs sm:hidden">₱{{ number_format($details['price'], 2) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="p-4 sm:p-6 hidden sm:table-cell text-green-500 font-medium">
                                            ₱{{ number_format($details['price'], 2) }}
                                        </td>

                                        <td class="p-4 sm:p-6 text-center">
                                            <div class="inline-flex items-center border border-amber-200 rounded-lg overflow-hidden">
                                                <form action="{{ route('cart.update') }}" method="POST" class="m-0 cart-update-form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <input type="hidden" name="quantity" value="{{ max(0, $details['quantity'] - 1) }}">
                                                    <button type="submit" data-direction="decrement" class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100">−</button>
                                                </form>

                                                <div class="px-6 py-1 bg-amber-50 text-amber-700 font-bold qty-display" data-id="{{ $id }}">{{ $details['quantity'] }}</div>

                                                <form action="{{ route('cart.update') }}" method="POST" class="m-0 cart-update-form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <input type="hidden" name="quantity" value="{{ $details['quantity'] + 1 }}">
                                                    <button type="submit" data-direction="increment" class="px-3 py-1 bg-amber-50 text-amber-700 border border-amber-200 hover:bg-amber-100">+</button>
                                                </form>
                                            </div>
                                        </td>

                                        <td class="p-4 sm:p-6 text-right font-bold text-green-500">
                                            <span class="item-subtotal" data-id="{{ $id }}">₱{{ number_format($details['price'] * $details['quantity'], 2) }}</span>
                                        </td>

                                        <td class="p-4 sm:p-6 text-right">
                                            <form action="{{ route('cart.remove') }}" method="POST" class="cart-remove-form">
                                                @csrf
                                                @method('DELETE')
                                                <input type="hidden" name="id" value="{{ $id }}">
                                                <button type="submit" class="text-gray-500 hover:text-red-500 transition-colors remove-btn" data-id="{{ $id }}">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </div>
                    </div>
                </div>

                <div class="lg:w-1/4">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-md sticky top-24">
                        <h3 class="font-playfair font-bold text-xl text-gray-900 mb-6">Order Summary</h3>

                        <div class="space-y-3 text-sm border-b border-gray-200 pb-6 mb-6">
                            <div class="flex justify-between text-gray-400">
                                <span>Subtotal</span>
                                <span id="cartSubtotalValue" class="text-gray-900 font-medium">&#8369;{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span>Shipping</span>
                                <span class="text-amber-600 font-medium">Free</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-6">
                            <span class="text-lg font-bold text-gray-900">Total</span>
                            <span id="cartTotalValue" class="text-2xl font-playfair font-bold text-amber-600">&#8369;{{ number_format($total, 2) }}</span>
                        </div>

                        @auth
                        <a href="{{ route('checkout') }}" class="block w-full py-4 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-all shadow-lg shadow-amber-300/20 text-center">
                            Proceed to Checkout
                        </a>
                        @else
                        <a href="{{ route('login') }}" class="block w-full py-4 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-all shadow-lg shadow-amber-300/20 text-center">
                            Login to Checkout
                        </a>
                        @endauth
                        
                        <a href="{{ route('products.index') }}" class="block text-center mt-4 text-sm text-amber-600 hover:text-amber-700 transition-colors">
                            Continue Shopping
                        </a>
                    </div>
                </div>

            </div>
        @else
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-amber-50 rounded-full flex items-center justify-center mx-auto mb-6 text-amber-400">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Your cart is empty</h2>
                <p class="text-gray-500 mb-8">Looks like you haven't added any luxury pieces yet.</p>
                <a href="{{ route('products.index') }}" class="px-8 py-3 bg-amber-300 text-black font-bold rounded-full hover:bg-amber-400 transition-all">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
    <div id="removeConfirmModal" class="fixed inset-0 z-[60] hidden">
        <div class="absolute inset-0 bg-black/50" data-modal-close></div>
        <div class="relative min-h-full flex items-center justify-center p-4">
            <div class="w-full max-w-md rounded-2xl bg-white shadow-2xl border border-gray-200 overflow-hidden">
                <div class="px-6 pt-6 pb-2">
                    <h3 class="text-xl font-bold text-gray-900">Remove Item</h3>
                </div>
                <div class="px-6 pb-6">
                    <p id="removeConfirmMessage" class="text-gray-600">Are you sure you want to remove this item?</p>
                </div>
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end gap-3">
                    <button type="button" id="removeConfirmCancelBtn" class="px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition-colors">Cancel</button>
                    <button type="button" id="removeConfirmOkBtn" class="px-4 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600 transition-colors">Remove</button>
                </div>
            </div>
        </div>
    </div>
    <div id="cartActionToast" class="fixed top-24 right-4 sm:right-6 z-[70] max-w-sm w-[calc(100vw-2rem)] sm:w-auto px-4 py-3 rounded-xl shadow-lg flex items-center gap-3 opacity-0 translate-y-2 pointer-events-none transition-all duration-300 bg-emerald-500 text-white">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
        </svg>
        <span id="cartActionToastMessage" class="text-sm font-semibold"></span>
    </div>

    <script>
        (function () {
            const removeModal = document.getElementById('removeConfirmModal');
            const removeMessage = document.getElementById('removeConfirmMessage');
            const removeOkBtn = document.getElementById('removeConfirmOkBtn');
            const removeCancelBtn = document.getElementById('removeConfirmCancelBtn');
            const toast = document.getElementById('cartActionToast');
            const toastMessage = document.getElementById('cartActionToastMessage');
            let modalResolver = null;
            let toastTimer = null;

            function updateNavbarCartCount(nextCount) {
                const badge = document.getElementById('navbarCartCount');
                if (!badge) return;
                const parsed = Number(nextCount) || 0;
                badge.textContent = String(parsed);
                badge.classList.toggle('hidden', parsed <= 0);
            }

            function updateTotals(totalValue) {
                const subtotalEl = document.getElementById('cartSubtotalValue');
                const totalEl = document.getElementById('cartTotalValue');
                if (subtotalEl) subtotalEl.textContent = '\u20B1' + totalValue;
                if (totalEl) totalEl.textContent = '\u20B1' + totalValue;
            }

            function showToast(message, tone) {
                if (!toast || !toastMessage) return;
                toastMessage.textContent = message;
                toast.classList.remove('bg-emerald-500', 'bg-red-500', 'text-white');
                if (tone === 'error') {
                    toast.classList.add('bg-red-500', 'text-white');
                } else {
                    toast.classList.add('bg-emerald-500', 'text-white');
                }

                requestAnimationFrame(function () {
                    toast.classList.remove('opacity-0', 'translate-y-2', 'pointer-events-none');
                });

                if (toastTimer) {
                    window.clearTimeout(toastTimer);
                }
                toastTimer = window.setTimeout(function () {
                    toast.classList.add('opacity-0', 'translate-y-2', 'pointer-events-none');
                }, 2200);
            }

            function closeRemoveModal(confirmed) {
                if (!removeModal) return;
                removeModal.classList.add('hidden');
                if (modalResolver) {
                    modalResolver(confirmed);
                    modalResolver = null;
                }
            }

            function askRemoveConfirmation(message) {
                if (!removeModal || !removeMessage) {
                    return Promise.resolve(window.confirm(message));
                }
                removeMessage.textContent = message || 'Are you sure you want to remove this item?';
                removeModal.classList.remove('hidden');
                return new Promise(function (resolve) {
                    modalResolver = resolve;
                });
            }

            if (removeOkBtn) {
                removeOkBtn.addEventListener('click', function () {
                    closeRemoveModal(true);
                });
            }

            if (removeCancelBtn) {
                removeCancelBtn.addEventListener('click', function () {
                    closeRemoveModal(false);
                });
            }

            if (removeModal) {
                removeModal.querySelectorAll('[data-modal-close]').forEach(function (el) {
                    el.addEventListener('click', function () {
                        closeRemoveModal(false);
                    });
                });
            }

            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape' && removeModal && !removeModal.classList.contains('hidden')) {
                    closeRemoveModal(false);
                }
            });

            document.querySelectorAll('.cart-update-form').forEach(function (form) {
                form.addEventListener('submit', async function (event) {
                    event.preventDefault();
                    const fd = new FormData(form);
                    const nextQuantity = Number(fd.get('quantity')) || 0;

                    if (nextQuantity <= 0) {
                        const confirmed = await askRemoveConfirmation('Are you sure you want to remove this item?');
                        if (!confirmed) return;
                    }

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            body: fd,
                        });
                        const data = await response.json();
                        if (!response.ok || !data.success) {
                            alert(data.message || 'Failed to update cart');
                            return;
                        }

                        const id = fd.get('id');
                        if (data.removed) {
                            const row = document.getElementById('cart-row-' + id);
                            if (row) row.remove();
                            showToast(data.message || 'Item removed from cart.', 'success');
                        } else {
                            const qtyEl = document.querySelector('.qty-display[data-id="' + id + '"]');
                            if (qtyEl) qtyEl.textContent = data.quantity;

                            const subEl = document.querySelector('.item-subtotal[data-id="' + id + '"]');
                            if (subEl) subEl.textContent = '\u20B1' + data.item_subtotal;

                            const row = document.getElementById('cart-row-' + id);
                            if (row) {
                                const currentQty = Number(data.quantity) || 0;
                                row.querySelectorAll('.cart-update-form').forEach(function (updateForm) {
                                    const quantityInput = updateForm.querySelector('input[name="quantity"]');
                                    const submitBtn = updateForm.querySelector('button[type="submit"]');
                                    if (!quantityInput || !submitBtn) return;

                                    const direction = submitBtn.dataset.direction || '';
                                    if (direction === 'decrement') {
                                        quantityInput.value = String(Math.max(0, currentQty - 1));
                                    } else {
                                        quantityInput.value = String(currentQty + 1);
                                    }
                                });
                            }
                        }

                        updateTotals(data.total);
                        if (typeof data.cart_count !== 'undefined') {
                            updateNavbarCartCount(data.cart_count);
                        }
                    } catch (error) {
                        showToast('Failed to update cart.', 'error');
                    }
                });
            });

            document.querySelectorAll('.cart-remove-form').forEach(function (form) {
                form.addEventListener('submit', async function (event) {
                    event.preventDefault();
                    const confirmed = await askRemoveConfirmation('Are you sure you want to remove this item?');
                    if (!confirmed) return;

                    const fd = new FormData(form);

                    try {
                        const response = await fetch(form.action, {
                            method: 'POST',
                            headers: { 'X-Requested-With': 'XMLHttpRequest' },
                            body: fd,
                        });
                        const data = await response.json();
                        if (!response.ok || !data.success) {
                            alert(data.message || 'Failed to remove item');
                            return;
                        }

                        const id = fd.get('id');
                        const row = document.getElementById('cart-row-' + id);
                        if (row) row.remove();
                        showToast(data.message || 'Item removed from cart.', 'success');

                        updateTotals(data.total);
                        if (typeof data.cart_count !== 'undefined') {
                            updateNavbarCartCount(data.cart_count);
                        }
                    } catch (error) {
                        showToast('Failed to remove item.', 'error');
                    }
                });
            });
        })();
    </script>

    

</body>
</html>





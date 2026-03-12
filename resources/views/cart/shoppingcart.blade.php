<!doctype html>
<html>
<head>
    <title>Your Cart | Lumina</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @include('partials.theme_init')
    @vite('resources/css/app.css')
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-gray-50 text-gray-900 dark:bg-black dark:text-white font-sans antialiased flex flex-col min-h-screen transition-colors">

    @include('partials.navbar')

    <div class="grow container mx-auto px-4 sm:px-6 py-24">
        
        <h1 class="text-3xl sm:text-4xl font-playfair font-bold text-amber-300 mb-8 text-center sm:text-left">Your Shopping Cart</h1>

        @if(session('cart') && count(session('cart')) > 0)
            <div class="flex flex-col lg:flex-row gap-8">
                
                <div class="lg:w-3/4">
                    <div class="bg-gray-900 border border-white/10 rounded-lg overflow-hidden shadow-2xl">
                        <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[620px]">
                            <thead class="bg-black text-amber-300 text-lg font-bold tracking-wider">
                                <tr>
                                    <th class="p-4 sm:p-6">Product</th>
                                    <th class="p-4 sm:p-6 hidden sm:table-cell">Price</th>
                                    <th class="p-4 sm:p-6 text-center">Quantity</th>
                                    <th class="p-4 sm:p-6 text-right">Subtotal</th>
                                    <th class="p-4 sm:p-6"></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/10">
                                @php $total = 0; @endphp
                                @foreach(session('cart') as $id => $details)
                                    @php $total += $details['price'] * $details['quantity']; @endphp
                                    <tr id="cart-row-{{ $id }}" class="hover:bg-white/5 transition-colors">
                                        
                                        <td class="p-4 sm:p-6">
                                            <div class="flex items-center gap-4">
                                                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-800 rounded-lg overflow-hidden shrink-0 border border-white/5">
                                                        <img src="{{ asset($details['image']) }}" class="w-full h-full object-cover">
                                                    </div>
                                                <div>
                                                    <h3 class="font-bold text-white text-sm sm:text-base">{{ $details['name'] }}</h3>
                                                    <p class="text-gray-400 text-xs sm:hidden">₱{{ number_format($details['price'], 2) }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="p-4 sm:p-6 hidden sm:table-cell text-green-500 font-medium">
                                            ₱{{ number_format($details['price'], 2) }}
                                        </td>

                                        <td class="p-4 sm:p-6 text-center">
                                            <div class="inline-flex items-center border border-gray-800 rounded-lg overflow-hidden">
                                                <form action="{{ route('cart.update') }}" method="POST" class="m-0 cart-update-form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <input type="hidden" name="quantity" value="{{ max(0, $details['quantity'] - 1) }}">
                                                    <button type="submit" class="px-3 py-1 bg-black text-amber-300 hover:bg-gray-800">−</button>
                                                </form>

                                                <div class="px-6 py-1 bg-black text-amber-300 font-bold qty-display" data-id="{{ $id }}">{{ $details['quantity'] }}</div>

                                                <form action="{{ route('cart.update') }}" method="POST" class="m-0 cart-update-form">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $id }}">
                                                    <input type="hidden" name="quantity" value="{{ $details['quantity'] + 1 }}">
                                                    <button type="submit" class="px-3 py-1 bg-black text-amber-300 hover:bg-gray-800">+</button>
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
                    <div class="bg-gray-900 border border-white/10 rounded-2xl p-6 shadow-2xl sticky top-24">
                        <h3 class="font-playfair font-bold text-xl text-white mb-6">Order Summary</h3>
                        
                        <div class="space-y-3 text-sm border-b border-white/10 pb-6 mb-6">
                            <div class="flex justify-between text-gray-400">
                                <span>Subtotal</span>
                                <span class="text-white font-medium">₱{{ number_format($total, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-gray-400">
                                <span>Shipping</span>
                                <span class="text-amber-300 font-medium">Free</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-6">
                            <span class="text-lg font-bold text-white">Total</span>
                            <span class="text-2xl font-playfair font-bold text-amber-300">₱{{ number_format($total, 2) }}</span>
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
                        
                        <a href="{{ route('products.index') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-white transition-colors">
                            Continue Shopping
                        </a>
                    </div>
                </div>

            </div>
        @else
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-gray-900 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-600">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-white mb-2">Your cart is empty</h2>
                <p class="text-gray-400 mb-8">Looks like you haven't added any luxury pieces yet.</p>
                <a href="{{ route('products.index') }}" class="px-8 py-3 bg-amber-300 text-black font-bold rounded-full hover:bg-amber-400 transition-all">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    <script>
        // Simple AJAX handlers for cart update/remove forms
        (function(){
            function csrfToken(){
                const t = document.querySelector('meta[name="csrf-token"]');
                return t ? t.getAttribute('content') : document.querySelector('input[name="_token"]').value;
            }

            // Handle update forms
            document.querySelectorAll('.cart-update-form').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    const fd = new FormData(form);
                    fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: fd
                    }).then(r => r.json()).then(data => {
                        if (!data.success) { alert(data.message || 'Failed to update cart'); return; }
                        const id = fd.get('id');
                        if (data.removed) {
                            const row = document.getElementById('cart-row-' + id);
                            if (row) row.remove();
                        } else {
                            // update quantity display and item subtotal
                            const qtyEl = document.querySelector('.qty-display[data-id="' + id + '"]');
                            if (qtyEl) qtyEl.textContent = data.quantity;
                            const subEl = document.querySelector('.item-subtotal[data-id="' + id + '"]');
                            if (subEl) subEl.textContent = '₱' + data.item_subtotal;
                        }
                        // update total in summary
                        document.querySelectorAll('.text-2xl.font-playfair.font-bold.text-amber-300, .text-white.font-medium').forEach(function(el){
                            if (el.textContent.trim().startsWith('₱')) {
                                el.textContent = '₱' + data.total;
                            }
                        });
                    }).catch(()=> alert('Failed to update cart')); 
                });
            });

            // Handle remove forms
            document.querySelectorAll('.cart-remove-form').forEach(function(form){
                form.addEventListener('submit', function(e){
                    e.preventDefault();
                    const fd = new FormData(form);
                    // respect _method=DELETE by sending POST with _method
                    fetch(form.action, {
                        method: 'POST',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' },
                        body: fd
                    }).then(r => r.json()).then(data => {
                        if (!data.success) { alert(data.message || 'Failed to remove item'); return; }
                        const id = fd.get('id');
                        const row = document.getElementById('cart-row-' + id);
                        if (row) row.remove();
                        // update total in summary
                        document.querySelectorAll('.text-2xl.font-playfair.font-bold.text-amber-300, .text-white.font-medium').forEach(function(el){
                            if (el.textContent.trim().startsWith('₱')) {
                                el.textContent = '₱' + data.total;
                            }
                        });
                    }).catch(()=> alert('Failed to remove item'));
                });
            });
        })();
    </script>

    

</body>
</html>
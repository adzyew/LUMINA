<footer id="contact" class="bg-amber-50 border-t border-amber-200/50 pt-16 pb-8">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">

            <div class="space-y-4">
                <div class="flex items-center">
                    <img src="{{ asset('IMAGES/Lumina (1).svg') }}" alt="Lumina" class="h-10 w-auto">
                </div>
                <p class="text-amber-900/80 text-sm leading-relaxed max-w-xs">
                    Crafting dreams into reality, one jewel at a time. Experience the difference of true luxury with our handcrafted collections.
                </p>

            </div>

            <div>
                <h4 class="font-bold text-amber-900 mb-6 uppercase tracking-wider text-sm">Quick Links</h4>
                <ul class="space-y-3 text-amber-900/80 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:text-amber-600 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-amber-500 rounded-full"></span>Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-amber-600 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-amber-500 rounded-full"></span>Collections</a></li>
                    <li><a href="{{ url('/#features') }}" class="hover:text-amber-600 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-amber-500 rounded-full"></span>About Us</a></li>
                    <li><a href="{{ url('/#contact') }}" class="hover:text-amber-600 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-amber-500 rounded-full"></span>Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-amber-900 mb-6 uppercase tracking-wider text-sm">Customer Care</h4>
                <ul class="space-y-3 text-amber-900/80 text-sm">
                    <li><a href="{{ route('legal.shipping') }}" class="hover:text-amber-600 transition-colors">Shipping Information</a></li>
                    <li><a href="{{ route('legal.returns') }}" class="hover:text-amber-600 transition-colors">Returns & Exchange</a></li>
                    <li><a href="{{ route('legal.warranty') }}" class="hover:text-amber-600 transition-colors">Lifetime Warranty</a></li>
                    <li><a href="{{ route('legal.privacy') }}" class="hover:text-amber-600 transition-colors">Privacy Policy</a></li>
                    <li><a href="{{ route('legal.terms') }}" class="hover:text-amber-600 transition-colors">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <div class=" mt-8 pt-8 border-t border-amber-200/50 flex flex-col sm:flex-row justify-center items-center gap-4">
            <p class="text-amber-900/70 text-sm text-center justify-center">&copy; {{ date('Y') }} Lumina Jewelry Accessories. All rights reserved.</p>
        </div>
    </div>
</footer>

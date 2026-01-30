<footer id="contact" class="bg-black border-t border-white/10 pt-16 pb-8">
    <div class="container mx-auto px-4 sm:px-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-12">
            
            <div class="space-y-4">
                <div class="flex items-center space-x-2">
                    <div class="flex space-x-1">
                        <div class="w-2 h-6 bg-amber-300 rounded-full transform -rotate-12"></div>
                        <div class="w-2 h-6 bg-amber-300 rounded-full"></div>
                        <div class="w-2 h-6 bg-amber-300 rounded-full transform rotate-12"></div>
                    </div>
                    <span class="font-playfair font-black text-2xl text-white tracking-wide">Lumina</span>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed max-w-xs">
                    Crafting dreams into reality, one jewel at a time. Experience the difference of true luxury with our handcrafted collections.
                </p>
                <div class="flex space-x-4 pt-2">
                    <a href="#" class="text-gray-500 hover:text-amber-300 transition-colors"><span class="sr-only">Facebook</span><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg></a>
                    <a href="#" class="text-gray-500 hover:text-amber-300 transition-colors"><span class="sr-only">Instagram</span><svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772 4.902 4.902 0 011.772-1.153c.636-.247 1.363-.416 2.427-.465C9.673 2.013 10.03 2 12.315 2zm-2.008 2H12.316c2.28 0 2.604.01 3.491.05.817.038 1.259.18 1.554.295.39.15.668.33.96.622.292.292.472.57.622.96.115.295.257.737.295 1.554.04.887.051 1.21.051 3.491v.418c0 2.28-.01 2.604-.05 3.491-.039.817-.181 1.259-.296 1.554a2.934 2.934 0 01-.622.96 2.934 2.934 0 01-.96.622c-.295.115-.737.257-1.554.296-.887.04-1.21.05-3.491.05h-.418c-2.28 0-2.604-.01-3.491-.05-.817-.039-1.259-.181-1.554-.296a2.934 2.934 0 01-.96-.622 2.934 2.934 0 01-.622-.96c-.115-.295-.257-.737-.296-1.554-.04-.887-.05-1.21-.05-3.491v-.418c0-2.28.01-2.604.05-3.491.039-.817.181-1.259.296-1.554.15-.39.33-.668.622-.96.292-.292.57-.472.96-.622.295-.115.737-.257 1.554-.296.886-.04 1.209-.05 3.49-.05zm-1.008 4a5.008 5.008 0 015.008 5.008 5.008 5.008 0 01-5.008 5.008 5.008 5.008 0 01-5.008-5.008 5.008 5.008 0 015.008-5.008zm0 2a3.008 3.008 0 00-3.008 3.008 3.008 3.008 0 003.008 3.008 3.008 3.008 0 003.008-3.008 3.008 3.008 0 00-3.008-3.008z" clip-rule="evenodd" /></svg></a>
                </div>
            </div>

            <div>
                <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">Quick Links</h4>
                <ul class="space-y-3 text-gray-400 text-sm">
                    <li><a href="{{ url('/') }}" class="hover:text-amber-300 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-amber-500 rounded-full"></span>Home</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-amber-300 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-amber-500 rounded-full"></span>Collections</a></li>
                    <li><a href="{{ url('/#features') }}" class="hover:text-amber-300 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-amber-500 rounded-full"></span>About Us</a></li>
                    <li><a href="{{ url('/#contact') }}" class="hover:text-amber-300 transition-colors flex items-center gap-2"><span class="w-1 h-1 bg-amber-500 rounded-full"></span>Contact</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">Customer Care</h4>
                <ul class="space-y-3 text-gray-400 text-sm">
                    <li><a href="#" class="hover:text-amber-300 transition-colors">Shipping Information</a></li>
                    <li><a href="#" class="hover:text-amber-300 transition-colors">Returns & Exchange</a></li>
                    <li><a href="#" class="hover:text-amber-300 transition-colors">Lifetime Warranty</a></li>
                    <li><a href="#" class="hover:text-amber-300 transition-colors">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-amber-300 transition-colors">Terms of Service</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-bold text-white mb-6 uppercase tracking-wider text-sm">Stay Updated</h4>
                <p class="text-gray-400 text-sm mb-4">Subscribe to receive updates, access to exclusive deals, and more.</p>
                <form class="flex flex-col gap-3">
                    <div class="relative">
                        <input type="email" placeholder="Enter your email" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-lg focus:outline-none focus:border-amber-300 text-sm text-white transition-colors placeholder-gray-600">
                    </div>
                    <button type="button" class="w-full px-4 py-3 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-all text-sm uppercase tracking-wide">
                        Subscribe
                    </button>
                </form>
            </div>
        </div>

        <div class="mt-16 pt-8 border-t border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-gray-600 text-xs text-center sm:text-left">&copy; {{ date('Y') }} Lumina Jewelry Accessories. All rights reserved.</p>
            <div class="flex space-x-4">
                <div class="h-6 w-10 bg-white/5 rounded flex items-center justify-center text-xs text-gray-500">VISA</div>
                <div class="h-6 w-10 bg-white/5 rounded flex items-center justify-center text-xs text-gray-500">MC</div>
                <div class="h-6 w-10 bg-white/5 rounded flex items-center justify-center text-xs text-gray-500">PAYPAL</div>
            </div>
        </div>
    </div>
</footer>
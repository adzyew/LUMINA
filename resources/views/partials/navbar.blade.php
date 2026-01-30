<nav class="fixed top-0 left-0 right-0 z-50 bg-black/80 backdrop-blur-md border-b border-white/10 transition-all duration-300">
    <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4">
        <div class="flex items-center justify-between">
            
            <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                <div class="flex space-x-1">
                    <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full transform -rotate-12 group-hover:-translate-y-1 transition-transform"></div>
                    <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full group-hover:-translate-y-1 transition-transform delay-75"></div>
                    <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full transform rotate-12 group-hover:-translate-y-1 transition-transform delay-150"></div>
                </div>
                <span class="font-playfair font-black text-2xl sm:text-3xl tracking-wide text-white group-hover:text-amber-300 transition-colors">
                    Lumina
                </span>
            </a>

            <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                <a href="{{ url('/') }}" class="text-sm lg:text-base font-medium text-gray-300 hover:text-amber-300 transition-colors">
                    Home
                </a>
                <a href="{{ route('products.index') }}" class="text-sm lg:text-base font-medium text-gray-300 hover:text-amber-300 transition-colors">
                    Collections
                </a>
                <a href="{{ url('/#features') }}" class="text-sm lg:text-base font-medium text-gray-300 hover:text-amber-300 transition-colors">
                    About
                </a>
                <a href="{{ url('/#contact') }}" class="text-sm lg:text-base font-medium text-gray-300 hover:text-amber-300 transition-colors">
                    Contact
                </a>
            </div>

            <div class="flex items-center space-x-4 sm:space-x-6">
                
                <button class="hidden sm:block text-gray-300 hover:text-amber-300 transition-colors">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </button>
                
                <a href="{{ route('cart.index') }}" class="relative text-gray-300 hover:text-amber-300 transition-colors group">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-1 -right-1 sm:-top-2 sm:-right-2 bg-amber-300 text-black text-[10px] sm:text-xs rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center font-bold animate-pulse">
                            {{ count(session('cart')) }}
                        </span>
                    @endif
                </a>

                <div class="hidden md:block h-6 w-px bg-gray-700"></div>

                <div class="hidden md:flex items-center space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="flex items-center gap-2 text-amber-300 font-medium hover:text-white transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <span>{{ Auth::user()->name }}</span>
                        </a>
                    @else
                        <button onclick="openAuthModal('login')" class="text-sm font-medium text-gray-300 hover:text-amber-300 transition-colors">
                            Login
                        </button>
                        <button onclick="openAuthModal('register')" class="px-5 py-2 bg-amber-300 text-black text-sm font-bold rounded-lg hover:bg-amber-400 hover:shadow-[0_0_15px_rgba(251,191,36,0.4)] transition-all">
                            Sign Up
                        </button>
                    @endauth
                </div>
                
                <button class="md:hidden text-gray-300 hover:text-amber-300 transition-colors p-2" onclick="toggleMobileMenu()">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
        
        <div id="mobileMenu" class="hidden md:hidden mt-4 pt-4 border-t border-gray-800 bg-black/95 absolute left-0 right-0 px-6 pb-6 shadow-2xl">
            <div class="flex flex-col space-y-4">
                <a href="{{ url('/') }}" class="text-lg text-gray-300 hover:text-amber-300 transition-colors border-b border-gray-800 pb-2">Home</a>
                <a href="{{ route('products.index') }}" class="text-lg text-gray-300 hover:text-amber-300 transition-colors border-b border-gray-800 pb-2">Collections</a>
                <a href="{{ url('/#features') }}" class="text-lg text-gray-300 hover:text-amber-300 transition-colors border-b border-gray-800 pb-2">About</a>
                <a href="{{ url('/#contact') }}" class="text-lg text-gray-300 hover:text-amber-300 transition-colors border-b border-gray-800 pb-2">Contact</a>
                
                <div class="flex flex-col gap-3 pt-2">
                     @auth
                        <a href="{{ url('/dashboard') }}" class="text-amber-300 font-bold">My Account</a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-400 hover:text-white text-sm">Log Out</button>
                        </form>
                    @else
                        <button onclick="openAuthModal('login'); toggleMobileMenu()" class="text-left text-gray-300 hover:text-amber-300 py-2">Login</button>
                        <button onclick="openAuthModal('register'); toggleMobileMenu()" class="w-full py-3 bg-amber-300 text-black font-bold rounded-lg">Sign Up</button>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }
</script>
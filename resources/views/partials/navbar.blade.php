<nav class="{{ ($authPage ?? false) ? 'fixed top-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-md border-b border-gray-200 text-gray-900' : (($welcomeLayout ?? false) ? 'w-full bg-white/95 backdrop-blur-md border-b border-gray-200 text-gray-900' : 'fixed top-0 left-0 right-0 z-50 bg-white/95 dark:bg-black/95 backdrop-blur-md border-b border-gray-200 dark:border-amber-300 text-gray-900 dark:text-white') }} transition-colors">
        <div class="container mx-auto px-4 sm:px-6 py-3 sm:py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
            <a href="{{ url('/') }}" class="flex items-center space-x-2 group">
                    <div class="flex space-x-1">
                        <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full transform -rotate-12"></div>
                        <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full"></div>
                        <div class="w-2 h-6 sm:w-3 sm:h-8 bg-amber-300 rounded-full transform rotate-12"></div>
                    </div>
                <span class="font-serif font-black text-2xl sm:text-3xl text-inherit">Lumina</span>
            </a>

                <!-- Navigation Links - Desktop -->
                <div class="hidden md:flex items-center space-x-6 lg:space-x-8">
                <a href="{{ url('/') }}" class="text-inherit hover:text-amber-500 transition-colors duration-300 lg:text-lg font-sans font-semibold">Home</a>
                <a href="{{ route('products.index') }}" class="text-inherit hover:text-amber-500 transition-colors duration-300 lg:text-lg font-sans font-semibold">Collections</a>
                <a href="{{ url('/#features') }}" class="text-inherit hover:text-amber-500 transition-colors duration-300 lg:text-lg font-sans font-semibold">About</a>
                <a href="{{ url('/#contact') }}" class="text-inherit hover:text-amber-500 transition-colors duration-300 lg:text-lg font-sans font-semibold">Contact</a>
                </div>

            <!-- Icons & Actions - Desktop -->
            <div class="flex items-center gap-2 sm:gap-4">
                @if(!($authPage ?? false))
                    @include('partials.theme_toggle')
                @endif
                

                {{-- User dropdown (auth) or Login/Sign Up (guest) --}}
                

                @auth
                    <a href="{{ route('wishlist.index') }}" class="relative p-2 rounded-lg text-gray-500 dark:text-gray-300 hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors group">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </a>
                @endauth
                <a href="{{ route('cart.index') }}" class="relative p-2 rounded-lg text-gray-500 dark:text-gray-300 hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors group">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        @if(session('cart') && count(session('cart')) > 0)
                        <span class="absolute -top-0.5 -right-0.5 sm:top-0 sm:right-0 bg-amber-300 text-black text-[10px] sm:text-xs rounded-full min-w-[18px] h-[18px] flex items-center justify-center font-bold">
                                {{ count(session('cart')) }}
                            </span>
                        @endif
                    </a>
                @auth
                    @if(auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false) || auth()->user()->can('inventory.view') || auth()->user()->can('sales.view') || auth()->user()->can('deliveries.manage'))
                        @php
                            $adminLink = (auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false)) ? route('admin.admin_dashboard') : route('admin.staff.dashboard');
                        @endphp
                        <a href="{{ $adminLink }}" class="hidden md:inline-flex px-4 py-2 text-sm font-semibold text-amber-500 hover:text-amber-400 transition-colors">
                            {{ auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false) ? 'Admin Panel' : 'Staff Panel' }}
                        </a>
                    @endif
                    <div class="relative" id="userDropdownWrapper">
                        <button type="button" onclick="toggleUserDropdown()" class="flex items-center gap-2 p-2 rounded-lg text-gray-500 dark:text-gray-300 hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors" aria-expanded="false" aria-haspopup="true" id="userMenuButton">
                            <span class="hidden sm:inline text-sm font-medium text-inherit">{{ auth()->user()->name }}</span>
                            @if(auth()->user()->profile_photo_url ?? null)
                                <img src="{{ auth()->user()->profile_photo_url }}" alt="" class="w-8 h-8 rounded-full object-cover border border-amber-300/30">
                            @else
                                <div class="w-8 h-8 rounded-full bg-amber-300 flex items-center justify-center text-black font-bold text-sm">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                    </button>
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-56 rounded-xl bg-white dark:bg-gray-900 shadow-xl border border-gray-200 dark:border-white/10 py-1 z-50 transition-opacity duration-150">
                            <div class="px-4 py-3 border-b border-gray-100 dark:border-white/10">
                                <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                View Profile
                            </a>
                            <a href="{{ route('dashboard') }}#orders" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                Orders
                            </a>
                            <a href="{{ route('profile.edit') }}#settings" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-white/5 transition-colors">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Settings
                            </a>
                            <div class="border-t border-gray-100 dark:border-white/10 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" >
                                @csrf
                                <button type="button" onclick="showLogoutModal()" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    @if(!($authPage ?? false))
                        <a href="{{ route('login') }}" class="hidden md:inline-flex px-4 py-2 text-base font-semibold text-inherit hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 transition-colors border s rounded-lg hover:bg-amber-400 ">Log In</a>
                        <a href="{{ route('register.form') }}" class="hidden md:inline-flex px-4 py-2 bg-amber-300 text-black font-bold rounded-lg hover:bg-amber-400 transition-colors text-base">Sign Up</a>
                    @endif
                @endauth
        
                {{-- Mobile menu button --}}
                <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg text-gray-500 dark:text-gray-300 hover:text-amber-500" aria-label="Menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="hidden md:hidden mt-4 pt-4 border-t border-gray-200 dark:border-white/10">
            <div class="flex flex-col space-y-1">
                <a href="{{ url('/') }}" class="px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">Home</a>
                <a href="{{ route('products.index') }}" class="px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">Collections</a>
                <a href="{{ url('/#features') }}" class="px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">About</a>
                <a href="{{ url('/#contact') }}" class="px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">Contact</a>
                <div class="pt-3 mt-3 border-t border-gray-200 dark:border-white/10 space-y-1">
                 @auth   
                        @if(auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false) || auth()->user()->can('inventory.view') || auth()->user()->can('sales.view') || auth()->user()->can('deliveries.manage'))
                            @php
                                $adminLinkMobile = (auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false)) ? route('admin.admin_dashboard') : route('admin.staff.dashboard');
                                $adminLabel = auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false) ? 'Admin Panel' : 'Staff Panel';
                            @endphp
                            <a href="{{ $adminLinkMobile }}" class="flex items-center gap-3 px-4 py-3 text-amber-500 hover:bg-amber-500/10 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                {{ $adminLabel }}
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">View Profile</a>
                        <a href="{{ route('dashboard') }}#orders" class="flex items-center gap-3 px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">Orders</a>
                        <a href="{{ route('dashboard') }}#settings" class="flex items-center gap-3 px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 dark:hover:bg-white/5 rounded-lg transition-colors">Settings</a>
                            <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Are you sure you want to logout?');">
                                @csrf
                                <button type="button" onclick="showLogoutModal()" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Logout
                                </button>
                            </form>
                    @else
                        @if(!($authPage ?? false))
                            <a href="{{ route('login') }}" class="block px-4 py-3 text-amber-500 font-medium hover:bg-amber-500/10 rounded-lg transition-colors">Log In</a>
                            <a href="{{ route('register.form') }}" class="block px-4 py-3 bg-amber-300 text-black font-bold rounded-lg text-center">Sign Up</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
    </div>
</nav>
<div id="logoutModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="hideLogoutModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white dark:bg-gray-900 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-200 dark:border-white/10">
                <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-500/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900 dark:text-white" id="modal-title">Confirm Logout</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">Are you sure you want to log out of your account?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-800/50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <form method="POST" action="{{ route('logout') }}" class="m-0">
                        @csrf
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors">
                            Confirm
                        </button>
                    </form>
                    <button type="button" onclick="hideLogoutModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white dark:bg-gray-800 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleMobileMenu() {
    document.getElementById('mobileMenu').classList.toggle('hidden');
}
function toggleUserDropdown() {
    var d = document.getElementById('userDropdown');
    if (d) d.classList.toggle('hidden');
}
document.addEventListener('click', function(e) {
    var w = document.getElementById('userDropdownWrapper');
    var d = document.getElementById('userDropdown');
    if (w && d && !w.contains(e.target)) d.classList.add('hidden');

function showLogoutModal() {
    const modal = document.getElementById('logoutModal');
    modal.classList.remove('hidden');
    }

function hideLogoutModal() {
    const modal = document.getElementById('logoutModal');
    modal.classList.add('hidden');
    }
});


            function showLogoutModal() {
                const modal = document.getElementById('logoutModal');
                modal.classList.remove('hidden');
            }

            function hideLogoutModal() {
                const modal = document.getElementById('logoutModal');
                modal.classList.add('hidden');
            }

</script>

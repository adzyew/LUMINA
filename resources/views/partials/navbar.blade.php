<nav id="mainNavbar" class="{{ ($authPage ?? false) ? 'fixed top-0 left-0 right-0 z-50 bg-amber-50  backdrop-blur-md border-b border-amber-300 text-gray-900' : (($welcomeLayout ?? false) ? 'w-full bg-amber-50 backdrop-blur-md border-b border-amber-300 text-gray-900' : 'fixed top-0 left-0 right-0 z-50 bg-amber-50 backdrop-blur-md border-b border-amber-300 text-gray-900') }} transition-colors duration-300">
        <div class="navbar-shell container mx-auto px-4 sm:px-6 py-3 sm:py-4 transition-all duration-300">
            
            <div class="flex items-center relative {{ ($authPage ?? false) ? 'justify-center' : 'justify-between' }}">
                
                <a href="{{ url('/') }}" class="flex items-center">
                    <img src="{{ asset('IMAGES/Lumina (1).svg') }}" alt="Lumina" class="navbar-logo h-10 sm:h-11 w-auto origin-left scale-[2.30] transition-transform duration-300">
                </a>

                @if(!($authPage ?? false))
                <div class="hidden md:flex absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 justify-center items-center gap-8 navbar-links">
                    <a href="{{ url('/') }}" data-nav-target="home" class="nav-link text-gray-900 hover:text-amber-500 transition-colors duration-300 xl:text-xl font-sans font-semibold">Home</a>
                    <a href="{{ route('products.index') }}" data-nav-target="collections" class="nav-link text-gray-900 hover:text-amber-500 transition-colors duration-300 xl:text-xl font-sans font-semibold">Collections</a>
                    <a href="{{ url('/#about') }}" data-nav-target="about" class="nav-link text-gray-900 hover:text-amber-500 transition-colors duration-300 xl:text-xl font-sans font-semibold">About</a>
                    <a href="{{ url('/#contact') }}" data-nav-target="contact" class="nav-link text-gray-900 hover:text-amber-500 transition-colors duration-300 xl:text-xl font-sans font-semibold">Contact</a>
                </div>

            <div class="flex items-center justify-end gap-4 navbar-actions">

                {{-- User dropdown (auth) or Login/Sign Up (guest) --}}
                @auth
                    <a href="{{ route('wishlist.storefront') }}" class="relative p-2 rounded-lg text-amber-400 hover:text-amber-500 transition-colors group" aria-label="Wishlist">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 transform group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </a>
                @endauth
                @if(!request()->routeIs('login')&& !request()->routeIs('register'))
                <a href="{{ route('cart.index') }}" class="relative p-2 rounded-lg text-amber-400 hover:text-amber-500 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 sm:w-7 sm:h-7 transform group-hover:scale-110 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                        @php
                            $navbarCartCount = 0;
                            if (session('cart') && is_array(session('cart'))) {
                                foreach (session('cart') as $cartItem) {
                                    $navbarCartCount += (int) ($cartItem['quantity'] ?? 0);
                                }
                            }
                        @endphp
                        <span id="navbarCartCount" class="absolute -top-3 -right-0.5 sm:top-0 sm:right-0 bg-amber-300 text-black text-[10px] sm:text-xs rounded-full min-w-4.5 h-4.5 grid grid-cols-1 justify-items-center font-bold {{ $navbarCartCount > 0 ? '' : 'hidden' }}">
                            {{ $navbarCartCount }}
                        </span>
                    </a>
                @endif
                @auth
                    @if(auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false) || auth()->user()->can('inventory.view') || auth()->user()->can('sales.view') || auth()->user()->can('deliveries.manage') || auth()->user()->can('reviews.moderate'))
                        @php
                            $adminLink = (auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false)) ? route('admin.admin_dashboard') : route('admin.staff.dashboard');
                        @endphp
                        <a href="{{ $adminLink }}" class="hidden md:inline-flex px-4 py-2 text-sm font-semibold text-amber-500 hover:text-amber-400 transition-colors">
                            {{ auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false) ? 'Admin Panel' : 'Staff Panel' }}
                        </a>
                    @endif
                    <div class="relative" id="userDropdownWrapper">
                        <button type="button" onclick="toggleUserDropdown()" class="flex items-center gap-2 p-2 rounded-lg text-gray-500 hover:text-amber-500 hover:bg-gray-100 transition-colors" aria-expanded="false" aria-haspopup="true" id="userMenuButton">
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
                        <div id="userDropdown" class="hidden absolute right-0 mt-2 w-56 rounded-xl bg-white shadow-xl border border-gray-200 py-1 z-50 transition-opacity duration-150">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                View Profile
                            </a>
                            <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                Orders
                            </a>
                            <a href="{{ route('wishlist.storefront') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                Wishlist
                            </a>
                            <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                Settings
                            </a>
                            <div class="border-t border-gray-100 my-1"></div>
                            <form method="POST" action="{{ route('logout') }}" data-logout-form>
                                @csrf
                                <button type="button" onclick="showLogoutModal()" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    @if(!($authPage ?? false))
                        <a href="{{ route('login') }}" class="hidden md:inline-flex px-4 py-2 text-base font-semibold text-amber-500 hover:text-amber-600 hover:bg-amber-600/10transition-colors border border-amber-500 rounded-lg transform hover:scale-105">Log In</a>
                        <a href="{{ route('register.form') }}" class="hidden md:inline-flex px-4 py-2 bg-amber-400 text-white font-bold rounded-lg hover:bg-amber-500 hover:text-white transition-colors text-base transform hover:scale-105">Sign Up</a>
                    @endif
                @endauth

                {{-- Mobile menu button --}}
                <button type="button" onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg text-amber-400 hover:text-amber-500" aria-label="Menu">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                    </svg>
                </button>
            </div>
            @endif
        </div>

        @if(!($authPage ?? false))
        {{-- Mobile Menu --}}
        <div id="mobileMenu" class="hidden md:hidden mt-4 pt-4 border-t border-gray-200">
            <div class="flex flex-col space-y-1">
                <a href="{{ url('/') }}" data-nav-target="home" class="nav-link px-4 py-3 text-gray-900 hover:text-amber-500 hover:bg-gray-100 rounded-lg transition-colors">Home</a>
                <a href="{{ route('products.index') }}" data-nav-target="collections" class="nav-link px-4 py-3 text-gray-900 hover:text-amber-500 hover:bg-gray-100 rounded-lg transition-colors">Collections</a>
                <a href="{{ url('/#about') }}" data-nav-target="about" class="nav-link px-4 py-3 text-gray-900 hover:text-amber-500 hover:bg-gray-100 rounded-lg transition-colors">About</a>
                <a href="{{ url('/#contact') }}" data-nav-target="contact" class="nav-link px-4 py-3 text-gray-900 hover:text-amber-500 hover:bg-gray-100 rounded-lg transition-colors">Contact</a>
                <div class="pt-3 mt-3 border-t border-gray-200 space-y-1">
                 @auth
                        @if(auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false) || auth()->user()->can('inventory.view') || auth()->user()->can('sales.view') || auth()->user()->can('deliveries.manage') || auth()->user()->can('reviews.moderate'))
                            @php
                                $adminLinkMobile = (auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false)) ? route('admin.admin_dashboard') : route('admin.staff.dashboard');
                                $adminLabel = auth()->user()->hasRole('admin') || (auth()->user()->is_admin ?? false) ? 'Admin Panel' : 'Staff Panel';
                            @endphp
                            <a href="{{ $adminLinkMobile }}" class="flex items-center gap-3 px-4 py-3 text-amber-500 hover:bg-amber-500/10 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                {{ $adminLabel }}
                            </a>
                        @endif
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 rounded-lg transition-colors">View Profile</a>
                        <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 rounded-lg transition-colors">Orders</a>
                        <a href="{{ route('wishlist.storefront') }}" class="flex items-center gap-3 px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 rounded-lg transition-colors">Wishlist</a>
                        <a href="{{ route('profile.show') }}" class="flex items-center gap-3 px-4 py-3 text-inherit hover:text-amber-500 hover:bg-gray-100 rounded-lg transition-colors">Settings</a>
                            <form method="POST" action="{{ route('logout') }}" data-logout-form>
                                @csrf
                                <button type="button" onclick="showLogoutModal()" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                    Logout
                                </button>
                            </form>
                    @else
                        @if(!($authPage ?? false))
                            <a href="{{ route('login') }}" class="block px-4 py-3 text-center text-base font-semibold text-amber-500 border border-amber-400 rounded-lg hover:text-amber-400 hover:bg-gray-100 transition-colors">Log In</a>
                            <a href="{{ route('register.form') }}" class="block px-4 py-3 bg-amber-300 text-black font-bold rounded-lg text-center">Sign Up</a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>
        @endif
    </div>
</nav>
<div id="logoutModal" class="fixed inset-0 z-[100] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" onclick="hideLogoutModal()"></div>

    <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm border border-gray-200">
                <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-semibold leading-6 text-gray-900" id="modal-title">Confirm Logout</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">Are you sure you want to log out of your account?</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <form method="POST" action="{{ route('logout') }}" class="m-0" data-logout-form>
                        @csrf
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors disabled:opacity-70 disabled:cursor-not-allowed">
                            Confirm
                        </button>
                    </form>
                    <button type="button" onclick="hideLogoutModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setActiveNavLink(target) {
        const links = document.querySelectorAll('[data-nav-target]');

        links.forEach((link) => {
            const isActive = link.dataset.navTarget === target;
            link.classList.toggle('!text-amber-500', isActive);
            link.classList.toggle('font-bold', isActive);

            const inMobileMenu = !!link.closest('#mobileMenu');
            if (inMobileMenu) {
                link.classList.toggle('bg-amber-500/10', isActive);
            }

            if (isActive) {
                link.setAttribute('aria-current', 'page');
            } else {
                link.removeAttribute('aria-current');
            }
        });
    }

    function updateActiveNav() {
        const path = window.location.pathname.replace(/\/+$/, '') || '/';
        const hash = window.location.hash.replace('#', '');
        const isHomePage = path === '/' || path === '/home';

        if (path.startsWith('/products') || path.startsWith('/collection')) {
            setActiveNavLink('collections');
            return;
        }

        if (!isHomePage) {
            setActiveNavLink('');
            return;
        }

        if (hash === 'about' || hash === 'contact') {
            setActiveNavLink(hash);
            return;
        }

        const about = document.getElementById('about');
        const contact = document.getElementById('contact');
        const triggerLine = window.innerHeight * 0.35;

        if (contact) {
            const contactTop = contact.getBoundingClientRect().top;
            if (contactTop <= triggerLine) {
                setActiveNavLink('contact');
                return;
            }
        }

        if (about) {
            const aboutTop = about.getBoundingClientRect().top;
            if (aboutTop <= triggerLine) {
                setActiveNavLink('about');
                return;
            }
        }

        setActiveNavLink('home');
    }

    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        if (menu) menu.classList.toggle('hidden');
    }

    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        if (dropdown) dropdown.classList.toggle('hidden');
    }

    function showLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) modal.classList.remove('hidden');
    }

    function hideLogoutModal() {
        const modal = document.getElementById('logoutModal');
        if (modal) modal.classList.add('hidden');
    }

    document.addEventListener('click', function (e) {
        const wrapper = document.getElementById('userDropdownWrapper');
        const dropdown = document.getElementById('userDropdown');
        if (wrapper && dropdown && !wrapper.contains(e.target)) {
            dropdown.classList.add('hidden');
        }
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            const menu = document.getElementById('mobileMenu');
            if (menu) menu.classList.add('hidden');
        }
    });

    window.addEventListener('hashchange', updateActiveNav);
    window.addEventListener('scroll', updateActiveNav, { passive: true });

    document.addEventListener('DOMContentLoaded', function () {
        updateActiveNav();

        document.querySelectorAll('form[data-logout-form]').forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (form.dataset.submitting === '1') {
                    event.preventDefault();
                    return;
                }

                form.dataset.submitting = '1';
                form.querySelectorAll('button[type=\"submit\"], input[type=\"submit\"]').forEach(function (submitter) {
                    submitter.disabled = true;
                    if (submitter.tagName === 'BUTTON') {
                        submitter.dataset.originalText = submitter.textContent;
                        submitter.textContent = 'Logging out...';
                    }
                });
            });
        });
    });

</script>

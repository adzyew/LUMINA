<div class="mb-10 bg-white border border-gray-200 rounded-2xl px-6 py-3 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <header class="flex items-center gap-3 min-w-0">
    @include('partials.favicon')
            <h1 class="text-3xl font-playfair font-bold text-gray-900">{{ $title ?? 'Overview' }}</h1>
            @if(!empty($subtitle))
                <p class="text-gray-600 text-sm mt-3 truncate">{{ $subtitle }}</p>
            @endif
        </header>

        <div class="relative" id="staffProfileDropdownWrapper">
            <button type="button" onclick="toggleStaffProfileDropdown()" class="flex items-center gap-3 cursor-pointer">
                <div class="w-9 h-9 rounded-full bg-amber-400 flex items-center justify-center text-black font-bold text-sm shrink-0">
                    {{ strtoupper(substr(Auth::user()->name ?? 'S', 0, 1)) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-gray-800 leading-tight">{{ Auth::user()->name ?? 'Staff' }}</p>
                    <p class="text-xs text-gray-400 leading-tight">Staff</p>
                </div>
                <svg id="staffProfileChevron" class="w-4 h-4 text-gray-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            <div id="staffProfileDropdownMenu" class="absolute right-0 mt-3 w-52 bg-white border border-gray-200 rounded-2xl shadow-xl z-50 overflow-hidden opacity-0 scale-95 pointer-events-none transition-all duration-150 ease-out origin-top-right">
                <div class="px-4 py-3 border-b border-gray-100">
                    <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name ?? 'Staff' }}</p>
                    <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email ?? '' }}</p>
                </div>
                <div class="py-1">
                    <a href="{{ route('admin.profile.show') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        My Profile
                    </a>
                    <button type="button" onclick="showLogoutModal(); closeStaffProfileDropdown();" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                        Logout
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

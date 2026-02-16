{{-- Theme toggle button: sun in dark mode (switch to light), moon in light mode (switch to dark) --}}
<button type="button" onclick="toggleTheme()" class="p-2 rounded-lg text-gray-400 hover:text-amber-300 hover:bg-white/5 transition-colors" aria-label="Toggle dark/light theme">
    <span class="dark:hidden" aria-hidden="true">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
    </span>
    <span class="hidden dark:inline" aria-hidden="true">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    </span>
</button>

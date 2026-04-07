@if(session('toast_message'))
    @php
        $toastType = session('toast_type', 'success');
        $isError = $toastType === 'error';
    @endphp

    <div
        id="globalToast"
        class="fixed top-6 left-1/2 z-[300] hidden -translate-x-1/2 items-center gap-3 rounded-xl border px-5 py-3.5 text-sm font-medium shadow-lg transition-all duration-300 {{ $isError ? 'bg-red-50 border-red-200 text-red-800' : 'bg-green-100 border-green-200 text-green-800' }}"
        role="status"
        aria-live="polite"
    >
        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            @if($isError)
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m7.938 4H4.062c-1.54 0-2.502-1.667-1.732-3L10.268 4c.77-1.333 2.694-1.333 3.464 0l7.938 12c.77 1.333-.192 3-1.732 3Z" />
            @else
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12.75 11.25 15 15 9.75m6 2.25a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            @endif
        </svg>
        <span>{{ session('toast_message') }}</span>
    </div>

    <script>
        (function () {
            const toast = document.getElementById('globalToast');
            if (!toast) return;

            toast.classList.remove('hidden');
            toast.classList.add('flex');

            window.setTimeout(function () {
                toast.classList.add('hidden');
                toast.classList.remove('flex');
            }, 3500);
        })();
    </script>
@endif

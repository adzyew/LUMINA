@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="w-full">
        <div class="mx-auto flex w-full justify-center  rounded-xl  p-2">
        <div class="inline-flex items-stretch overflow-hidden rounded-xl gap-5 p-1 ">
            <p class="text-sm font-medium text-black self-center">
                Showing <b>{{ $paginator->firstItem() }}</b> to <b>{{ $paginator->lastItem() }}</b> of <b>{{ $paginator->total() }}</b> results
            </p>
            @if ($paginator->onFirstPage())
                <span class="inline-flex h-12 min-w-12 items-center justify-center rounded-lg border border-transparent px-4 text-amber-800 bg-white" aria-disabled="true" aria-label="{{ __('Previous') }}">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.78 15.53a.75.75 0 0 1-1.06 0l-4-4a.75.75 0 0 1 0-1.06l4-4a.75.75 0 1 1 1.06 1.06L9.31 11l3.47 3.47a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex h-12 min-w-12 items-center justify-center rounded-lg border border-transparent px-4 text-amber-800 transition bg-white transform hover:scale-105" aria-label="{{ __('Previous') }}">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.78 15.53a.75.75 0 0 1-1.06 0l-4-4a.75.75 0 0 1 0-1.06l4-4a.75.75 0 1 1 1.06 1.06L9.31 11l3.47 3.47a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="inline-flex h-12 min-w-12 items-center justify-center px-4 text-sm font-semibold text-amber-400 bg-white">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="inline-flex h-12 min-w-12 items-center justify-center rounded-lg border border-amber-800 bg-white px-5 text-xl font-black text-amber-800 shadow-[inset_0_0_0_1px_rgba(99,102,241,0.25)]">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="inline-flex h-12 min-w-12 items-center justify-center rounded-lg border border-transparent px-5 text-xl font-semibold text-amber-400 transition bg-white transform hover:scale-105" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex h-12 min-w-12 items-center justify-center rounded-lg border border-transparent px-4 text-amber-800 transition bg-white transform hover:scale-105" aria-label="{{ __('Next') }}">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.22 4.47a.75.75 0 0 1 1.06 0l4 4a.75.75 0 0 1 0 1.06l-4 4a.75.75 0 1 1-1.06-1.06L10.69 9 7.22 5.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="inline-flex h-12 min-w-12 items-center justify-center rounded-lg border border-transparent px-4 text-amber-500 bg-white " aria-disabled="true" aria-label="{{ __('Next') }}">
                    <svg viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5" aria-hidden="true">
                        <path fill-rule="evenodd" d="M7.22 4.47a.75.75 0 0 1 1.06 0l4 4a.75.75 0 0 1 0 1.06l-4 4a.75.75 0 1 1-1.06-1.06L10.69 9 7.22 5.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif
        </div>
        </div>
        
    </nav>
@endif

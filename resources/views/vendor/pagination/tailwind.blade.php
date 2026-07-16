@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Navigasi Halaman" class="flex flex-col sm:flex-row items-center justify-between gap-3 mt-1">

        {{-- Info total data --}}
        <p class="text-xs text-zinc-500 dark:text-zinc-400 shrink-0">
            Menampilkan
            @if ($paginator->firstItem())
                <span class="font-semibold text-zinc-700 dark:text-zinc-200">{{ $paginator->firstItem() }}</span>–<span class="font-semibold text-zinc-700 dark:text-zinc-200">{{ $paginator->lastItem() }}</span>
            @else
                <span class="font-semibold text-zinc-700 dark:text-zinc-200">{{ $paginator->count() }}</span>
            @endif
            dari <span class="font-semibold text-zinc-700 dark:text-zinc-200">{{ $paginator->total() }}</span> data
        </p>

        {{-- Nomor halaman --}}
        <div class="flex items-center gap-1 flex-wrap justify-center">

            {{-- Tombol Sebelumnya --}}
            @if ($paginator->onFirstPage())
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-300 dark:text-zinc-600 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-500 dark:text-zinc-400 hover:bg-primary-container hover:text-on-primary-container dark:hover:bg-surface-container-high transition-colors"
                   aria-label="Halaman sebelumnya">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @endif

            {{-- Nomor-nomor halaman --}}
            @foreach ($elements as $element)
                {{-- Titik-titik pemisah "..." --}}
                @if (is_string($element))
                    <span class="inline-flex items-center justify-center w-8 h-8 text-xs text-zinc-400 dark:text-zinc-500 cursor-default select-none">
                        {{ $element }}
                    </span>
                @endif

                {{-- Array nomor halaman --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                  class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-bold bg-primary text-on-primary dark:bg-primary dark:text-on-primary cursor-default shadow-sm">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-xs font-medium text-zinc-600 dark:text-zinc-400 hover:bg-primary-container hover:text-on-primary-container dark:hover:bg-surface-container-high transition-colors"
                               aria-label="Ke halaman {{ $page }}">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Selanjutnya --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-500 dark:text-zinc-400 hover:bg-primary-container hover:text-on-primary-container dark:hover:bg-surface-container-high transition-colors"
                   aria-label="Halaman selanjutnya">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </a>
            @else
                <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-zinc-300 dark:text-zinc-600 cursor-not-allowed">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                </span>
            @endif

        </div>
    </nav>
@endif

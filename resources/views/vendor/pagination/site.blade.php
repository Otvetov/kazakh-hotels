@php
    $elements = $elements ?? [];
    $base = 'min-w-10 h-10 px-3 inline-flex items-center justify-center rounded-xl text-sm font-medium transition select-none';
    $idle = $base . ' bg-[#1b1c1d] border border-white/10 text-gray-300 hover:border-[#8ee30f] hover:text-[#8ee30f]';
    $active = $base . ' bg-[#8ee30f] border border-[#8ee30f] text-[#0a0a0a] font-bold';
    $disabled = $base . ' bg-[#1b1c1d] border border-white/10 text-gray-600 opacity-50 cursor-not-allowed';
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-center">
        <div class="flex items-center gap-1.5 flex-wrap justify-center">
            {{-- Назад --}}
            @if ($paginator->onFirstPage())
                <span aria-disabled="true" class="{{ $disabled }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $idle }}" aria-label="@lang('pagination.previous')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
            @endif

            {{-- Номера страниц --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-2 text-[#7e8488]">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page" class="{{ $active }}">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="{{ $idle }}">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Вперёд --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $idle }}" aria-label="@lang('pagination.next')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            @else
                <span aria-disabled="true" class="{{ $disabled }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </span>
            @endif
        </div>
    </nav>
@endif

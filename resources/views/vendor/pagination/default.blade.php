@if ($paginator->hasPages())
<div class="pagination-wrapper">
    {{-- Previous Page Link --}}
    @if ($paginator->onFirstPage())
        <span class="arrow disabled">‹</span>
    @else
        <a class="arrow" href="{{ $paginator->previousPageUrl() }}" rel="prev">‹</a>
    @endif

    {{-- Pagination Elements --}}
    @foreach ($elements as $element)
        {{-- "Three Dots" Separator --}}
        @if (is_string($element))
            <span class="disabled">{{ $element }}</span>
        @endif

        {{-- Array Of Links --}}
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next Page Link --}}
    @if ($paginator->hasMorePages())
        <a class="arrow" href="{{ $paginator->nextPageUrl() }}" rel="next">›</a>
    @else
        <span class="arrow disabled">›</span>
    @endif
</div>
@endif

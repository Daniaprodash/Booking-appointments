@if ($paginator->hasPages())
<nav role="navigation" aria-label="Pagination">
    <ul class="pagination-custom">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="disabled"><span><i class="fas fa-chevron-right"></i></span></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="fas fa-chevron-right"></i></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><span>{{ $page }}</span></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="fas fa-chevron-left"></i></a></li>
        @else
            <li class="disabled"><span><i class="fas fa-chevron-left"></i></span></li>
        @endif
    </ul>
</nav>

<style>
.pagination-custom {
    display: flex;
    list-style: none;
    padding: 0;
    margin: 0;
    gap: 6px;
    align-items: center;
}

.pagination-custom li a,
.pagination-custom li span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 36px;
    height: 36px;
    padding: 0 12px;
    border-radius: 8px;
    font-size: 0.9rem;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.2s ease;
    color: var(--muted);
    background: var(--glass);
}

.pagination-custom li a:hover {
    background: var(--primary-2);
    color: #fff;
    transform: translateY(-2px);
}

.pagination-custom li.active span {
    background: linear-gradient(135deg, var(--primary-1), var(--primary-2));
    color: #fff;
    box-shadow: 0 4px 12px rgba(15, 179, 161, 0.2);
}

.pagination-custom li.disabled span {
    opacity: 0.4;
    cursor: not-allowed;
}
</style>
@endif

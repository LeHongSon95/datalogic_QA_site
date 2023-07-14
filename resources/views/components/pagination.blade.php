<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
    class="navigation d-flex align-items-center justify-content-center mx-3">
    <ul class="pagination pagination">
        {{-- previous Page first --}}
        <li class="page-item">
            @if ($paginator->onFirstPage())
                <span class="material-icons page-item__link">keyboard_double_arrow_left</span>
            @else
                <a href="{{ $paginator->url(1) }}" class="material-icons page-item__link">
                    keyboard_double_arrow_left
                </a>
            @endif
        </li>

        {{-- previous Page Link --}}
        <li class="page-item">
            @if ($paginator->onFirstPage())
                <span class="material-icons page-item__link" aria-disabled="true"
                    aria-label="{{ __('pagination.previous') }}">chevron_left</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="material-icons page-item__link"
                    aria-label="{{ __('pagination.previous') }}">
                    chevron_left
                </a>
            @endif
        </li>

        @if ($paginator->currentPage() > 2)
            <li class="page-item">
                <a class="page-item__link" href="{{ $paginator->url(1) }}"
                    aria-label="{{ __('Go to page :page', ['page' => 1]) }}">
                    1
                </a>
            </li>
        @endif

        @if ($paginator->currentPage() > 3)
            <li class="page-item" aria-disabled="true">
                <span class="page-item__link dot">...</span>
            </li>
        @endif

        @foreach (range(1, $paginator->lastPage()) as $page)
            @if ($page >= $paginator->currentPage() - 1 && $page <= $paginator->currentPage() + 1)
                <li class="page-item">
                    @if ($page == $paginator->currentPage())
                        <span class="page-item__link" aria-current="page">{{ $page }}</span>
                    @else
                        <a class="page-item__link" href="{{ $paginator->url($page) }}"
                            aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                            {{ $page }}
                        </a>
                    @endif
                </li>
            @endif
        @endforeach

        @if ($paginator->currentPage() < $paginator->lastPage() - 2)
            <li class="page-item" aria-disabled="true">
                <span class="page-item__link dot">...</span>
            </li>
        @endif

        @if ($paginator->currentPage() < $paginator->lastPage() - 1)
            <li class="page-item">
                <a class="page-item__link" href="{{ $paginator->url($paginator->lastPage()) }}"
                    aria-label="{{ __('Go to page :page', ['page' => $paginator->lastPage()]) }}">
                    {{ $paginator->lastPage() }}
                </a>
            </li>
        @endif

        {{-- Next Page Link --}}
        <li class="page-item">
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="material-icons page-item__link"
                    aria-label="{{ __('pagination.next') }}">
                    chevron_right
                </a>
            @else
                <span class="material-icons page-item__link" aria-disabled="true"
                    aria-label="{{ __('pagination.next') }}">chevron_right</span>
            @endif
        </li>

        {{-- next Page Last --}}
        <li class="page-item">
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="material-icons page-item__link">
                    keyboard_double_arrow_right
                </a>
            @else
                <span class="material-icons page-item__link">keyboard_double_arrow_right</span>
            @endif
        </li>
    </ul>
</nav>

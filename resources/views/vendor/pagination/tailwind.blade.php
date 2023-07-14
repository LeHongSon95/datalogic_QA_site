@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="navigation d-flex align-items-center justify-content-center">
        <ul class="pagination">
            <li class="page-item">
                @if ($paginator->onFirstPage())
                    <span class="material-icons page-item__link">keyboard_double_arrow_left</span>
                @else
                    <a href="{{ $paginator->url(1)  }}" class="material-icons page-item__link">
                        keyboard_double_arrow_left
                    </a>
                @endif
            </li>

            {{-- previous Page Link --}}
            <li class="page-item">
                @if ($paginator->onFirstPage())
                    <span class="material-icons page-item__link" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">chevron_left</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="material-icons page-item__link" aria-label="{{ __('pagination.previous') }}">
                        chevron_left
                    </a>
                @endif
            </li>

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item" aria-disabled="true">
                        <span class="page-item__link dot">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="page-item">
                            @if ($page == $paginator->currentPage())
                                <span class="page-item__link" aria-current="page">{{ $page }}</span>
                            @else
                                <a class="page-item__link" href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            <li class="page-item">
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="material-icons page-item__link" aria-label="{{ __('pagination.next') }}">
                        chevron_right
                    </a>
                @else
                    <span class="material-icons page-item__link" aria-disabled="true" aria-label="{{ __('pagination.next') }}">chevron_right</span>
                @endif
            </li>

            <li class="page-item">
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->url( $paginator->lastPage() ) }}" class="material-icons page-item__link">
                        keyboard_double_arrow_right
                    </a>
                @else
                    <span class="material-icons page-item__link">keyboard_double_arrow_right</span>
                @endif
            </li>
        </ul>
    </nav>
@endif

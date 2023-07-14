<div class="wrapper__search">
    <form class="form-search-qa" method="get" action="{{ route('frontend.home.index') }}">
        <div class="group-search {{  !$isSearch && count($questionRecommend) ? 'mb-4' : '' }}">
            <div class="top">
                <div class="top__left d-flex align-items-end">
                    <span class="text me-2">{{ trans('messages.note_search') }}</span>

                    <button type="button" class="btn-tooltip material-icons"
                            data-bs-toggle="tooltip"
                            data-bs-html="true"
                            data-bs-placement="top"
                            data-bs-offset="-235,2"
                            data-bs-title='{{ trans('messages.help_search') }}'
                            data-bs-custom-class="tooltip-help-qa"
                    >
                        help
                    </button>
                </div>
                <div class="top__right">
                    <div class="form-check custom-form-check d-flex align-items-end">
                        <input class="form-check-input homepage-search-checkbox-custom"
                               type="radio"
                               value="{{ trans('messages.title_search') }}"
                               id="title-search"
                               name="title_search"
                               {{ request()->input('title_search') || !request()->has('full_text_search') ? 'checked' : '' }}
                               
                        >
                        <label class="form-check-label" for="title-search">
                            {{ trans('messages.title_search') }}
                        </label>
                    </div>

                    <div class="form-check custom-form-check d-flex align-items-end">
                        <input class="form-check-input homepage-search-checkbox-custom"
                               type="radio"
                               value="{{ trans('messages.full_text_search') }}"
                               id="full-text-search"
                               name="full_text_search"
                                {{ request()->input('full_text_search') ? 'checked' : '' }}
                        >
                        <label class="form-check-label" for="full-text-search">
                            {{ trans('messages.full_text_search') }}
                        </label>
                    </div>
                </div>
            </div>

            <div class="control-group">
                <input type="text"
                       id="key-words"
                       class="form-control"
                       value="{{ \App\Helpers\Helper::pregReplaceSingleSpace( request()->input('s') ) }}"
                       name="s"
                       aria-label=""
                       autocomplete="off"
                >

                @if( !empty( $searchKeywordHistory ) )
                    <ul class="search-history-list">
                        @foreach( $searchKeywordHistory as $keyword )
                            <li class="search-history-list__item">{{ $keyword }}</li>
                        @endforeach
                    </ul>
                @endif

                <button id="btn-clear-words" type="button" class="btn-icon btn-clear-words material-icons">close</button>
                <button type="submit" class="btn-icon btn-submit-search material-icons">search</button>
            </div>

            <div id="key-search-check" class="key-search d-none">
                <label class="key-search__name"></label>
                <input class="key-search__cate" name="cate_id" value="{{ request()->input('cate_id') }}" aria-label="" type="hidden">
                <span class="btn-delete-filter-cate material-icons">close</span>
            </div>
        </div>

        @include('frontend.home.components.question-recommend')

        <div class="group-select">
            <div class="top under-line d-flex justify-content-between">
                <h4 class="top__left m-0">
                    {{ trans('messages.related_qa') }}
                </h4>

                <p class="top__right">
                    {{ trans('messages.applicable', ['count' => $questions->total()]) }}
                </p>
            </div>

            <div class="nav-box has-paginator">
                <div class="nav-box__left">
                    <select class="form-select select-items-per-page show-item" name="items_per_page" aria-label="">
                        @foreach( Config::get('constants.itemsPerPage') as $itemPerPage )
                            <option value="{{ $itemPerPage }}"{{ request()->input('items_per_page') == $itemPerPage ? 'selected': ''  }}>{{ $itemPerPage }}</option>
                        @endforeach
                    </select>

                    <span>{{ trans('messages.showing_item_page') }}</span>
                </div>

                {{-- paginator view custom --}}
                {{ $questions->appends(request()->query())->links('components.pagination') }}

                {{-- select order --}}
                <div class="nav-box__right">
                    <select class="form-select select-order-qa" name="order_by" aria-label="">
                        @foreach( Config::get('constants.orders') as $order )
                            <option value="{{ $order }}"{{ request()->input('order_by') == $order ? 'selected': ''  }}>{{ trans('messages.order_' . $order) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </form>
</div>
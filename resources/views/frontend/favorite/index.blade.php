@extends('layouts.master')
@section('title', 'Home')

@section('addCss')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/favorite.css') }}">
@endsection

@section('content')
    <div class="site-favorite-qa site-page">
        <div class="container">
            <div class="breadcrumb-option d-lg-flex align-items-lg-start justify-content-lg-between">
                <nav aria-label="breadcrumb" class="mb-4 m-lg-0">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('frontend.home.index') }}">
                                {{ trans('messages.breadcrumb.frontend.top_page') }}
                            </a>
                        <li class="breadcrumb-item active" aria-current="page">
                            {{ trans('messages.breadcrumb.frontend.favorite') }}
                        </li>
                    </ol>
                </nav>

                @include('frontend.components.option-change-font-size', [
                    'class' => 'd-flex align-items-center',
                ])
            </div>

            <div class="warp-container">
                <h4 class="title entry-heading under-line">
                    {{ trans('messages.breadcrumb.frontend.favorite') }}
                    ({{ trans('messages.subject', ['count' => count($data)]) }})
                </h4>
                <form class="form-search-qa" method="get" action="{{ route('frontend.favorite') }}">
                    <div class="nav-box has-paginator">
                        <div class="nav-box__left">
                            <select class="form-select select-items-per-page show-item" name="items_per_page"
                                aria-label="">
                                @foreach (Config::get('constants.itemsPerPage') as $itemPerPage)
                                    <option
                                        value="{{ $itemPerPage }}"{{ request()->input('items_per_page') == $itemPerPage ? 'selected' : '' }}>
                                        {{ $itemPerPage }}</option>
                                @endforeach
                            </select>
                            <span>{{ trans('messages.showing_item_page') }}</span>
                        </div>

                        {{-- paginator view custom --}}
                        <nav class="nav-box__middle">
                            <ul class="pagination justify-content-center m-0">
                                {{ $data->appends(request()->query())->links('components.pagination') }}
                            </ul>
                        </nav>

                        {{-- select order --}}
                        <div class="nav-box__right">
                            <select class="form-select select-order-qa" name="order_by" aria-label="">
                                @foreach (Config::get('constants.orders') as $order)
                                    <option
                                        value="{{ $order }}"{{ request()->input('order_by') == $order ? 'selected' : '' }}>
                                        {{ trans('messages.order_' . $order) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <div class="favorite-list">
                    @foreach ($data as $item)
                        <div class="favorite-list__item">
                            <h2 class="cate-name">
                                {{ trans('messages.category') }} :
                                @if (count($item->categories))
                                    @foreach ($item->categories as $category)
                                        <span>{{ $category->title }}</span>
                                    @endforeach
                                @endif
                            </h2>

                            <div class="content">
                                <div class="content__entry">
                                    <a href="{{ route('frontend.qa.show', ['id' => $item->id]) }}" class="more">
                                        @if (!empty($item->title))
                                            {!! html_entity_decode($item->title) !!}
                                        @endif
                                    </a>
                                </div>
                                <form action="{{ route('frontend.qa.update_favorite', ['id' => $item->id]) }}"
                                    method="POST">
                                    @csrf
                                    <div class="content__right d-flex justify-content-end">
                                        <button type="submit"
                                            class="btn-icon btn-add-favorite {{ in_array($item->id, $qaFavoriteCookies) ? 'active' : '' }}">
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('addJs')
    <script src="{{ asset('assets/js/pages/favorite.js') }}"></script>
@endsection
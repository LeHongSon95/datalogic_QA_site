@extends('layouts.master')
@section('title', 'FQA LIST')

@section('addCss')
    <link rel="stylesheet" href="{{ asset('assets/css/components/admin.css') }}">
@endsection

@section('content')
<div class="site-qa">
    <div class="container">
        <div class="mySlides_1">
            <div class="wrapper__search">
                <form class="form-search-qa" method="get" action="">
                    <div class="group-search {{ !$isSearch && count($questionRecommend) ? 'mb-4' : '' }}">
                        <div class="control-group">
                            <input type="text" id="key-words" class="form-control"
                                value="{{ \App\Helpers\Helper::pregReplaceSingleSpace(request()->input('s')) }}"
                                name="s" aria-label="" autocomplete="off">

                            @if (!empty($searchKeywordHistory))
                                <ul class="search-history-list">
                                    @foreach ($searchKeywordHistory as $keyword)
                                        <li class="search-history-list__item">{{ $keyword }}</li>
                                    @endforeach
                                </ul>
                            @endif

                            <button id="btn-clear-words" type="button"
                                class="btn-icon btn-clear-words material-icons">close</button>
                            <button type="submit" class="btn-icon btn-submit-search material-icons">search</button>
                        </div>

                        <div id="key-search-check" class="key-search d-none">
                            <label class="key-search__name"></label>
                            <input class="key-search__cate" name="cate_id" value="{{ request()->input('cate_id') }}"
                                aria-label="" type="hidden">
                            <span class="btn-delete-filter-cate material-icons">close</span>
                        </div>
                    </div>
                </form>
            </div>
            <div class="button-left">
                <form class="form-add-qa__group" action="{{ route('import') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex justify-content-between align-center">
                        <label for="customFile"><span
                                class="btn2 d-flex justify-content-center align-items-center">FAQアップロード</span></label>
                        <div class="form-group">
                            <div class="custom-file text-left">
                                <input type="file" name="upload" class="d-none custom-file-input" id="customFile">
                            </div>
                        </div>
                    </div>
                </form>
                <button class="btn1"><a href="{{ route('question.create') }}">FAQ追加</a></button>
            </div>
            <div class="line"></div>
            <div class="site-qa__content">
                <div class="wrapper">
                    <div class="wrapper__search">
                        <form class="form-search-qa" method="get" action="">
                            <div class="group-select">
                                <div class="nav-box has-paginator">
                                    <div class="nav-box__left">
                                        <select class="form-select select-items-per-page show-item"
                                            name="items_per_page" aria-label="">
                                            @foreach (Config::get('constants.itemsPerPage') as $itemPerPage)
                                                <option value="{{ $itemPerPage }}"
                                                    {{ request()->input('items_per_page') == $itemPerPage ? 'selected' : '' }}>
                                                    {{ $itemPerPage }}</option>
                                            @endforeach
                                        </select>

                                        <span>{{ trans('messages.showing_item_page') }}</span>
                                    </div>

                                    {{-- paginator view custom --}}
                                    {{ $questions->appends(request()->query())->links('components.pagination') }}

                                    {{-- select order --}}
                                    <div class="nav-box__right">
                                        <select class="form-select select-order-qa" name="order_by" aria-label="">
                                            @foreach (Config::get('constants.orders') as $order)
                                                <option value="{{ $order }}"
                                                    {{ request()->input('order_by') == $order ? 'selected' : '' }}>
                                                    {{ trans('messages.order_' . $order) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="wrapper__result">
                        @if (count($questions))
                            <div class="list-qa">
                                @foreach ($questions as $question)
                                    <div id="question-{{ $question->id }}" class="list-qa__item">
                                        <p class="cate">
                                            {{ trans('messages.category') }} :
                                            @if (count($question->categories))
                                                @foreach ($question->categories as $category)
                                                    <span>{{ $category->title }}</span>
                                                @endforeach
                                            @endif
                                        </p>
                                        <h4 class="title">
                                            {!! strip_tags($question->title) !!}
                                        </h4>
                                        <div class="content">
                                            <button id="btn-action" class="delete material-icons btn-action"
                                                data-id="{{ $question->id }}"
                                                data-route="{{ route('admin.delete', ['id' => $question->id]) }}">delete</button>

                                            <a id="btn-action" class="material-icons btn-action" href="{{ route('question.edit', $question->id) }}">edit</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('addJs')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/pages/qa-top.js') }}"></script>
    <script src="{{ asset('assets/js/pages/admin.js') }}"></script>
    @if ($errors->has('upload'))
        <?php $errorText = ''; ?>
        @if ($errors->has('errorUpload'))
            <?php $errorText = $errorText . '<ul class="sweet-alert-error-list">'; ?>
            @foreach (json_decode($errors->first('errorUpload'), true) as $item)
                @foreach ($item as $row)
                    <?php $errorText = $errorText . '<li>' . $row . '</li>'; ?>
                @endforeach
            @endforeach
            <?php $errorText = $errorText . '</ul>'; ?>
        @endif
        <script>
            Swal.fire({
                icon: 'error',
                title: '{{ $errors->first('upload') }}',
                html: '{!! $errorText !!}',
                confirmButtonText: "はい",
            })
        </script>
    @endif
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                text: '{{ session('success') }}',
                confirmButtonText: "はい",
            })
        </script>
    @endif
@endsection

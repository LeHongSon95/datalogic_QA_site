@extends('layouts.master')
@section('title', 'Q&A Detail')

@section('addCss')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/detail.css') }}">
@endsection

@section('content')
    <div class="site-qa-detail site-page">
        <div class="container">
            <div class="breadcrumb-option d-lg-flex align-items-lg-start justify-content-lg-between">
                <nav aria-label="breadcrumb" class="mb-4 m-lg-0">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a
                                href="{{ route('frontend.home.index') }}">{{ trans('messages.breadcrumb.frontend.top_page') }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{!! strip_tags($qa->title) !!}</li>
                    </ol>
                </nav>

                @include('frontend.components.option-change-font-size', [
                    'class' => 'd-flex align-items-center',
                ])
            </div>

            <div class="warp-container">
                <div class="qa-post">
                    <div class="heading d-md-flex align-items-md-center justify-content-md-between">
                        <p class="cate">
                            {{ trans('messages.category') }} :
                            @if (count($qa->categories))
                                @foreach ($qa->categories as $category)
                                    <span>{{ $category->title }}</span>
                                @endforeach
                            @endif
                        </p>

                        <p class="date">
                            {{ trans('messages.update_date') }} :
                            {{ $qa->updated_at->format(config('date.date_format_y_m_d')) }}
                        </p>
                    </div>

                    <h4 class="title">
                        {!! $qa->title !!}
                    </h4>

                    <div class="content">
                        {!! html_entity_decode($qa->answers->content) !!}
                    </div>
                    <div class="favorite-box text-end mt-4">
                        <form action="{{ route('frontend.qa.update_favorite', ['id' => $qa->id]) }}" method="POST">
                            @csrf
                           
                            <button type="submit" class="btn btn-add-favorite d-inline-flex align-items-center {{ in_array($qa->id, $qaFavoriteCookies) ? 'active' : '' }}">
                            <span class="material-icons">star_border</span>
                                <span class="text">{{ trans('messages.btn_add_favorite') }}</span>
                            </button>
                        </form>
                    </div>
                </div>
                @if (count($questionRelated))
                    <div class="related-question">
                        <h4 class="question__title">
                            {{ trans('messages.related_question') }}
                        </h4>
                        @foreach ($questionRelated as $key => $item)
                            <p>{{ $key + 1 . ', ' }}{!! strip_tags($item->title) !!} <a class="more"
                                    href="{{ route('frontend.qa.show', ['id' => $item->id]) }}">
                                    … {{ trans('messages.show_detail') }}
                                </a></p>
                        @endforeach
                    </div>
                @endif

                <div class="question">
                    <h4 class="question__title">
                        {{ trans('messages.questionnaire.title') }}
                    </h4>

                    <form class="question__form" action="{{ route('frontend.qa.questionnaire.store', ['id' => $qa->id]) }}"
                        method="POST">
                        @csrf
                        <div class="left">
                            <ul class="list-group list-group-radio reset-list custom-list-group">
                                @foreach (config('constants.questionnaire_status') as $key => $value)
                                    <li class="list-group-item">
                                        <input id="status-{{ $key }}" class="form-check-input"
                                            value="{{ $value }}" type="radio" name="status"
                                            {{ (!old('status') && $value == config('constants.questionnaire_status.solved')) || old('status') == $value ? 'checked' : '' }}>
                                        <label for="status-{{ $key }}"
                                            class="form-check-label">{{ trans('messages.questionnaire_status.' . $key) }}</label>
                                    </li>
                                @endforeach
                            </ul>
                            @if ($errors->has('status'))
                                <div class="error">{{ $errors->first('status') }}</div>
                            @endif
                        </div>

                        <div class="right">
                            <label for="comment" class="form-label">
                                {{ trans('messages.questionnaire.content_label') }}</label>

                            <textarea id="comment" class="comment form-control" name="content"></textarea>

                                <p class="note {{ $errors->has('content') ? 'mb-0' : '' }}">
                                    {{ trans('messages.questionnaire.note') }}</p>
         
                            @if ($errors->has('content'))
                                <div class="error mb-5">{{ $errors->first('content') }}</div>
                            @endif
                            <div class="action text-end">
                                <button type="submit" class="btn btn-submit-question">
                                    {{ trans('messages.questionnaire.btn_send') }}
                                </button>
                            </div>
                            @if(session('success'))
                            <div class="alert">
                                <p class="mb-0">
                                アンケート送信成功しました。
                                </p>
                                <button type="button" class="btn-icon material-icons btn-close-alert">close</button>
                            </div>
                            @endif
                           
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true" id="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id=""></h5>
                    <button type="button" class="btn btn-close"></button>
                </div>
                <div class="modal-body">
                    
                </div>
            </div>
        </div>
    </div>
@endsection

@section('addJs')
    <script src="{{ asset('assets/js/pages/qa-show.js') }}"></script>
@endsection

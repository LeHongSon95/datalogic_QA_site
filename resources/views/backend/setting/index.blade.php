@extends('layouts.master')
@section('title', 'Admin Setting')

@section('addCss')
    <link rel="stylesheet" href="{{ asset('assets/css/libs/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pages/setting.css') }}">
@endsection

@section('content')
    <div class="site-setting site-page container">
        <div class="site-setting-container">
            <div class="site-setting-warp">
                <h4 class="wrapper__heading">
                    {{ trans('messages.Keyword_setting') }}
                </h4>
                <div class="wrapper form-featured-keywords">
                    @include('backend.setting.components.form-create-featured-tag')
                    @include('backend.setting.components.form-delete-featured-tag', [
                        'featured_keywords' => $featured_keywords,
                    ])
                </div>

                <h4 class="wrapper__heading recommended-title">
                    {{ trans('messages.qa_recommended_settings') }}
                </h4>
                <div class="wrapper form-featured-keywords form-qa-recomended">
                    @include('backend.setting.components.form-create-question-recommend', [
                        'questions' => $questions,
                    ])
                    @include('backend.setting.components.form-delete-question-recommend', [
                        'questionsRecommendeds' => $questionsRecommendeds,
                    ])
                </div>

                <div class="search-button_end">
                    <div class="flex-all">
                        <form action="{{ route('admin.setting.updateSetting') }}" method="POST">
                            @csrf
                            <p>{{ trans('messages.change_number_of_characters_question') }}</p>
                            <div class="d-flex">
                                <input id="number" type="text" oninput="onInputChange(event)"
                                    name="question_characters"
                                    value="{{ (old('question_characters') ? old('question_characters') : $setting) ? $setting->question_characters : '' }}">
                                <button type="submit" class="btn btn-custom">
                                    <p>{{ trans('messages.save') }}</p>
                                </button>
                            </div>
                            @error('question_characters')
                                <small style="color:red" class="help-block">{{ $message }}</small>
                            @enderror
                        </form>
                    </div>
                    <div class="flex-all">
                        <form action="{{ route('admin.setting.updateSetting') }}" method="POST">
                            @csrf
                            <p>{{ trans('messages.change_number_of_characters_answer') }}</p>
                            <div class="d-flex">
                                <input type="text" oninput="onInputChange(event)" name="answer_characters"
                                    value="{{ (old('answer_characters') ? old('answer_characters') : $setting) ? $setting->answer_characters : '' }}">
                                <button type="submit" class="btn btn-custom">
                                    <p>{{ trans('messages.save') }}</p>
                                </button>
                            </div>
                            @error('answer_characters')
                                <small style="color:red" class="help-block">{{ $message }}</small>
                            @enderror
                        </form>
                    </div>
                    <div class="flex-all">
                        <form action="{{ route('admin.setting.updateSetting') }}" method="POST">
                            @csrf
                            <p>{{ trans('messages.change_number_of_recently_viewed_keywords') }}</p>
                            <div class="d-flex">
                                <input type="text" oninput="onInputChange(event)" name="number_recently_viewed_keywords"
                                    value="{{ (old('number_recently_viewed_keywords') ? old('number_recently_viewed_keywords') : $setting) ? $setting->number_recently_viewed_keywords : '' }}">
                                <button type="submit" class="btn btn-custom">
                                    <p>{{ trans('messages.save') }}</p>
                                </button>
                            </div>
                            @error('number_recently_viewed_keywords')
                                <small style="color:red" class="help-block">{{ $message }}</small>
                            @enderror
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('addJs')
    <script src="{{ asset('assets/js/pages/setting.js') }}"></script>
    <script>
        function onInputChange(event) {
            const input = event.target;
            const inputValue = input.value;

            const mia = inputValue.replace(/[^0-9]/g, '');

            input.value = mia;
        }
    </script>
@endsection

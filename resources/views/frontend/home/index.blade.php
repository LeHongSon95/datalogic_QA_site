@extends('layouts.master')
@section('title', 'Home')

@section('addCss')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/qa-top.css') }}">
@endsection

@section('content')
    <div class="site-qa site-page">
        <div class="container">
            @include('frontend.components.option-change-font-size', ['class' => 'mb-4'])

            <div class="site-qa__content">
                <aside class="sidebar">
                    <!--widget categories-->
                    @include('frontend.home.components.widget.category')

                    <!--widget keywords featured -->
                    @include('frontend.home.components.widget.keywords', ['keywords' => $featured_keywords])

                    <!--widgets keywords recently -->
                    @include('frontend.home.components.widget.recently_keywords', [
                        'keywords' => $searchKeywordHistory,
                        'setting' => $setting
                    ])

                    <!--widgets favorite-->
                    @include('frontend.home.components.widget.favorite')
                </aside>

                <div class="wrapper">
                    <h4 class="wrapper__heading">
                        {{ trans('messages.keyword_search') }}
                    </h4>

                    @include('frontend.home.components.form-search')

                    <div class="wrapper__result">
                        @include('frontend.home.components.questions')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('addJs')
    <script src="{{ asset('assets/js/pages/qa-top.js') }}"></script>
@endsection
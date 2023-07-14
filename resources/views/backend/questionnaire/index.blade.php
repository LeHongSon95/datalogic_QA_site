@extends('layouts.master')
@section('title', 'questionnaires')

@section('addCss')
    <link rel="stylesheet" href="{{ asset('assets/css/pages/questionnaire.css') }}">
@endsection

@section('content')
    <div class="site-questionnaire site-page container">
        <div class="site-questionnaire-container">
            <div class="site-questionnaire-warp">
                <div class="wrapper__search">
                    @include('components.form-input-search', [
                       'method' => 'get',
                        'name' => 's',
                        'class' => '',
                    ])
                </div>
                <div class="site-questionnaire__list mt-3">
                    @include('backend.questionnaire.components.questionnaires-list', [
                        'questionnaires' => $questionnaires
                    ])
                </div>

            </div>
            @if (count($questionnaires) > 0)
                @include('backend.questionnaire.components.questionnaires-content', [
                    'questionnaires' => $questionnaires
                ])
            @endif
        </div>
    </div>
@endsection

@section('addJs')
    <script src="{{ asset('assets/js/pages/questionnaire.js') }}"></script>
    <script src="{{ asset('assets/js/components/form-input-search.js') }}"></script>
@endsection

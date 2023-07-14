@extends('layouts.master')
@section('title', 'Dashboard')

@section('addCss')
    <link rel="stylesheet" href={{ asset('assets/css/libs/select2.css') }}>
    <link rel="stylesheet" href={{ asset('assets/css/pages/dashboard.css') }}>
    <style>
        .question-item .clear {
            position: absolute;
            right: 40px;
        }
        .question-item input {
            padding-right: 4rem !important;
        }
    </style>
@endsection

@section('content')
    <div class="site-dashboard-qa site-page">
        <div class="container">
            <div class="warp-container">
                <h4 class="entry-heading under-line">
                    {{ trans('messages.breadcrumb.backend.dashboard') }}
                </h4>

                @include('backend.dashboard.form' , [
                    'action' => route('question.update', $qa->id),
                ])
            </div>
        </div>
    </div>
@endsection



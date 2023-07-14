@extends('layouts.master')
@section('title', 'Analysis')

@section('addCss')
<link rel="stylesheet" href={{ asset('assets/css/pages/navigation-tab.css') }}>
<style>
    canvas {
        width: 600px;
        height: 305px;
        max-width: 100%;
        min-height: 305px;
    }
</style>
@endsection

@section('content')
<div class="site-admin-analysis">
    <form action="" id="analysisForm">
        <input type="hidden" name="tab" value="">
        @include('backend.analysis.components.filter')
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ request('tab') == 'number-accesses' || !request('tab') ? 'active' : '' }}" id="number-accesses" data-bs-toggle="tab" data-bs-target="#number-accesses-pane" type="button" role="tab" aria-controls="number-accesses-pane" aria-selected="true">アクセス数
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ request('tab') == 'no-access-count' ? 'active' : '' }}" id="no-access-count" data-bs-toggle="tab" data-bs-target="#no-access-count-pane" type="button" role="tab" aria-controls="no-access-count-pane" aria-selected="false">QA別アクセス数

                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ request('tab') == 'favorite' ? 'active' : '' }}" id="favorite" data-bs-toggle="tab" data-bs-target="#favorite-pane" type="button" role="tab" aria-controls="favorite-pane" aria-selected="false">お気に入り
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ request('tab') == 'number-of-resolutions' ? 'active' : '' }}" id="number-of-resolutions" data-bs-toggle="tab" data-bs-target="#number-of-resolutions-pane" type="button" role="tab" aria-controls="number-of-resolutions-pane" aria-selected="false">解決数</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade {{ request('tab') == 'number-accesses' || !request('tab') ? 'show active' : '' }}" id="number-accesses-pane" role="tabpanel" aria-labelledby="number-accesses" tabindex="0">
                @include('backend.analysis.components.number-accesses-tab', [
                'title' => 'アクセス数',
                ])
            </div>
            <div class="tab-pane fade {{ request('tab') == 'no-access-count' ? 'show active' : '' }}" id="no-access-count-pane" role="tabpanel" aria-labelledby="no-access-count" tabindex="0">
                @include('backend.analysis.components.no-access-count-tab', [
                'title' => 'QA別アクセス数',
                ])
            </div>
            <div class="tab-pane fade {{ request('tab') == 'favorite' ? 'show active' : '' }}" id="favorite-pane" role="tabpanel" aria-labelledby="favorite" tabindex="0">
                @include('backend.analysis.components.favorite-tab', [
                'title' => 'お気に入り',
                ])
            </div>
            <div class="tab-pane fade {{ request('tab') == 'number-of-resolutions' ? 'show active' : '' }}" id="number-of-resolutions-pane" role="tabpanel" aria-labelledby="number-of-resolutions" tabindex="0">
                @include('backend.analysis.components.number-of-resolutions-tab', [
                'title' => '解決数',
                ])
            </div>
        </div>
    </form>
</div>
@endsection

@section('addJs')
<script src={{ asset('assets/js/libs/chart/dist/chart.umd.js') }}></script>
<script src={{ asset('assets/js/pages/navigation-tab.js') }}></script>
@endsection
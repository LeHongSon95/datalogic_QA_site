<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">

    <!-- Font Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/libs/bootstrap.css') }}">

    <!-- Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    @if (Request::is('admin/*'))
    <link rel="stylesheet" href="{{ asset('assets/css/components/admin-menu-bar.css') }}">
    @endif
    @yield('addCss')

    <!-- Custom font size -->
    @php
    $fontSize = \App\Helpers\Helper::getCookieSetFontSize();
    @endphp

    @if ($fontSize['type'] != Config::get('constants.optionFontSize.normal'))
    <link rel="stylesheet" href="{{ asset('assets/css/change-font-size/' . $fontSize['type'] . '.css') }}">
    @endif
</head>

<body class="page-template {{ !Request::is('admin/*') ? $fontSize['class']  : ''}}">
    <div class="loader">
        <div id="loader"></div>
    </div>
    @include('layouts.header')

    <div class="sticky-footer">
        @if (Request::is('admin/*'))
        <div class="container site-admin site-page">
            @include('components.admin-menu-bar')
            @yield('content')
        </div>
        @else
        @yield('content')
        @endif
    </div>

    @include('layouts.footer')

    <!-- Bootstrap Js -->
    <script src="{{ asset('assets/js/libs/bootstrap.js') }}"></script>

    <!-- lib jquery + general Js -->
    <script>
        function getParam(param) {
            return new URLSearchParams(window.location.search).get(param);
        }

        function escapeHtml(text) {
            var map = {
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#039;'
            };

            return text.replace(/[&<>"']/g, function(m) {
                return map[m];
            });
        }
    </script>
    <script src="{{ asset('assets/js/pages/general.js') }}"></script>
    @yield('addJs')
</body>

</html>
<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token"  content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'YoushiSpace') }}</title>

    <!-- Styles -->

{{--    <link href="{{ asset('css/app.css') }}" rel="stylesheet">--}}
    <style>
        body,html{
            margin:0px!important;
        }
    </style>

    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>
<body>
    <div id="app">
        {{--navigation--}}
        @yield('nav')
        {{--content--}}
        @yield('content')
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    {{--轮询获取消息通知--}}
    <script>

    </script>
</body>
</html>

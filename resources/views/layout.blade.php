<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>App - @yield('title')</title>

    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{!! asset('css/app.css') !!}" rel="stylesheet">
    <script type="text/javascript" src="{!! asset('js/app.js') !!}"></script>
</head>
<body>
<div class="position-ref full-height">
    <div class="top-right links">
        <a href="{{ url('/parse-requests') }}">Сохранённые парсинги</a>
    </div>

    <div class="content">
        @yield('content')
    </div>
</div>
</body>
</html>

<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>画像作成</title>

    <!--link rel="icon" type="image/x-icon" href="https://redrooooofsb1.work/asmapp/favicon.ico"-->

    <!-- Fonts -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/earlyaccess/notosansjp.css">

    <!-- Style -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/my.css') }}">

    <!-- Scripts -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <!--script src="{{ asset('js/my.js') }}"></script-->
</head>
<body>

    <div class="content">
        @yield('content')
    </div>

</body>
</html>

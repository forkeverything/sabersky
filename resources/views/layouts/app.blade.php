<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pusaka Group</title>

    <link rel="shortcut icon" href="{{ asset('/images/icons/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}" />


    <!-- Fonts -->
    {{--<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>--}}

    <!-- Styles -->
    <link href="{{ asset('/css/all.css') }}" rel="stylesheet">
</head>
<body id="app-layout">
    @include('layouts.partials.nav')
    @yield('content')
    <!-- JavaScripts -->

    <script type="text/javascript" src="{{ asset('/js/vendor.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Authorization': 'Bearer ' + localStorage.getItem('token')
            }
        });
    </script>
    <script type="text/javascript" src="{{ asset('/js/app.js') }}"></script>
    @include('flash')
</body>
</html>

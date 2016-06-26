<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sabersky</title>

    <link rel="shortcut icon" href="{{ asset('/images/icons/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!--
    ========== Stylez ===========
     -->
    <link href="{{ asset('/css/all.css') }}" rel="stylesheet">
    <!-- Fonts -->
    <script src="https://use.typekit.net/qkf3ndw.js"></script>
    <script>try {
            Typekit.load({async: true});
        } catch (e) {
        }</script>
    {{--<link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel='stylesheet' type='text/css'>--}}
</head>

<body id="error-body">

<!--
========== Content ===========
 -->
<div id="content">
    <img src="/images/logo/logo-blue.jpg" id="logo">
    <h1 id="code"><i class="fa fa-warning"></i>@yield('code')</h1>
    <h2 id="title">@yield('title')</h2>
    @yield('body')
</div>

    <!--
    ========== Scripts ===========
    -->
    <!-- Plugins / Frameworks -->
    <script type="text/javascript" src="{{ asset('/js/vendor.js') }}"></script>
    <!-- Setup & Initz' -->
    <script type="text/javascript" src="{{ asset('/js/dependencies.js') }}"></script>
    <!-- Global (helpers) -->
    <script type="text/javascript" src="{{ asset('/js/global.js') }}"></script>
    <!-- Flash Notification -->
    @include('layouts.partials.flash')
</body>
</html>

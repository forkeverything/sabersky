<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sabersky</title>

    <link rel="shortcut icon" href="{{ asset('/images/icons/favicon.png') }}">

    <!--
    ========== Stylez ===========
     -->
    <link href="{{ asset('/css/all.css') }}" rel="stylesheet">
    <!--
    ========== External Scripts ==========
    -->
    <!-- Typekit -->
    <script src="https://use.typekit.net/qkf3ndw.js"></script>
    <script>try {
            Typekit.load({async: true});
        } catch (e) {
        }</script>
</head>

<body id="app-layout">

<!--
========== Content ===========
 -->
@include('layouts.partials.nav')
<div id="main-stage" v-cloak>
    @if(Auth::user())
        @include('layouts.partials.side-menu')
        <div id="body-content"
             :class="{
             'with-menu': showingMenu
             }"
        @click="hideOverlays"
        >
        @yield('content')
</div>
@else
    <div id="body-content"
    @click="hideOverlays"
    @if(isset($fullPage) && $fullPage)class="full-page"@endif
    >
    @yield('content')
    </div>
    @endif
    </div>


    <!--
    ========== Scripts ===========
    -->
    <script type="text/javascript" src="{{ asset('/js/landing-vendor.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/landing-dependencies.js') }}"></script>
    <script type="text/javascript" src="{{ asset('/js/landing.js') }}"></script>


    <!-- Flash Notification -->
    @include('layouts.partials.flash')
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Pusaka Group</title>

    <link rel="shortcut icon" href="{{ asset('/images/icons/favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <meta name="stripe-key" content="{{ env('STRIPE_KEY') }}"/>

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
    @if(isset($page) && $page === 'landing')class="landing-layout"@endif
    >
    @yield('content')
    </div>
    @endif
    </div>


    <!--
    ========== Scripts ===========
    -->
    <!-- Plugins / Frameworks -->
    <script type="text/javascript" src="{{ asset('/js/vendor.js') }}"></script>
    <!-- Stripe -->
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>

    <!-- Setup & Initz' -->
    <script type="text/javascript" src="{{ asset('/js/dependencies.js') }}"></script>
    <!-- Global (helpers) -->
    <script type="text/javascript" src="{{ asset('/js/global.js') }}"></script>
    <!-- Page Specific Components -->
    <script type="text/javascript" src="{{ asset('/js/page.js') }}"></script>
    <!-- Global Vue Instance -->
    <script src="{{ asset('/js/vue-root.js') }}"></script>
    <!-- Flash Notification -->
    @include('layouts.partials.flash')
</body>
</html>

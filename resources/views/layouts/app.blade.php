<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sabersky</title>

    <link rel="shortcut icon" href="{{ asset('/images/icons/favicon.png') }}">

    @include('layouts.partials.meta-fields')

    <!--
    ========== Stylez ===========
     -->
    <link href="{{ asset('/css/all.css') }}" rel="stylesheet">
    <!--
    ========== External Scripts ==========
    -->
    <!-- Stripe -->
    <script type="text/javascript" src="https://js.stripe.com/v2/"></script>
    <!-- Pusher -->
    <script type="text/javascript" src="https://js.pusher.com/3.1/pusher.min.js"></script>
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
    <!-- Plugins / Frameworks -->
    <script type="text/javascript" src="{{ asset('/js/vendor.js') }}"></script>
    <!-- Pusher -->
    <script type="text/javascript" src="https://js.pusher.com/3.1/pusher.min.js"></script>
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
    @yield('google-tracking-code')
</body>
</html>

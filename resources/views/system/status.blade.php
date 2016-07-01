<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sabersky</title>

    <link rel="shortcut icon" href="{{ asset('/images/icons/favicon.png') }}">

    <meta name="pusher-key" content="{{ env('PUSHER_KEY') }}"/>

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

    <!--
    ========== External Scripts ==========
    -->
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

    <system-status inline-template :company-count="{{ $companyCount }}">
        <div id="system-status">
            <h1>
                @{{ companyCount }}
            </h1>
        </div>
    </system-status>

    <!--
    ========== Scripts ===========
    -->
    <!-- Plugins / Frameworks -->
    <script type="text/javascript" src="{{ asset('/js/vendor.js') }}"></script>
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


<meta name="csrf-token" content="{{ csrf_token() }}"/>
<meta name="stripe-key" content="{{ env('STRIPE_KEY') }}"/>
<meta name="pusher-key" content="{{ env('PUSHER_KEY') }}"/>
@if(Auth::check())
    <meta name="user-id" content="{{Auth::user()->id}}">
@endif
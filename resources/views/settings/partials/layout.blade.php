@extends('layouts.app')
@section('content')
    <div id="system-settings" class="container">
        <div class="custom-tabs">
            @yield('tab-content')
        </div>
    </div>
@endsection
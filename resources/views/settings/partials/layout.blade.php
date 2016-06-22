@extends('layouts.app')
@section('content')
    <div id="system-settings" class="container">

        <div id="settings-title">
            @yield('settings-title')
        </div>

        <div class="row flexing">
            <div class="col-sm-2">
                @include('settings.partials.nav')
            </div>
            <div class="col-sm-10">
                <div id="settings-content">
                    @yield('settings-content')
                </div>
            </div>
        </div>
    </div>
@endsection
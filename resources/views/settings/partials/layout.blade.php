@extends('layouts.app')
@section('content')
    <div id="system-settings" class="container">
        <settings-dropdown-nav :page="'{{ $page }}'"></settings-dropdown-nav>
        <div id="settings-header">
            @yield('settings-header')
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
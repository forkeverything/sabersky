@extends('settings.partials.layout')
@section('tab-content')
    @include('settings.partials.nav', ['page' => 'permissions'])

    <div class="tab-content">
        @include('settings.partials.permissions')
    </div>
@endsection

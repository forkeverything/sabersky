@extends('settings.partials.layout')
@section('tab-content')
    @include('settings.partials.nav', ['page' => 'rules'])

    <div class="tab-content">
        @include('settings.partials.rules')
    </div>
@endsection

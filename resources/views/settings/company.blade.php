@extends('settings.partials.layout')
@section('tab-content')
    @include('settings.partials.nav', ['page' => 'company'])

    <div class="tab-content">
        @include('settings.partials.company')
    </div>
@endsection


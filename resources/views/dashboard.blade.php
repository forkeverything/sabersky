@extends('layouts.app')

@section('content')
    <dashboard inline-template :user="{{ $user }}">
        <div id="dashboard" class="container">

            @include('dashboard.overview')

            <div class="row">
                <div class="col-sm-8">
                    <div id="dashboard-calendar"></div>
                </div>
                <div class="col-sm-4">
                    @include('dashboard.quick-links')
                </div>
            </div>

        </div>
    </dashboard>
@endsection

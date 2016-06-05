@extends('layouts.app')

@section('content')
    <dashboard inline-template :user="{{ $user }}">
        <div id="dashboard" class="container">

            @include('dashboard.overview')

            <div class="row">
                <div class="col-sm-8">
                    
                </div>
            </div>

        </div>
    </dashboard>
@endsection

@extends('layouts.app')

@section('content')
    <dashboard inline-template :user="{{ $user }}">
        <div id="dashboard" class="container">
            <div class="row">
                <div class="col-sm-3 hidden-xs">
                    <div class="date card">
                        <div class="month-year">
                            <span class="month">@{{ date.format('MMMM') }}</span>
                            <span class="year">@{{ date.format('YYYY') }}</span>
                        </div>
                        <h1 class="day weekday">@{{ date.format('dddd') }}</h1>
                        <h1 class="day calendar">@{{ date.format('Do') }}</h1>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="actionables card">
                        <span class="card-title left small">Actionables</span>
                        <hr>
                        @can('po_submit')
                            <div class="requests-unfulfilled">
                                <h3 class="subheading">Unfulfilled Requests</h3>
                                <a href="/purchase_requests"><h1>{{ $numUnfulfilledRequests }}</h1></a>
                            </div>
                        @endcan

                        @can('po_payments')
                            <div class="orders-unpaid">
                                <h3 class="subheading">Orders - Unpaid</h3>
                                <a href="/purchase-orders"><h1>4</h1></a>
                            </div>
                            @endcan

                    </div>
                </div>
            </div>
        </div>
    </dashboard>
@endsection

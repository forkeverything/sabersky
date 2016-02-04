@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-requests-all">
        <a href="{{ route('dashboard') }}" class="back-link"><i class="fa  fa-arrow-left fa-btn"></i>Back to
            Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">Purchase Requests</h1>
        </div>
        <p class="page-intro">This is where you can find purchase requests made by Engineers / Planners.</p>
        @can('pr_make')
        <a href="{{ route('makePurchaseRequest') }}">
            <button class="btn btn-solid-green" id="button-make-purchase-request">Make Purchase Request</button>
        </a>
        @endcan
        <div class="purchase-request-filters">
            <ul class="list-unstyled list-inline">
                <li class="unselectable"
                    @click="changeFilter('')"
                    :class="{
                        'active': filter !== 'complete' && filter !== 'cancelled'
                    }"
                >
                Open
                </li>
                <li class="unselectable"
                    :class="{
                        'active': filter == 'complete'
                    }"
                @click="changeFilter('complete')"
                >
                Complete
                </li>
                <li class="unselectable"
                    :class="{
                        'active': filter == 'cancelled'
                    }"
                @click="changeFilter('cancelled')"
                >
                Cancelled
                </li>
            </ul>
            <span class="filter-urgent unselectable"
            @click="toggleUrgent"
            :class="{ 'active': urgent}"
            >
            Urgent Only</span>
        </div>
        @if($purchaseRequests->first())
            @include('purchase_requests.partials.table_all')
        @else
            <h4 class="text-center">No Purchase Requests could be found.</h4>
        @endif


    </div>
@endsection
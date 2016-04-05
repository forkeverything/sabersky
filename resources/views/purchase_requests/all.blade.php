@extends('layouts.app')
@section('content')
    <purchase-requests-all inline-template>
        <div class="container" id="purchase-requests-all">
            @can('pr_make')
            <section class="page-top children-right">
                <a href="{{ route('makePurchaseRequest') }}">
                    <button class="btn btn-solid-green" id="button-make-purchase-request">Make Purchase Request</button>
                </a>
            </section>
            @endcan
            <div class="page-body">
                <div class="purchase-request-filters table-filters">
                    <ul class="list-unstyled list-inline">
                        <li class="clickable"
                        @click="changeFilter('')"
                        :class="{
                        'active': filter !== 'complete' && filter !== 'cancelled'
                    }"
                        >
                        Open
                        </li>
                        <li class="clickable"
                            :class="{
                        'active': filter == 'complete'
                    }"
                        @click="changeFilter('complete')"
                        >
                        Complete
                        </li>
                        <li class="clickable"
                            :class="{
                        'active': filter == 'cancelled'
                    }"
                        @click="changeFilter('cancelled')"
                        >
                        Cancelled
                        </li>
                    </ul>
                    <span class="filter-urgent clickable"
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
        </div>
    </purchase-requests-all>
@endsection

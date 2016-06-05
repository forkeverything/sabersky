@extends('layouts.app')
@section('content')
    <purchase-requests-all inline-template>
        <div class="container" id="purchase-requests-all">


            <div class="title-with-buttons">
                <h1>Purchase Requests</h1>
                @can('pr_make')
                    <div class="buttons">
                        <a href="{{ route('makePurchaseRequest') }}" class="link-make-pr">
                            <button class="btn btn-solid-green" id="button-make-purchase-request">Make Request</button>
                        </a>
                    </div>
                @endcan
            </div>


            <div class="custom-tabs">

                <ul class="nav nav-tabs" role="tablist" v-autofit-tabs>
                    @include('purchase_requests.partials.all.tab-nav')
                </ul>

                <div class="tab-content">

                    <!-- Controls -->
                    <div class="pr-controls table-controls with-right">
                        <div class="controls-left controls-filter-search">
                            <div class="filters with-search" v-dropdown-toggle="showFiltersDropdown">
                                <div class="dropdown">
                                    @include('purchase_requests.partials.all.filters')
                                </div>
                            </div>
                                @include('layouts.partials.form-search-repository')
                        </div>
                        <div class="controls-right">
                            <div class="control-urgent">
                                @include('purchase_requests.partials.all.checkbox-urgent')
                            </div>
                        </div>
                        <div class="active-filters">
                            @include('purchase_requests.partials.all.filters_active')
                        </div>
                    </div>

                    <!-- Has Requests -->
                    <div class="has-purchase-requests" v-if="response.total > 0">

                        <div class="table-responsive">
                            @include('purchase_requests.partials.all.table-requests')
                        </div>

                        <div class="page-controls">
                            <per-page-picker :response="response" :req-function="makeRequest"></per-page-picker>
                            <paginator :response="response" :req-function="makeRequest"></paginator>
                        </div>

                    </div>

                    <!-- Empty Stage -->
                    <div class="no-purchase-requests empty-stage" v-else>
                        <i class="fa fa-shopping-basket"></i>
                        <h4>Could not find any Purchase Requests</h4>
                        <p>Try changing filters, <a @click="removeAllFilters">removing all</a>
                            filters, <a @click="clearSearch">clear</a> the search or
                            <a @click="changeState('all')">view all</a> requests to see more.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </purchase-requests-all>
@endsection

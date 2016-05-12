@extends('layouts.app')
@section('content')
    <purchase-requests-all inline-template>
        <div class="container" id="purchase-requests-all">
            @can('pr_make')
            <div class="top align-end">
                <a href="{{ route('makePurchaseRequest') }}" class="link-make-pr">
                    <button class="btn btn-solid-green" id="button-make-purchase-request">Make Purchase Request</button>
                </a>
            </div>
            @endcan

            <div class="custom-tabs">

                <ul class="nav nav-tabs" role="tablist" v-autofit-tabs>
                    @include('purchase_requests.partials.all.tab-nav')
                </ul>

                <div class="tab-content">
                    <div class="pr-controls table-controls">
                        <div class="controls-left">
                            <div class="pr-filters dropdown" v-dropdown-toggle="showFiltersDropdown">
                                @include('purchase_requests.partials.all.filters')
                            </div>
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
                    <div class="has-purchase-requests" v-if="response.total > 0">

                        <div class="table-responsive">
                            @include('purchase_requests.partials.all.table-requests')
                        </div>

                        <div class="page-controls">
                            <per-page-picker :response="response" :req-function="makeRequest"></per-page-picker>
                            <paginator :response="response" :req-function="makeRequest"></paginator>
                        </div>

                    </div>
                    <div class="no-purchase-requests empty-stage" v-else>
                        <i class="fa fa-shopping-basket"></i>
                        <h4>Could not find any Purchase Requests</h4>
                        <p>Try changing filters, <a class="dotted clickable" @click="removeAllFilters">removing all</a>
                            filters or
                            <a @click="changeState('all')" class="dotted clickable">view all</a> requests to see more.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </purchase-requests-all>
@endsection

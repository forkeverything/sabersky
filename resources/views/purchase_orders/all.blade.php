    @extends('layouts.app')
@section('content')
    <purchase-orders-all inline-template :user="user">
        <div id="purchase-orders-all" class="container">
            @can('po_submit')
            <div class="top align-end">
                <a class="link-create-order" href="{{ route('getSubmitPOForm') }}">
                    <button class="btn btn-solid-green" id="button-submit-purchase-order">Create Order</button>
                </a>
            </div>
            @endcan

            <div class="custom-tabs">

                <ul class="nav nav-tabs" role="tablist" v-autofit-tabs>
                    @include('purchase_orders.all.tab-nav')
                </ul>

                <div class="tab-content">
                    <div class="table-controls">
                        <div class="controls-left">
                            <div class="po-filters dropdown" v-dropdown-toggle="showFiltersDropdown">
                                @include('purchase_orders.all.filters')
                            </div>
                        </div>
                        <div class="active-filters">
                            @include('purchase_orders.all.active-filters')
                        </div>
                    </div>

                    <div class="has-purchase-orders" v-if="response.total > 0">
                        <div class="table-responsive">
                            @include('purchase_orders.all.table-orders')
                        </div>
                        <div class="page-controls">
                            <per-page-picker :response="response" :req-function="makeRequest"></per-page-picker>
                            <paginator :response="response" :req-function="makeRequest"></paginator>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </purchase-orders-all>
@endsection

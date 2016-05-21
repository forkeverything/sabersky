@extends('layouts.app')
@section('content')
    <purchase-order-single inline-template :user="user">

        <div id="purchase-order-single" class="container">
            <input type="hidden" value="{{ $purchaseOrder->id }}" v-model="purchaseOrderID">

            <div class="approvals no-print">
                @include('purchase_orders.partials.single.approvals')
            </div>


            <div class="stats">
                <div class="align-end">
                    <a class="link-show-stats dotted" role="button" data-toggle="collapse" href="#po-stats-collapse" aria-expanded="false" aria-controls="po-stats-collapse">
                        View Stats
                    </a>
                </div>
                <div class="collapse" id="po-stats-collapse">
                        @include('purchase_orders.partials.single.stats')
                </div>

            </div>

            <div class="page-body">

                <div class="meta visible-xs">
                    @include('purchase_orders.partials.single.meta')
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <h3>Vendor</h3>
                        <div class="vendor">
                            @include('purchase_orders.partials.single.vendor')
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <h3 class="visible-xs">Order</h3>
                        <div class="order">
                            @include('purchase_orders.partials.single.order')
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-xs-12">
                        <h3>Items</h3>
                        <div class="quick-actions">
                            <button class="btn btn-small btn-outline-blue" @click="markAllPaid" :disabled="! purchaseOrder.approved">Mark All Paid</button>
                        </div>
                        <div class="line-items">
                            @include('purchase_orders.partials.single.line-items')
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-xs-12">
                        <div class="additional-costs">
                            @include('purchase_orders.partials.single.additional-costs')
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-offset-6 col-md-6">
                        <div class="summary">
                            @include('purchase_orders.partials.single.summary')
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </purchase-order-single>
@endsection
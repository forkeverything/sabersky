@extends('layouts.app')
@section('content')
    <div id="purchase-order-single" class="container">

            <div class="approvals no-print">
                @include('purchase_orders.partials.single.approvals')
            </div>

        <div class="page-body">

            <div class="meta visible-xs">
                @include('purchase_orders.partials.single.meta')
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="vendor">
                        @include('purchase_orders.partials.single.vendor')
                    </div>
                </div>
                <div class="col-sm-8">

                    <div class="order">
                        @include('purchase_orders.partials.single.order')
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
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
@endsection
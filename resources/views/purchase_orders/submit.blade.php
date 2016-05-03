@extends('layouts.app')
@section('content')
    <purchase-orders-submit inline-template :user="user">
        <div class="container" id="purchase-orders-submit">
            @include('purchase_orders.submit.step-1')
            @include('purchase_orders.submit.step-2')
            <single-pr-modal></single-pr-modal>
        </div>
    </purchase-orders-submit>
@endsection

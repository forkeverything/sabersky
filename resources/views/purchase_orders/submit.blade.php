@extends('layouts.app')
@section('content')
    <purchase-orders-submit inline-template :user="user">
        <div class="container" id="purchase-orders-submit">
            @include('purchase_orders.partials.submit.step-1')
            @include('purchase_orders.partials.submit.step-2')
        </div>
    </purchase-orders-submit>
@endsection

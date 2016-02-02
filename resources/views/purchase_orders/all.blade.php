@extends('layouts.app')
@section('content')
    <div id="purchase-orders-all" class="container">
        <a href="{{ route('dashboard') }}" class="back-link"><i class="fa  fa-arrow-left fa-btn"></i>Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">Purchase Orders</h1>
        </div>
        <p>Purchase orders which have been submitted by the Procurement team.</p>
        @can('po_submit')
        <a href="{{ route('submitPurchaseOrder') }}">
            <button class="btn btn-solid-green" id="button-submit-purchase-order">Submit Purchase Order</button>
        </a>
        @endcan
    </div>
@endsection
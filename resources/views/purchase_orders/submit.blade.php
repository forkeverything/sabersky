@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-orders-submit">
        <a href="{{ route('showAllPurchaseOrders') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Purchase
            Orders</a>
        <div class="page-header">
            <h1 class="page-title">Submit Purchase Order</h1>
        </div>
        <p class="page-intro">Create and Submit Purchase Orders from requests made by the Planning / Engineering Team.</p>
        @include('purchase_orders.partials.selected_info')
        @include('errors.list')
        <form
            @if(! $existingPO)
               action="{{ route('savePOStep1') }}"
            @elseif(! $existingPO->vendor_id)
               action="{{ route('savePOStep2') }}"
            @else
               action="{{ route('completePurchaseOrder') }}"
            @endif
            id="form-submit-purchase-order"
            method="POST"
        >
        {{ csrf_field() }}
        @if(! $existingPO)
            @include('purchase_orders.partials.step_1_fields')
        @elseif(! $existingPO->vendor_id)
            @include('purchase_orders.partials.step_2_fields')
        @else
            @include('purchase_orders.partials.step_3_fields')
        @endif
        @if(! $existingPO)
            <div class="form-group">
                <button type="submit" class="btn btn-primary form-control">Next Step 2: Vendor Details
                </button>
            </div>
        @elseif(! $existingPO->vendor_id)
            <div class="form-group" v-show="readyStep3">
                <button type="submit" class="btn btn-primary form-control">Next Step 3: Add Items
                </button>
            </div>
        @endif
        </form>
    </div>
@endsection
@section('scripts.footer')
<script src="{{ asset('/js/page/purchase-orders/submit.js') }}"></script>
@endsection
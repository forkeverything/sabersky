@extends('layouts.app')
@section('content')
    @if($vendor->linkedCompany)
    <div class="container" id="vendor-single-linked">
        <div class="row">
            <div class="col-sm-8">
                <div class="page-body vendor">
                    <h4>Vendor</h4>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="page-body linked-company">
                    <h4>Linked Company</h4>
                </div>
            </div>
        </div>
    </div>
    @else
        @include('vendors.partials.single.custom')
    @endif
@endsection

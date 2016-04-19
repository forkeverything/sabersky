@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-request-single">
        <div class="row">
            <div class="col-sm-4">
                <div class="card-item page-body">
                    <h4>Item</h4>
                    <div class="thumbnail-item">
                        @if($thumbnail = $purchaseRequest->item->photos->first())
                            {{ $thumbnail->path }}
                        @else
                            <div class="placeholder">
                                <i class="fa fa-image"></i>
                            </div>
                        @endif
                    </div>
                    <div class="details-item">
                        <a class="dotted" href="{{ route('getSingleItem', $purchaseRequest->item->id) }}">{{ $purchaseRequest->item->name }}</a>
                    </div>

                </div>
                <div class="card-project page-body visible-lg visible-md visible-sm">
                    <h4>Project</h4>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card-pr page-body">
                    <h4>Purchase Request Details</h4>
                </div>
                <div class="mobile-card-project page-body visible-xs">
                    <h4>Project</h4>
                </div>
            </div>
        </div>
    </div>
@endsection


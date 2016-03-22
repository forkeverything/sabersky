@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-request-single">
        <a href="{{ route('showAllPurchaseRequests') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Back
            to Purchase Requests</a>
        <div class="page-header">
            <h1 class="page-title">
                Purchase Request
            </h1>
        </div>
        <div class="page-body">
            <div class="item-details">
                <h2>{{ $purchaseRequest->item->name }}@if($purchaseRequest->urgent)<span class="badge-warning"><i class="fa fa-warning"></i>URGENT</span> @endif</h2>
                <p>
                    {{ $purchaseRequest->item->specification }}
                </p>
                @if($photos = $purchaseRequest->item->photos)
                    @include('layouts.partials.photo_gallery')
                @endif
                @can('pr_make')
                    @if($purchaseRequest->state === 'Open')
                        <form id="form-item-photo" action="{{ route('addItemPhoto', $purchaseRequest->item->id) }}" method="POST">
                            {{ csrf_field() }}
                        @include('layouts.partials.input_item_photos')
                        </form>
                    @endif
                @endcan
            </div>
            <div class="request-details">
                <h2>Request Details</h2>
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th>State</th>
                        <td>{{ $purchaseRequest->state }}</td>
                    </tr>
                    <tr>
                        <th>Requested</th>
                        <td>{{ $purchaseRequest->created_at->diffForHumans() }}</td>
                    </tr>
                    <tr>
                        <th>Project</th>
                        <td class="capitalize">{{ $purchaseRequest->project->name }}</td>
                    </tr>
                    <tr>
                        <th>Quantity Outstanding</th>
                        <td>{{ $purchaseRequest->quantity }}</td>
                    </tr>
                    <tr>
                        <th>Requested By</th>
                        <td>{{ $purchaseRequest->user->name }}</td>
                    </tr>
                    <tr>
                        <th>Due Date</th>
                        <td>{{ $purchaseRequest->due->format('d M Y') }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            @can('pr_make')
                @if($purchaseRequest->state === 'Open')
                    <form action="{{ route('cancelPurchaseRequest')}}" id="form-pr-cancel" method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" value="{{ $purchaseRequest->id }}" name="purchase_request_id">
                        <!-- Submit -->
                            <button type="submit" class="btn btn-outline-red form-control button-cancel">Cancel</button>
                    </form>
                @endif
            @endcan
        </div>
    </div>
@endsection


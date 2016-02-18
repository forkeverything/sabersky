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
            </div>
            <div class="request-details">
                <h5>Request Details</h5>
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
                        <td>{{ $purchaseRequest->project->name }}</td>
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
            <form action="{{ route('cancelPurchaseRequest')}}" id="form-pr-cancel" method="POST">
                {{ csrf_field() }}
                <input type="hidden" value="{{ $purchaseRequest->id }}" name="purchase_request_id">
                <!-- Submit -->
                    <button type="submit" class="btn btn-outline-red form-control">Cancel</button>
            </form>
            @endcan
        </div>
    </div>
@endsection
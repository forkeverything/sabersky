@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-request-single">
        <a href="{{ route('showAllPurchaseRequests') }}" class="back-link"><i class="fa  fa-arrow-left fa-btn"></i>Back
            to Purchase Requests</a>
        <div class="page-header">
            <div class="page-title">
                <strong>Purchase Request - </strong> {{ $purchaseRequest->item->name }}
                for {{ $purchaseRequest->project->name }}  @if($purchaseRequest->urgent)<span class="badge-warning">URGENT</span> @endif
            </div>
        </div>
        <div class="purchase-request-single-details">
            <h5>Purchase Request Details</h5>
            <table class="table table-bordered">
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
                    <th>Specification</th>
                    <td>{{ $purchaseRequest->item->specification }}</td>
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

    </div>
@endsection
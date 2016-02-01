@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-requests-all">
        <a href="{{ route('dashboard') }}" class="back-link"><i class="fa  fa-arrow-left fa-btn"></i>Back to
            Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">Purchase Requests</h1>
        </div>
        <p class="page-intro">This is where you can find purchase requests made by Engineers / Planners.</p>
        @if(Auth::user()->is('director') || Auth::user()->is('planner'))
            <a href="{{ route('makePurchaseRequest') }}">
                <button class="btn btn-solid-green" id="button-make-purchase-request">Make Purchase Request</button>
            </a>
        @endif
        @if(! $purchaseRequests->isEmpty())
                <table class="table table-hover table-purchase-requests">
                    <thead>
                        <tr>
                            <th>Due Date</th>
                            <th>Project</th>
                            <th>Item</th>
                            <th>Specification</th>
                            <th>Quantity</th>
                            <th>Requested By</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($purchaseRequests as $purchaseRequest)
                        <tr data-href="{{ route('singlePurchaseRequest', $purchaseRequest->id) }}">
                            <td>{{ $purchaseRequest->due->format('d M Y') }}</td>
                            <td>{{ $purchaseRequest->project->name }}</td>
                            <td>{{ $purchaseRequest->item->name }}</td>
                            <td>{{ str_limit($purchaseRequest->item->specification, 45, ' ...') }}</td>
                            <td>{{ $purchaseRequest->quantity }}</td>
                            <td>{{ $purchaseRequest->user->name }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        @else
            <h4 class="text-center">No Purchase Requests have been made.</h4>
        @endif


    </div>
@endsection
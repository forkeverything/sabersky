@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-requests-all">
        <a href="{{ route('dashboard') }}" class="link-underline"><i class="fa  fa-arrow-left fa-btn"></i>Back to
            Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">Purchase Requests</h1>
        </div>
        <p>This is where you can find purchase requests made by Engineers / Planners.</p>
        @if(Auth::user()->is('director') || Auth::user()->is('planner'))
            <a href="{{ route('makePurchaseRequest') }}">
                <button class="btn btn-default" id="button-make-purchase-request">Make Purchase Request</button>
            </a>
        @endif
        @if(! $purchaseRequests->isEmpty())
            @foreach($purchaseRequests as $purchaseRequest)
                {{ $purchaseRequest->item->name }}
            @endforeach
        @else
            <h4 class="text-center">No Purchase Requests have been made.</h4>
        @endif
    </div>
@endsection
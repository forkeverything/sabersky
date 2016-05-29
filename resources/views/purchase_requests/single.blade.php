@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-request-single">

            <h1>Purchase Request #{{ $purchaseRequest->number }}</h1>
        <div class="align-end">
            <pr-single-cancel :purchase-request="{{ $purchaseRequest }}"></pr-single-cancel>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="item hidden-xs card">
                    @include('purchase_requests.partials.single.item-card')
                </div>
                <div class="project card hidden-xs">
                    @include('purchase_requests.partials.single.project-card')
                </div>
            </div>
            <div class="col-sm-8">
                <div class="request card">
                    <p class="card-title">Request</p>
                    <div class="requested-info">
                        <div class="meta">
                            Requested {{ $purchaseRequest->created_at->diffForHumans() }}
                            by {{ $purchaseRequest->user->name }}
                        </div>
                        <span class="badge-state {{ $purchaseRequest->state }}">{{ $purchaseRequest->state }}</span>
                    </div>
                    <hr>
                    <div class="required">
                        <h3>Due by</h3>
                        <div class="due text-center">
                            @if($purchaseRequest->urgent)
                                <span class="badge-urgent"><i class="fa fa-warning"></i></span>
                            @endif
                            {{ $purchaseRequest->due->format('d M Y') }}</div>
                    </div>
                    <hr>
                    <!-- PR details Table -->
                    <div class="quantities">
                        <h3>Quantities</h3>
                        <table class="table table-quantities">
                            <tbody>
                            <tr>
                                <th>Initially Requested</th>
                                <td>{{ $purchaseRequest->initialQuantity }}</td>
                            </tr>
                            <tr>
                                <th>Fulfilled</th>
                                <td>{{ $purchaseRequest->fulfilledQuantity }}</td>
                            </tr>
                            <tr>
                                <th><strong>Outstanding</strong></th>
                                <td><strong>{{ $purchaseRequest->quantity }}</strong></td>
                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="mobile-only item card visible-xs">
                    @include('purchase_requests.partials.single.item-card')
                </div>
                <div class="mobile-only project card visible-xs">
                    @include('purchase_requests.partials.single.project-card')
                </div>
                    <div class="notes card">
                        <h4 class="card-title">Notes</h4>
                        <notes subject="purchase_request" subject_id="{{ $purchaseRequest->id }}" :user="user"></notes>
                    </div>
                    @include('layouts.partials.activities_log', ['activities' => $purchaseRequest->activities])
            </div>
        </div>
    </div>
@endsection


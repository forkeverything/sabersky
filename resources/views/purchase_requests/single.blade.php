@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-request-single">
        <div class="align-end">
            <pr-single-cancel :purchase-request="{{ $purchaseRequest }}"></pr-single-cancel>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="card-item page-body hidden-xs">
                    @include('purchase_requests.partials.single.item-card')
                </div>
                <div class="card-project page-body hidden-xs">
                    @include('purchase_requests.partials.single.project-card')
                </div>
            </div>
            <div class="col-sm-8">
                <div class="card-request page-body">
                    <h4 class="card-title">Purchase Request #{{ $purchaseRequest->number }}<span
                                class="badge-state {{ $purchaseRequest->state }}">{{ $purchaseRequest->state }}</span>
                    </h4>
                    <div class="requested-info">
                        Requested {{ $purchaseRequest->created_at->diffForHumans() }}
                        by {{ $purchaseRequest->user->name }}
                    </div>
                    <div class="required">
                        <span class="card-subheading">Due by</span>
                        <div class="due">
                            @if($purchaseRequest->urgent)
                                <span class="badge-urgent"><i class="fa fa-warning"></i></span>
                            @endif
                            {{ $purchaseRequest->due->format('d M Y') }}</div>
                    </div>
                    <!-- PR details Table -->
                    <div class="quantities">
                        <span class="card-subheading">Quantities</span>
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
                <div class="mobile-only card-item page-body visible-xs">
                    @include('purchase_requests.partials.single.item-card')
                </div>
                <div class="mobile-only card-project page-body visible-xs">
                    @include('purchase_requests.partials.single.project-card')
                </div>
                <div class="card-history page-body">
                    <h4 class="card-title">Order History / Actions Feed</h4>
                </div>
                <div class="page-body">
                    <div class="card-notes">
                        <h4 class="card-title">Notes</h4>
                        <notes subject="purchase_request" subject_id="{{ $purchaseRequest->id }}" :user="user"></notes>
                    </div>
                    @include('layouts.partials.activities_log', ['activities' => $purchaseRequest->activities])
                </div>
            </div>
        </div>
    </div>
@endsection


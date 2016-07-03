@extends('layouts.app')
@section('content')
    <purchase-request-single inline-template
                             :purchase-request="{{ $purchaseRequest }}"
                             :user="user"
    >
        <div class="container" id="purchase-request-single">

            <h1>Purchase Request #@{{ purchaseRequest.number }}</h1>

            <div class="top-control align-end" v-if="purchaseRequest.quantity > 0">
                <a class="link-fulfill" :href="'/purchase_orders/submit?request=' + purchaseRequest.id">
                    <button type="button" class="btn btn-solid-green">Fulfill</button>
                </a>
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
                                Requested @{{ purchaseRequest.created_at | diffHuman }}
                                by @{{ purchaseRequest.user.name }}
                            </div>
                            <span class="badge-state" :class="purchaseRequest.state">@{{ purchaseRequest.state }}</span>
                        </div>
                        <hr>
                        <div class="required">
                            <h3>Due by</h3>
                            <div class="due text-center">
                                <span class="badge-urgent" v-if="purchaseRequest.urgent"><i
                                            class="fa fa-warning"></i></span>
                                @{{ purchaseRequest.due | easyDate }}
                            </div>
                        </div>
                        <hr>
                        <!-- PR details Table -->
                        <div class="quantities">
                            <h3>Quantities</h3>
                            <table class="table table-quantities">
                                <tbody>
                                <tr>
                                    <th>Initially Requested</th>
                                    <td>@{{ purchaseRequest.initialQuantity }}</td>
                                </tr>
                                <tr>
                                    <th>Fulfilled</th>
                                    <td>@{{ purchaseRequest.fulfilledQuantity }}</td>
                                </tr>
                                <tr>
                                    <th><strong>Outstanding</strong></th>
                                    <td><strong>@{{ purchaseRequest.quantity }}</strong></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                        @include('purchase_requests.partials.single.cancel')
                    </div>
                    <div class="mobile-only item card visible-xs">
                        @include('purchase_requests.partials.single.item-card')
                    </div>
                    <div class="mobile-only project card visible-xs">
                        @include('purchase_requests.partials.single.project-card')
                    </div>
                    <div class="notes card">
                        <h4 class="card-title">Notes</h4>
                        <notes subject="purchase_request" subject_id="{{ $purchaseRequest->id}}" :user="user"></notes>
                    </div>
                    @include('layouts.partials.activities_log', ['activities' => $purchaseRequest->activities])
                </div>
            </div>

        </div>
    </purchase-request-single>
@endsection


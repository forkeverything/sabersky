@extends('layouts.app')
@section('content')
    <purchase-orders-submit inline-template :user="user">
        <div class="container" id="purchase-orders-submit">
            <div class="row">
                <div class="col-sm-8">
                    <div class="page-body select-vendor visible-xs">
                        @include('purchase_orders.partials.submit.select-vendor')
                    </div>
                    <div class="page-body select-pr">
                        <h5>Select Requests</h5>
                        <div class="project-selecter">
                            <label class="display-block">Project</label>
                            <user-projects-selecter :name.sync="projectID"></user-projects-selecter>
                        </div>
                        @include('purchase_orders.partials.submit.select-pr')
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="page-body vendor hidden-xs">
                        @include('purchase_orders.partials.submit.select-vendor')
                    </div>
                    <div class="page-body line-items">
                        <h5>Selected Items</h5>
                        <ul class="list-line-items list-unstyled" v-if="lineItems && lineItems.length > 0">
                            <li v-for="(index, lineItem) in lineItems" class="list-item-single">
                                <div class="count">@{{ index + 1 }}.</div>
                                <div class="item-details">
                                    <span class="brand">@{{ lineItem.item.brand }}</span>
                                    <span class="name">@{{ lineItem.item.name }}</span>
                                </div>
                                <div class="quantity">
                                    <div class="shift-label-input">
                                        <input type="text" v-model="lineItem.purchase_quantity | numberModel"
                                               class="form-control" required>
                                        <label class="required" placeholder="QTY"></label>
                                    </div>
                                </div>
                                <div class="price">
                                    <label class="required">Unit Price</label>
                                    <div class="input-group">
                                        <span class="input-group-addon" v-cloak>@{{ user.company.currency }}</span>
                                        <input type="text" v-model="lineItem.price | numberModel"
                                               placeholder="Unit Price" class="form-control">
                                    </div>
                                </div>
                                <div class="date-payable">
                                    <label>Payable</label>
                                    <input type="text" name="due" class="datepicker" v-datepicker placeholder="Date"
                                           v-model="lineItem.date_payable | easyDateModel">
                                </div>
                                <div class="date-delivery">
                                    <label>Estimated Delivery</label>
                                    <input type="text" name="due" class="datepicker" v-datepicker placeholder="Date"
                                           v-model="lineItem.date_delivery | easyDateModel">
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </purchase-orders-submit>
@endsection

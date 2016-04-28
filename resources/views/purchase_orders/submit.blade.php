@extends('layouts.app')
@section('content')
    <purchase-orders-submit inline-template :user="user">
        <div class="container" id="purchase-orders-submit">
            <div class="page-body">
                <section class="step project">
                    <h5>Project</h5>
                    <user-projects-selecter :name.sync="projectID"></user-projects-selecter>
                </section>
                @include('purchase_orders.partials.submit.select-pr')
                <section class="step line-items">
                    <h5>Line Items</h5>
                    <ul class="list-line-items list-unstyled" v-if="lineItems && lineItems.length > 0">
                        <li v-for="(index, lineItem) in lineItems" class="list-item-single">
                            <div class="count">@{{ index + 1 }}.</div>
                            <div class="item-details">
                                <span class="sku">@{{ lineItem.item.sku }}</span>
                                <span class="brand">@{{ lineItem.item.brand }}</span>
                                <span class="name">@{{ lineItem.item.name }}</span>
                            </div>
                            <div class="quantity">
                                <input type="text" v-model="lineItem.purchase_quantity | numberModel" placeholder="Qty" class="form-control">
                            </div>
                            <span class="at">@</span>
                            <div class="price">
                                <div class="input-group">
                                    <span class="input-group-addon" v-cloak>@{{ user.company.currency }}</span>
                                    <input type="text" v-model="lineItem.price | numberModel" placeholder="Unit Price" class="form-control">
                                </div>
                            </div>
                            <div class="date-payable">
                                <label>Payable</label>
                                <input type="text" name="due" v-datepicker placeholder="Date" v-model="lineItem.date_payable | easyDateModel">
                            </div>
                            <div class="date-delivery">
                                <label>Estimated Delivery</label>
                                <input type="text" name="due" v-datepicker placeholder="Date" v-model="lineItem.date_delivery | easyDateModel">
                            </div>
                        </li>
                    </ul>
                </section>
            </div>
        </div>
    </purchase-orders-submit>
@endsection

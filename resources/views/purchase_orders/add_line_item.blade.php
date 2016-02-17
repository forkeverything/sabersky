@extends('layouts.app')
@section('content')
    <div class="container" id="add-line-item">
        <a href="{{ route('submitPurchaseOrder') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Submit
            Purchase Order</a>
        <div class="page-header">
            <h1 class="page-title">
                Add Purchase Request
            </h1>
        </div>
        <div class="page-body">
            <section>
                <h5>How to fulfill a Purchase Request</h5>
                <ol>
                    <li>Select a Purchase Request</li>
                    <li>Set Quantity purchasing from vendor</li>
                    <li>Insert the quoted Unit Price</li>
                    <li>Enter Payable Date as given by vendor</li>
                    <li>Give estimated Delivery Date for order</li>
                </ol>
            </section>
            <p class="text-center"
               v-if="! purchaseRequests.length > 0"
            >
                There aren't any Open Purchase Requests for this project. Please have a Director or
                Planner add it first before you submit a Purchase Order.</p>

            <div class="table-responsive"
                 v-show="! selectedPurchaseRequest"
            >
                <h5>Select Purchase Request to Order</h5>
                <span class="filter-urgent clickable"
                @click="toggleUrgent"
                :class="{ 'active': urgent}"
                >
                Urgent Only</span>
                <table class="table table-hover table-purchase-requests table-sort">
                    <thead>
                    <tr>
                        <th
                        @click="changeSort('due')"
                        class="clickable"
                        :class="{
                        'active': field == 'due',
                        'asc' : order == '',
                        'desc': order == '-1'
                    }"
                        >
                        Date Due
                        </th>
                        <th
                        @click="changeSort('item.name')"
                        class="clickable"
                        :class="{
                        'active': field == 'item.name',
                        'asc' : order == '',
                        'desc': order == '-1'
                    }"
                        >Item
                        </th>
                        <th>
                            Specification
                        </th>
                        <th
                        @click="changeSort('quantity')"
                        class="clickable"
                        :class="{
                        'active': field == 'quantity',
                        'asc' : order == '',
                        'desc': order == '-1'
                    }"
                        >Quantity
                        </th>
                        <th
                        @click="changeSort('user.name')"
                        class="clickable"
                        :class="{
                        'active': field == 'user.name',
                        'asc' : order == '',
                        'desc': order == '-1'
                    }"
                        >Made by
                        </th>
                        <th
                        @click="changeSort('created_at')"
                        class="clickable"
                        :class="{
                        'active': field == 'created_at',
                        'asc' : order == '',
                        'desc': order == '-1'
                    }"
                        >Requested
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <template v-for="purchaseRequest in purchaseRequests | orderBy field order | filterBy urgent in 'urgent'">
                        <tr @click="selectPurchaseRequest(purchaseRequest)"
                        class="clickable"
                        :class="{'urgent': purchaseRequest.urgent}"
                        >
                        <td>
                            @{{ purchaseRequest.due | easyDate}}
                        </td>
                        <td>
                            @{{ purchaseRequest.item.name }}
                        </td>
                        <td>
                            @{{ purchaseRequest.item.specification }}
                        </td>
                        <td>
                            @{{ purchaseRequest.quantity }}
                        </td>
                        <td>
                            @{{ purchaseRequest.user.name }}
                        </td>
                        <td>
                            @{{ purchaseRequest.created_at | diffHuman}}
                        </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>

            <div
                    v-show="selectedPurchaseRequest"
            >
                <h5>Selected Purchase Request</h5>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <strong>@{{ selectedPurchaseRequest.item.name }}</strong>
                        <a class="close"
                        @click="removeSelectedPurchaseRequest"
                        >
                        &times;</a>
                    </div>
                    <div class="panel-body">
                        <p>
                            @{{ selectedPurchaseRequest.item.specification }}
                        </p>
                        <hr>
                        <div class="row">
                            <div class="col-sm-6">
                                <span class="text-muted">Requested By @{{ selectedPurchaseRequest.user.name }}</span>
                            </div>
                            <div class="col-sm-6 text-right">
                                <span class="text-muted">Requested @{{ selectedPurchaseRequest.created_at | diffHuman }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tabel-responsive purchase-order-details">
                    <h5>Order Details</h5>
                    <!-- Line Item Details Table -->
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Requested Quantity</th>
                            <td class="text-muted">@{{ selectedPurchaseRequest.quantity }}</td>
                        </tr>
                        <tr>
                            <th>Order Quantity</th>
                            <td><input v-model="quantity" type="number" min="0"></td>
                        </tr>
                        <tr>
                            <th>Unit Price</th>
                            <td><input v-model="price" type="number" min="0"></td>
                        </tr>
                        <tr>
                            <th>Item Subtotal</th>
                            <td class="text-muted">@{{ subtotal | numberFormat}} Rp</td>
                        </tr>
                        <tr>
                            <th>Date Payable</th>
                            <td>
                                <input v-model="payable"
                                       type="text"
                                       class="datepicker"
                                       placeholder="Pick Date"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>Due Date</th>
                            <td class="text-muted">@{{ selectedPurchaseRequest.due | date }}</td>
                        </tr>
                        <tr>
                            <th>Estimated Arrival Date</th>
                            <td>
                                <input type="text"
                                       v-model="delivery"
                                       class="datepicker"
                                       placeholder="Pick Date"
                                >
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <button class="btn-solid-green"
                        v-show="canAddPurchaseRequest"
                @click="addLineItem"
                >Add Purchase Request</button>
            </div>
        </div>

    </div>
@endsection
@section('scripts.footer')
    <script src="{{ asset('/js/page/line-items/add.js') }}"></script>
@stop


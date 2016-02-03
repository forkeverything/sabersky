@extends('layouts.app')
@section('content')
    <div id="purchase-orders-all" class="container">
        <a href="{{ route('dashboard') }}" class="back-link"><i class="fa  fa-arrow-left fa-btn"></i>Dashboard</a>
        <div class="page-header">
            <h1 class="page-title">Purchase Orders</h1>
        </div>
        <p>Purchase orders which have been submitted by the Procurement team.</p>
        @can('po_submit')
        <a href="{{ route('submitPurchaseOrder') }}">
            <button class="btn btn-solid-green" id="button-submit-purchase-order">Submit Purchase Order</button>
        </a>
        @endcan
        <div class="table-responsive">
            <!-- PO All Table -->
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Date Submitted</th>
                    <th>Project</th>
                    <th>Item(s)</th>
                    <th>Order Total</th>
                    <th class="text-center">Approved</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="purchaseOrder in purchaseOrders">
                    <tr>
                        <td>@{{ purchaseOrder.created_at | easyDate}}</td>
                        <td>@{{ purchaseOrder.project.name }}</td>
                        <td>
                            <ul class="po-item-list">
                                <li v-for="lineItem in purchaseOrder.line_items">
                                    @{{ lineItem.purchase_request.item.name }}
                                </li>
                            </ul>
                        </td>
                        <td>
                            @{{ purchaseOrder.total }}
                        </td>
                        <td class="text-center">
                            <span class="fa fa-check"
                            v-if="purchaseOrder.approved"
                            ></span>
                            <span class="fa fa-close"
                                  v-else
                            ></span>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>
@endsection
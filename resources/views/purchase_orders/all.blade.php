@extends('layouts.app')
@section('content')
    <purchase-orders-all inline-template>
        <div id="purchase-orders-all" class="container">
            <a href="{{ route('dashboard') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Dashboard</a>
            <div class="page-header">
                <h1 class="page-title">Purchase Orders</h1>
                <p class="page-intro">Purchase orders which have been submitted by the Procurement team.</p>
            </div>
            <div class="page-body">
                @can('po_submit')
                <a href="{{ route('submitPurchaseOrder') }}">
                    <button class="btn btn-solid-green" id="button-submit-purchase-order">Submit Purchase Order</button>
                </a>
                @endcan
                <div class="purchase-orders-filters table-filters">
                    <ul class="list-unstyled list-inline">
                        <li
                                v-for="status in statuses"
                                class="clickable"
                        @click="changeFilter(status.key)"
                        :class="{
                            'active': filter == status.key
                        }"
                        >
                        @{{ status.label }}
                        </li>
                    </ul>
                    <span class="filter-urgent clickable"
                    @click="toggleUrgent"
                    :class="{ 'active': urgent}"
                    >
                    Urgent Only</span>
                </div>
                <div class="table-responsive">
                    <!-- PO All Table -->
                    <table class="table table-hover table-sort">
                        <thead>
                        <tr>
                            <template v-for="heading in headings">
                                <th v-if="heading[0] !== ''"
                                @click="changeSort(heading[0])"
                                class="clickable"
                                :class="{
                                'active': field == heading[0],
                                'asc' : order == '',
                                'desc': order == '-1'
                        }"
                                >
                                @{{ heading[1] }}
                                </th>
                                <th v-else
                                    :class="{
                                'text-center': (heading[1] == 'Status' || heading[1] == 'Paid' || heading[1] == 'Delivered')
                            }"
                                >
                                    @{{ heading[1] }}
                                </th>
                            </template>

                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="purchaseOrder in purchaseOrders | orderBy field order | filterBy filter in 'status'">
                            <tr
                                    :class="{
                            'urgent': checkUrgent(purchaseOrder)
                        }"
                                    v-show="! urgent || checkUrgent(purchaseOrder)"
                            @click="loadSinglePO(purchaseOrder.id)"
                            >
                            <td>@{{ purchaseOrder.created_at | easyDate}}</td>
                            <td>@{{ purchaseOrder.project.name }}</td>
                            <td>
                                <ul class="po-item-list list-unstyled">
                                    <li v-for="lineItem in purchaseOrder.line_items">
                                        @{{ lineItem.purchase_request.item.name }}
                                    </li>
                                </ul>
                            </td>
                            <td>
                                @{{ purchaseOrder.total | numberFormat }} Rp
                            </td>
                            <td class="text-center">
                            <span class="fa fa-check"
                                  v-if="purchaseOrder.status =='approved'"
                            ></span>
                            <span class="fa fa-close"
                                  v-if="purchaseOrder.status == 'rejected'"
                            ></span>
                            <span class="fa fa-warning"
                                  v-if="purchaseOrder.status == 'pending'"
                            ></span>
                            </td>
                            <td class="text-center">
                            <span class="fa fa-check"
                                  v-if="checkProperty(purchaseOrder, 'paid')"
                            ></span>
                            <span class="fa fa-close"
                                  v-else
                            ></span>
                            </td>
                            <td class="text-center">
                            <span class="fa fa-check"
                                  v-if="checkProperty(purchaseOrder, 'delivered')"
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
        </div>
    </purchase-orders-all>
@endsection

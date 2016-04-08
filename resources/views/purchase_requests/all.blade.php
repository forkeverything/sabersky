@extends('layouts.app')
@section('content')
    <purchase-requests-all inline-template>
        <div class="container" id="purchase-requests-all">
            @can('pr_make')
            <div class="top children-right">
                <a href="{{ route('makePurchaseRequest') }}" class="link-make-pr">
                    <button class="btn btn-solid-green" id="button-make-purchase-request">Make Purchase Request</button>
                </a>
            </div>
            @endcan
            <div class="pr-controls">
                <div class="control-urgent">
                    <input type="checkbox"
                           id="checkbox-pr-urgent"
                           v-model="urgent"
                    @click="toggleUrgentOnly"
                    >
                    <label for="checkbox-pr-urgent"
                           :class="{
                                'urgent-only': urgent
                               }"
                    ><i class="fa fa-warning"></i> Urgent only</label>
                </div>
                <div class="pr-filters dropdown" v-dropdown-toggle="showFilterDropdown">
                    <button type="button"
                            class="btn button-dotted button-show-filter-dropdown button-toggle-dropdown"
                    >Filter:<span class="current-filter">@{{ response.data.filter | capitalize }}</span><i class="fa fa-chevron-down"></i>
                    </button>
                    <div class="dropdown-filters dropdown-container"
                         v-show="showFilterDropdown"
                    >
                        <span class="dropdown-title">View only</span>
                        <ul class="list-unstyled">
                            <li class="pr-dropdown-item"
                                v-for="filter in filters"
                            @click="changeFilter(filter)"
                            >
                            @{{ filter.label }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="page-body">
                <div class="page-controls-top">
                    <div class="pr-items-per-page-selecter">
                        <select-picker :name.sync="itemsPerPage" :options.sync="itemsPerPageOptions" :function="changeItemsPerPage"></select-picker>
                    </div>
                    @include('purchase_requests.partials.paginator')
                </div>
                <div class="pr-bag table-responsive">
                    <table class="table table-bordered table-hover table-standard table-purchase-requests-all">
                        <thead>
                        <tr>
                            <th class="clickable"
                            @click="changeSort('project_name')"
                            :class="{
                                            'current_asc': sort === 'project_name' && order === 'asc',
                                            'current_desc': sort === 'project_name' && order === 'desc'
                                        }"
                            >
                            Project
                            </th>
                            <th class="clickable"
                            @click="changeSort('quantity')"
                            :class="{
                                            'current_asc': sort === 'quantity' && order === 'asc',
                                            'current_desc': sort === 'quantity' && order === 'desc'
                                        }"
                            >
                            Qty
                            </th>
                            <th class="clickable"
                            @click="changeSort('item_name')"
                            :class="{
                                            'current_asc': sort === 'item_name' && order === 'asc',
                                            'current_desc': sort === 'item_name' && order === 'desc'
                                        }"
                            >
                            Item
                            </th>
                            <th class="clickable"
                            @click="changeSort('due')"
                            :class="{
                                            'current_asc': sort === 'due' && order === 'asc',
                                            'current_desc': sort === 'due' && order === 'desc'
                                        }"
                            >
                            Due</th>
                            <th class="clickable"
                            @click="changeSort('created_at')"
                            :class="{
                                            'current_asc': sort === 'created_at' && order === 'asc',
                                            'current_desc': sort === 'created_at' && order === 'desc'
                                        }"
                            >
                            Requested
                            </th>
                            <th class="clickable"
                            @click="changeSort('requester_name')"
                            :class="{
                                            'current_asc': sort === 'requester_name' && order === 'asc',
                                            'current_desc': sort === 'requester_name' && order === 'desc'
                                        }"
                            >
                            By
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <template v-for="purchaseRequest in response.data">
                            <tr class="row-single-pr" v-if="purchaseRequest.id">
                                <td class="col-project">@{{ purchaseRequest.project_name }}</td>
                                <td class="col-quantity">@{{ purchaseRequest.quantity }}</td>
                                <td class="col-item">
                                    <span class="item-name">@{{ purchaseRequest.item_name }}</span>
                                    <span class="item-specifications">@{{ purchaseRequest.item_specification }}</span>
                                </td>
                                <td>
                                    <span class="pr-due">@{{ purchaseRequest.due | easyDate }}</span>
                                </td>
                                <td>
                                    <span class="pr-requested">@{{ purchaseRequest.created_at | diffHuman }}</span>
                                </td>
                                <td>
                                    <span class="pr-requester">@{{ purchaseRequest.requester_name | capitalize }}</span>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
                <div class="page-controls-bottom">
                    @include('purchase_requests.partials.paginator')
                </div>
            </div>
        </div>
    </purchase-requests-all>
@endsection

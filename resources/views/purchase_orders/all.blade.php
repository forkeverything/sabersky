    @extends('layouts.app')
@section('content')
    <purchase-orders-all inline-template>
        <div id="purchase-orders-all" class="container">
            @can('po_submit')
            <div class="top align-end">
                <a href="{{ route('getSubmitPOForm') }}">
                    <button class="btn btn-solid-green" id="button-submit-purchase-order">Create Order</button>
                </a>
            </div>
            @endcan
            <div class="custom-tabs">

                <ul class="nav nav-tabs" role="tablist" v-autofit-tabs>
                    <li class="clickable"
                        role="presentation"
                        v-for="status in statuses"
                    @click="changeStatus(status)"
                    :class="{
                                'active': params.status === status
                            }"
                    >
                    <a href="#settings-@{{ status }}"
                       aria-controls="settings-@{{ status }}"
                       role="tab"
                       data-toggle="tab"
                       :class="status"
                    >
                        @{{ status | capitalize }}
                    </a>
                    </li>
                </ul>

                <div class="tab-content">
                    <div class="controls">
                        <div class="po-filters dropdown" v-dropdown-toggle="showFiltersDropdown">
                            <button type="button"
                                    class="btn button-show-filters-dropdown filter-button-toggle-dropdown"
                                    v-if="response.data"
                            >Filters <i
                                        class="fa fa-caret-down"></i>
                            </button>
                            <div class="filter-dropdown dropdown-container left"
                                 v-show="showFiltersDropdown"
                            >
                                <p>Show if</p>
                                <select-picker :options="filterOptions" :name.sync="filter" :placeholder="'Select one...'"></select-picker>

                                <!-- Number Filter -->
                                <div class="number filter" v-show="filter === 'number'">
                                    <p>is between</p>
                                    <integer-range-field :min.sync="minFilterValue" :max.sync="maxFilterValue"></integer-range-field>
                                </div>

                                <!-- Project Filter -->
                                <div class="project filter" v-show="filter === 'project_id'">
                                    <p>is</p>
                                    <user-projects-selecter :name.sync="filterValue"></user-projects-selecter>
                                </div>

                                <!-- Total Cost Filter -->
                                <div class="total filter" v-show="filter === 'total'">
                                    <p>is between</p>
                                    <integer-range-field :min.sync="minFilterValue" :max.sync="maxFilterValue"></integer-range-field>
                                </div>

                                <!-- Item SKU Filter -->
                                <div class="item-brand filter" v-show="filter === 'item_sku'">
                                    <p>is</p>
                                    <item-sku-selecter :name.sync="filterValue"></item-sku-selecter>
                                </div>

                                <!-- Item Brand Filter -->
                                <div class="item-brand filter" v-show="filter === 'item_brand'">
                                    <p>is</p>
                                    <item-brand-selecter :name.sync="filterValue"></item-brand-selecter>
                                </div>

                                <!-- Item Name Filter -->
                                <div class="item-name filter" v-show="filter === 'item_name'">
                                    <p>is</p>
                                    <item-name-selecter :name.sync="filterValue"></item-name-selecter>
                                </div>

                                <!-- Submitted (Date) Filter -->
                                <div class="submitted filter" v-show="filter === 'submitted'">
                                    <p>is from</p>
                                    <date-range-field :min.sync="minFilterValue" :max.sync="maxFilterValue"></date-range-field>
                                </div>

                                <!-- Made By (User) Filter -->
                                <div class="made_by filter" v-show="filter === 'user_id'">
                                    <p>Employee Name</p>
                                    <company-employee-search-selecter :name.sync="filterValue"></company-employee-search-selecter>
                                </div>


                                <button class="button-add-filter btn btn-outline-blue"
                                        v-show="filter && (filterValue || minFilterValue || maxFiltervalue)"
                                        @click.stop.prevent="addFilter">Add Filter
                                </button>
                            </div>
                            <div class="active-filters">

                                <button type="button" v-if="params.number_filter_integer" class="btn button-remove-filter" @click="
                        removeFilter('number')">
                                <span class="field">Number: </span><span v-if="params.number_filter_integer[0]">@{{ params.number_filter_integer[0] }}</span><span v-else>~ </span><span v-if="params.number_filter_integer[0] && params.number_filter_integer[1]"> - </span><span v-if="params.number_filter_integer[1]">@{{ params.number_filter_integer[1] }}</span><span v-else> ~</span></button>

                                <button type="button" v-if="params.project" class="btn button-remove-filter" @click="
                        removeFilter('project_id')">
                                <span class="field">Project: </span>@{{ params.project.name }}</button>

                                <button type="button" v-if="params.total_query_filter_aggregate_integer" class="btn button-remove-filter" @click="
                        removeFilter('total')">
                                <span class="field">Total Cost: </span><span v-if="params.total_query_filter_aggregate_integer[0]">@{{ params.total_query_filter_aggregate_integer[0] }}</span><span v-else>~ </span><span v-if="params.total_query_filter_aggregate_integer[0] && params.total_query_filter_aggregate_integer[1]"> - </span><span v-if="params.total_query_filter_aggregate_integer[1]">@{{ params.total_query_filter_aggregate_integer[1] }}</span><span v-else> ~</span></button>

                                <button type="button" v-if="params.item_sku" class="btn button-remove-filter" @click="
                        removeFilter('item_sku')"><span
                                        class="field">Item SKU: </span>@{{ params.item_sku }}</button>

                                <button type="button" v-if="params.item_brand" class="btn button-remove-filter" @click="
                        removeFilter('item_brand')"><span
                                        class="field">Item Brand: </span>@{{ params.item_brand }}</button>

                                <button type="button" v-if="params.item_name" class="btn button-remove-filter" @click="
                        removeFilter('item_name')"><span
                                        class="field">Item Name: </span>@{{ params.item_name }}</button>

                                <button type="button" v-if="params['purchase_orders.created_at_filter_date']" class="btn button-remove-filter" @click="
                        removeFilter('submitted')"><span
                                        class="field">Submitted: </span><span v-if="params['purchase_orders.created_at_filter_date'][0]">@{{ params['purchase_orders.created_at_filter_date'][0] | date }}</span><span v-else>~ </span><span v-if="params['purchase_orders.created_at_filter_date'][0] && params['purchase_orders.created_at_filter_date'][1]"> - </span><span v-if="params['purchase_orders.created_at_filter_date'][1]">@{{ params['purchase_orders.created_at_filter_date'][1] | date }}</span><span v-else> ~</span></button>

                                <button type="button" v-if="params.user" class="btn button-remove-filter" @click="
                        removeFilter('user_id')">
                                <span class="field">Made by: </span>@{{ params.user.name }}</button>

                            </div>
                        </div>
                    </div>
                   <h1>same div!</h1>
                </div>

            </div>
        </div>
    </purchase-orders-all>
@endsection

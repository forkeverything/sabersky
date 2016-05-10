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

                                <!-- Quantity Filter -->
                                <div class="quantity filter" v-show="filter === 'quantity' ">
                                    <p>is</p>
                                    <integer-range-field :min.sync="minFilterValue" :max.sync="maxFilterValue"></integer-range-field>
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

                                <!-- Due (Date) Filter -->
                                <div class="due filter" v-show="filter === 'due'">
                                    <p>is from</p>
                                    <date-range-field :min.sync="minFilterValue" :max.sync="maxFilterValue"></date-range-field>
                                </div>

                                <!-- Requested (Date) Filter -->
                                <div class="requested filter" v-show="filter === 'requested'">
                                    <p>is from</p>
                                    <date-range-field :min.sync="minFilterValue" :max.sync="maxFilterValue"></date-range-field>
                                </div>

                                <!-- Requester (User) Filter -->
                                <div class="requester filter" v-show="filter === 'user_id'">
                                    <p>is</p>
                                    <team-member-selecter :name.sync="filterValue"></team-member-selecter>
                                </div>


                                <button class="button-add-filter btn btn-outline-blue"
                                        v-show="filter && (filterValue || minFilterValue || maxFiltervalue)"
                                        @click.stop.prevent="addFilter">Add Filter
                                </button>
                            </div>
                        </div>
                    </div>
                   <h1>same div!</h1>
                </div>

            </div>
        </div>
    </purchase-orders-all>
@endsection

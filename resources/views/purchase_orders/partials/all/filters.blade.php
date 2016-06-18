@include('layouts.partials.button-filters')
</button>
<div class="filter-dropdown dropdown-container left"
     v-show="showFiltersDropdown"
>
    <p>Show if</p>
    <select-picker :options="filterOptions" :name.sync="filter" :placeholder="'Select one...'"></select-picker>

    <!-- Number -->
    <div class="number filter" v-show="filter === 'number'">
        <p>is between</p>
        <integer-range-field :min.sync="minFilterValue" :max.sync="maxFilterValue"></integer-range-field>
    </div>

    <!-- Project -->
    <div class="project filter" v-show="filter === 'project_id'">
        <p>is</p>
        <user-projects-selecter :name.sync="filterValue"></user-projects-selecter>
    </div>

    <!-- Currency -->
    <div class="currency filter" v-show="filter === 'currency_id'">
        <p>is</p>
        <company-currency-selecter :id.sync="filterValue"  :currencies="companyCurrencies"></company-currency-selecter>
    </div>

    <!-- Total Cost -->
    <div class="total filter" v-show="filter === 'total'">
        <p>is between</p>
        <integer-range-field :min.sync="minFilterValue" :max.sync="maxFilterValue"></integer-range-field>
    </div>

    <!-- Item SKU -->
    <div class="item-brand filter" v-show="filter === 'item_sku'">
        <p>is</p>
        <item-sku-selecter :name.sync="filterValue"></item-sku-selecter>
    </div>

    <!-- Item Brand -->
    <div class="item-brand filter" v-show="filter === 'item_brand'">
        <p>is</p>
        <item-brand-selecter :name.sync="filterValue"></item-brand-selecter>
    </div>

    <!-- Item Name -->
    <div class="item-name filter" v-show="filter === 'item_name'">
        <p>is</p>
        <item-name-selecter :name.sync="filterValue"></item-name-selecter>
    </div>

    <!-- Submitted (Date) -->
    <div class="submitted filter" v-show="filter === 'submitted'">
        <p>is from</p>
        <date-range-field :min.sync="minFilterValue" :max.sync="maxFilterValue"></date-range-field>
    </div>

    <!-- Made By (User) -->
    <div class="made_by filter" v-show="filter === 'user_id'">
        <p>Staff</p>
        <staff-selecter :name.sync="filterValue"></staff-selecter>
    </div>


    <button class="button-add-filter btn btn-outline-blue"
            v-show="filter && (filterValue || minFilterValue || maxFiltervalue)"
            @click.stop.prevent="addFilter">Add Filter
    </button>
</div>
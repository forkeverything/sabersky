@include('layouts.partials.button-filters')
<div class="filter-dropdown dropdown-container left"
     v-show="showFiltersDropdown"
>
    <p>Show items where</p>
    <select-picker :options="filterOptions" :name.sync="filter"
                   :placeholder="'Select one...'"></select-picker>

    <div class="categories-list" v-show="filter === 'category'">
        <p>is</p>
        <product-category-selecter :value.sync="filterValue"></product-category-selecter>
    </div>

    <!-- Brands Filter -->
    <div class="brands-list" v-show="filter === 'brand'">
        <p>is</p>
        <item-brand-selecter :name.sync="filterValue"></item-brand-selecter>
    </div>

    <!-- Names Filter -->
    <div class="names-list" v-show="filter === 'name'">
        <p>is</p>
        <item-name-selecter :name.sync="filterValue"></item-name-selecter>
    </div>

    <!-- Projects Filter -->
    <div class="projects-list" v-show="filter === 'project'">
        <p>is</p>
        <user-projects-selecter :name.sync="filterValue"></user-projects-selecter>
    </div>

    <!-- Add Filter Button -->
    <button class="button-add-filter btn btn-outline-blue"
            v-show="filter && filterValue"
            @click.stop.prevent="addFilter">Add Filter
    </button>
</div>
<div class="pr-filters dropdown" v-dropdown-toggle="showFiltersDropdown">
    <button type="button"
            class="btn button-show-filters-dropdown button-toggle-dropdown"
            v-if="response.data"
    >Filters <i
                class="fa fa-caret-down"></i>
    </button>
    <div class="filter-dropdown dropdown-container left"
         v-show="showFiltersDropdown"
    >
        <p>Show if</p>
        <select-picker :options="filterOptions" :name.sync="filter" :placeholder="'Select one...'"></select-picker>

        <div class="number-filter" v-show="filter === 'number'">
            <p>is between</p>
            <div class="integer-range-field">
                <input type="number" class="form-control" v-model="numberFilterMin" min="0">
                <span class="dash">-</span>
                <input type="number" class="form-control" v-model="numberFilterMax" min="0">
            </div>
        </div>

        <div class="project-filter" v-show="filter === 'project'">
            <p>is</p>
            <select-picker :options="projects" :name.sync="filterProject"
                           :placeholder="'Pick a Project...'"></select-picker>
        </div>

        <button class="button-add-filter btn btn-outline-blue"
                v-show="filter"
                @click.stop.prevent="addPRsFilter">Add Filter
        </button>
    </div>
</div>
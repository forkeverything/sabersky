<div class="active-filters">

    <button type="button" v-if="activeFilters.number_filter_integer" class="btn button-remove-filter" @click="
                        removeFilter('number')">
    <span class="field">Number: </span><span v-if="activeFilters.number_filter_integer[0]">@{{ activeFilters.number_filter_integer[0] }}</span><span v-else>~ </span><span v-if="activeFilters.number_filter_integer[0] && activeFilters.number_filter_integer[1]"> - </span><span v-if="activeFilters.number_filter_integer[1]">@{{ activeFilters.number_filter_integer[1] }}</span><span v-else> ~</span></button>

    <button type="button" v-if="activeFilters.project" class="btn button-remove-filter" @click="
                        removeFilter('project_id')">
    <span class="field">Project: </span>@{{ activeFilters.project.name }}</button>

    <button type="button" v-if="activeFilters.quantity_filter_integer" class="btn button-remove-filter" @click="
                        removeFilter('quantity')">
    <span class="field">Quantity: </span><span v-if="activeFilters.quantity_filter_integer[0]">@{{ activeFilters.quantity_filter_integer[0] }}</span><span v-else>~ </span><span v-if="activeFilters.quantity_filter_integer[0] && activeFilters.quantity_filter_integer[1]"> - </span><span v-if="activeFilters.quantity_filter_integer[1]">@{{ activeFilters.quantity_filter_integer[1] }}</span><span v-else> ~</span></button>


    <button type="button" v-if="activeFilters.item_brand" class="btn button-remove-filter" @click="
                        removeFilter('item_brand')"><span
            class="field">Item Brand: </span>@{{ activeFilters.item_brand }}</button>

    <button type="button" v-if="activeFilters.item_name" class="btn button-remove-filter" @click="
                        removeFilter('item_name')"><span
            class="field">Item Name: </span>@{{ activeFilters.item_name }}</button>

    <button type="button" v-if="activeFilters.due_filter_date" class="btn button-remove-filter" @click="
                        removeFilter('due')"><span
            class="field">Due: </span><span v-if="activeFilters.due_filter_date[0]">@{{ activeFilters.due_filter_date[0] | date }}</span><span v-else>~ </span><span v-if="activeFilters.due_filter_date[0] && activeFilters.due_filter_date[1]"> - </span><span v-if="activeFilters.due_filter_date[1]">@{{ activeFilters.due_filter_date[1] | date }}</span><span v-else> ~</span></button>

    <button type="button" v-if="activeFilters['purchase_requests.created_at_filter_date']" class="btn button-remove-filter" @click="
                        removeFilter('requested')"><span
            class="field">Requested: </span><span v-if="activeFilters['purchase_requests.created_at_filter_date'][0]">@{{ activeFilters['purchase_requests.created_at_filter_date'][0] | date }}</span><span v-else>~ </span><span v-if="activeFilters['purchase_requests.created_at_filter_date'][0] && activeFilters['purchase_requests.created_at_filter_date'][1]"> - </span><span v-if="activeFilters['purchase_requests.created_at_filter_date'][1]">@{{ activeFilters['purchase_requests.created_at_filter_date'][1] | date }}</span><span v-else> ~</span></button>

    <button type="button" v-if="activeFilters.user" class="btn button-remove-filter" @click="
                        removeFilter('user_id')">
    <span class="field">User: </span>@{{ activeFilters.user.name }}</button>

</div>
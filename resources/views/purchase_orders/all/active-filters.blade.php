<button type="button" v-if="params.number_filter_integer" class="btn button-remove-filter" @click="
                        removeFilter('number')">
<span class="field">Number: </span><span v-if="params.number_filter_integer[0]">@{{ params.number_filter_integer[0] }}</span><span v-else>~ </span><span v-if="params.number_filter_integer[0] && params.number_filter_integer[1]"> - </span><span v-if="params.number_filter_integer[1]">@{{ params.number_filter_integer[1] }}</span><span v-else> ~</span></button>

<button type="button" v-if="params.project" class="btn button-remove-filter" @click="
                        removeFilter('project_id')">
<span class="field">Project: </span>@{{ params.project.name }}</button>

<button type="button" v-if="params.total_filter_integer" class="btn button-remove-filter" @click="
                        removeFilter('total')">
<span class="field">Total Cost: </span><span v-if="params.total_filter_integer[0]">@{{ params.total_filter_integer[0] }}</span><span v-else>~ </span><span v-if="params.total_filter_integer[0] && params.total_filter_integer[1]"> - </span><span v-if="params.total_filter_integer[1]">@{{ params.total_filter_integer[1] }}</span><span v-else> ~</span></button>

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
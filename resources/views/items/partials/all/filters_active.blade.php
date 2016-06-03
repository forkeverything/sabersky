<button type="button" v-if="params.category" class="btn button-remove-filter" @click="
                        removeFilter('category')"><span
        class="field">Category: </span>@{{ params.category }}</button>

<button type="button" v-if="params.brand" class="btn button-remove-filter" @click="
                        removeFilter('brand')"><span
        class="field">Brand: </span>@{{ params.brand }}</button>

<button type="button" v-if="params.name" class="btn button-remove-filter" @click="
                        removeFilter('name')"><span
        class="field">Name: </span>@{{ params.name }}</button>

<button type="button" v-if="params.project" class="btn button-remove-filter" @click="
                        removeFilter('project')"><span
        class="field">Project: </span>@{{ params.project }}</button>
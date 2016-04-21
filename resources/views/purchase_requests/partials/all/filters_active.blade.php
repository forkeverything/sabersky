<div class="active-filters">

    <button type="button" v-if="activeNumberFilter" class="btn button-remove-filter" @click="
                        removeFilter('number')">
    <span class="field">Number: </span><span v-if="activeNumberFilter[0]">@{{ activeNumberFilter[0] }}</span><span v-else>~ </span><span v-if="activeNumberFilter[0] && activeNumberFilter[1]"> - </span><span v-if="activeNumberFilter[1]">@{{ activeNumberFilter[1] }}</span><span v-else> ~</span></button>

    <button type="button" v-if="activeProjectFilter" class="btn button-remove-filter" @click="
                        removeFilter('project_id')">
    <span class="field">Project: </span>@{{ activeProjectFilter.name }}</button>

</div>
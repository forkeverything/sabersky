@extends('layouts.app')
@section('content')
    <items-all inline-template>
        <div id="items-all" class="container">
            <section class="top align-end">
                <add-item-modal></add-item-modal>
            </section>
            <div class="page-body">
                <div class="items-control">
                    <div class="filter dropdown" v-dropdown-toggle="itemsFilterDropdown">
                        <button type="button" class="btn button-show-filter-dropdown">Filter items <i
                                    class="fa fa-caret-down"></i></button>
                        <div class="filter-dropdown dropdown-container left"
                             v-show="itemsFilterDropdown"
                        >
                            <p>Show items where</p>
                            <select-picker :options="filterOptions" :name.sync="filter"
                                           :placeholder="'Select one...'"></select-picker>
                            <div class="brands-list" v-show="filter === 'brand'">
                                <p>is</p>
                                <select id="items-filter-brand-select"><option></option></select>
                            </div>
                            <div class="projects-list" v-show="filter === 'project'">
                                <p>is</p>
                                <select-picker :options="projects" :name.sync="filterProject"
                                               :placeholder="'Pick a Project...'"></select-picker>
                            </div>
                            <button class="button-add-filter btn btn-outline-blue"
                                    v-show="filter && (filterBrand || filterProject)"
                                    @click.stop.prevent="addItemsFilter">Add Filter
                            </button>
                        </div>
                    </div>
                    <form class="form-item-search" @submit.prevent="searchItemQuery">
                        <input class="form-control input-item-search"
                               type="text"
                               placeholder="Search by SKU, Brand or Name"
                               @keyup="searchItemQuery"
                               v-model="searchTerm"
                               :class="{
                                    'active': searchTerm && searchTerm.length > 0
                               }"
                        >
                    </form>
                    <div class="active-filters">
                        <button type="button" v-if="activeBrandFilter" class="btn button-remove-filter" @click="
                        removeFilter('brand')"><span class="field">Brand: </span>@{{ decodeURIComponent(activeBrandFilter) }}</button>
                        <button type="button" v-if="activeProjectFilter" class="btn button-remove-filter" @click="
                        removeFilter('project')"><span
                                class="field">Project: </span>@{{ activeProjectFilter.name }}</button>
                    </div>
                </div>
                <div class="table-responsive table-items">
                    <!-- Items Table -->
                    <table class="table table-hover table-standard">
                        <thead>
                        <tr>
                            <th></th>
                            <th class="clickable"
                            @click="changeSort('name')"
                            :class="{
                                            'current_asc': sort === 'name' && order === 'asc',
                                            'current_desc': sort === 'name' && order === 'desc'
                                        }"
                            >
                            Details</th>
                            <th class="clickable"
                            @click="changeSort('sku')"
                            :class="{
                                            'current_asc': sort === 'sku' && order === 'asc',
                                            'current_desc': sort === 'sku' && order === 'desc'
                                        }"
                            >
                                SKU</th>
                            <th>Projects</th>
                        <tr>
                        </thead>
                        <tbody>
                        <template v-for="item in items">
                            <tr class="clickable item-row" v-if="item && item.id">
                                <td class="col-thumbnail">
                                    <div class="item-thumbnail">
                                        <img :src="item.photos[0].thumbnail_path"
                                             alt="Item Thumbnail"
                                             v-if="item.photos.length > 0"
                                        >
                                        <img src="/images/icons/thumbnail-item.svg"
                                             alt="Item Thumbnail Placeholder"
                                             v-else
                                        >
                                    </div>
                                </td>
                                <td class="col-details">
                                        <span class="brand" v-if="item.brand">@{{ item.brand }}</span>
                                        <span class="name">@{{ item.name }}</span>
                                    <span class="item-specification">
                                        <text-clipper :text="item.specification"></text-clipper></span>
                                </td>
                                <td class="col-sku no-wrap">
                                    <span class="has-sku" v-if="item.sku">@{{ item.sku }}</span>
                                    <span v-else>-</span>
                                </td>
                                <td class="no-wrap">
                                    <ul class="list-unstyled" v-if="getItemProjectNames(item).length > 0">
                                        <li v-for="project in getItemProjectNames(item)">@{{ project }}</li>
                                    </ul>
                                    <em v-else>None</em>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
                <div class="page-controls">
                    <per-page-picker :response="response" :req-function="getCompanyItems"></per-page-picker>
                    <paginator :response="response" :req-function="getCompanyItems"></paginator>
                </div>
            </div>
        </div>
    </items-all>
@endsection
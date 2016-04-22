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
                                <select-picker :options="projects" :name.sync="filterValue"
                                               :placeholder="'Pick a Project...'"></select-picker>
                            </div>

                            <!-- Add Filter Button -->
                            <button class="button-add-filter btn btn-outline-blue"
                                    v-show="filter && filterValue"
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
                        <button type="button" v-show="queryParams.brand" class="btn button-remove-filter" @click="
                        removeFilter('brand')"><span
                                class="field">Brand: </span>@{{ queryParams.brand }}</button>

                        <button type="button" v-show="queryParams.name" class="btn button-remove-filter" @click="
                        removeFilter('name')"><span
                                class="field">Name: </span>@{{ queryParams.name }}</button>

                        <button type="button" v-show="queryParams.project" class="btn button-remove-filter" @click="
                        removeFilter('project')"><span
                                class="field">Project: </span>@{{ queryParams.project.name }}</button>
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
                            <tr class="item-row" v-if="item && item.id">
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
                                    <a class="link-item-single" :href="'/items/' + item.id" alt="single item link">
                                        <div class="brand" v-if="item.brand"><span>@{{ item.brand }}</span></div>
                                        <div class="name"><span>@{{ item.name }}</span></div>
                                    </a>
                                    <span class="item-specification">
                                        <text-clipper :text="item.specification"></text-clipper></span>
                                </td>
                                <td class="col-sku no-wrap">
                                    <a :href="'/items/' + item.id" alt="single item link" v-if="item.sku">
                                        <span class="has-sku">@{{ item.sku }}</span>
                                    </a>
                                    <span v-else>-</span>
                                </td>
                                <td class="no-wrap">
                                    <ul class="list-unstyled" v-if="getItemProjects(item).length > 0">
                                        <li v-for="project in getItemProjects(item)">
                                            <a :href="'/projects/' + project.id" alt="Link to project" class="capitalize">
                                                @{{ project.name }}
                                            </a>
                                        </li>
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
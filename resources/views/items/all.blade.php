@extends('layouts.app')
@section('content')
    <items-all inline-template>
        <div id="items-all" class="container">
            <section class="top children-right">
                    <button id="button-make-purchase-request"
                            class="btn btn-solid-green"
                            @click="showAddItemModal"
                    >Add New Item</button>
            </section>
            <div class="page-body">
                <div class="items-control">
                        <div class="filter dropdown"  v-dropdown-toggle="itemsFilterDropdown">
                            <button type="button" class="btn">Filter items <i class="fa fa-caret-down"></i></button>
                            <div class="filter-dropdown dropdown-container left"
                                 v-show="itemsFilterDropdown"
                                >
                                    <p>Show items where</p>
                                    <select-picker :options="filterOptions" :name.sync="filter" :placeholder="'Select one...'"></select-picker>
                                    <div class="brands-list" v-show="filter === 'brand'">
                                        <p>is</p>
                                        <select-picker :options="brands" :name.sync="filterBrand" :placeholder="'Select a brand...'"></select-picker>
                                    </div>
                            </div>
                        </div>
                        <form class="form-item-search">
                            <input type="text" placeholder="Search by SKU, Brand or Name" class="form-control">
                        </form>
                </div>
                <div class="table-responsive table-items">
                    <!-- Items Table -->
                    <table class="table table-hover table-standard">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Details</th>
                            <th>SKU</th>
                            <th>Projects</th>
                        <tr>
                        </thead>
                        <tbody>
                        <template v-for="item in items">
                            <tr class="clickable item-row">
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
                                    <div class="item-brand-name">
                                        <span class="brand" v-if="item.brand">@{{ item.brand }}</span><span class="name">@{{ item.name }}</span>
                                    </div>
                                    <span class="item-specification"><text-clipper :text="item.specification"></text-clipper></span>
                                </td>
                                <td class="col-sku no-wrap">
                                    <span class="has-sku" v-if="item.sku">@{{ item.sku }}</span>
                                    <span v-else>-</span>
                                </td>
                                <td class="no-wrap">
                                    <ul class="list-unstyled" v-if="item.projects.length > 0">
                                        <li v-for="project in item.projects">@{{ project.name }}</li>
                                    </ul>
                                    <em v-else>None</em>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
            <add-item-modal :visible.sync="visibleAddItemModal"></add-item-modal>
        </div>
    </items-all>
@endsection
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
                <div class="table-responsive table-items">
                    <!-- Items Table -->
                    <table class="table table-hover table-standard">
                        <thead>
                        <tr>
                            <th></th>
                            <th>SKU</th>
                            <th>Brand</th>
                            <th>Name</th>
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
                                <td>
                                    <span class="sku" v-if="item.sku">@{{ item.sku }}</span>
                                    <span v-else>-</span>
                                </td>
                                <td>
                                    <span class="brand" v-if="item.brand">@{{ item.brand }}</span>
                                    <span v-else>universal</span>
                                </td>
                                <td class="col-name">
                                    <span class="item-name">@{{ item.name }}</span>
                                    <span class="item-specification">@{{ item.specification }}</span>
                                </td>
                                <td>
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
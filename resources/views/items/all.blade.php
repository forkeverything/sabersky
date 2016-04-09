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
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Name</th>
                            <th>Variants</th>
                            <th>Projects</th>
                        <tr>
                        </thead>
                        <tbody>
                        <template v-for="item in uniqueItems">
                            <tr class="clickable item-row">
                                <td class="td-thumbnail">
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
                                    @{{ item.name }}
                                </td>
                                <td>@{{ getVariants(item).length }}</td>
                                <td>
                                    @{{ getProjects(item) }}
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
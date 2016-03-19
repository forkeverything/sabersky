@extends('layouts.app')
@section('content')
    <items-all inline-template>
        <div id="items-all" class="container">
            <a href="{{ route('dashboard') }}" class="back-link no-print"><i class="fa  fa-arrow-left fa-btn"></i>Dashboard</a>
            <div class="page-header">
                <h1 class="page-title">Items</h1>
            </div>
            <div class="page-body"
                 v-if="items.length > 0"
            >
                {{--<div class="item-gallery">--}}
                {{--<template v-for="chunk in uniqueItems | chunk 6">--}}
                {{--<div class="row">--}}
                {{--<template v-for="item in chunk">--}}
                {{--<div class="col-ms-2">--}}
                {{--<a href="#" class="item-link">--}}
                {{--<div class="item-thumbnail">--}}
                {{--<img--}}
                {{--v-if="item.photos.length > 0"--}}
                {{--:src="item.photos[0].thumbnail_path"--}}
                {{-->--}}
                {{--<span--}}
                {{--v-else--}}
                {{--class="item-placeholder">--}}
                {{--<i class="fa fa-wrench"></i>--}}
                {{--</span>--}}
                {{--</div>--}}
                {{--<span class="name">@{{ item.name }}</span>--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--</template>--}}
                {{--</div>--}}
                {{--</template>--}}
                {{--</div>--}}
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
        <span class="page-error"
              v-else
        >
            No items have been created. Make your first purchase request to create an item!
        </span>

        </div>
    </items-all>
@endsection
@section('scripts.footer')
    <script src="{{ asset('/js/page/items/all.js') }}" type="text/javascript"></script>

@endsection
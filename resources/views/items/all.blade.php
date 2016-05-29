@extends('layouts.app')
@section('content')
    <items-all inline-template>
        <div id="items-all" class="container">

            <div class="title-with-buttons">
                <h1>Items</h1>
                <div class="buttons">
                    <add-item-modal></add-item-modal>
                </div>
            </div>


                <!-- Control -->
                <div class="table-controls">
                    <div class="controls-left controls-filter-search">
                        <div class="filters with-search" v-dropdown-toggle="showFiltersDropdown">
                            <div class="dropdown">
                                @include('items.partials.all.filters')
                            </div>
                        </div>
                        @include('layouts.partials.form-search-repository')
                    </div>
                    <div class="active-filters">
                        @include('items.partials.all.filters_active')
                    </div>
                </div>

                <!-- Has Items -->
                <div v-if="hasItems">
                    <div class="table-responsive table-items">
                        @include('items.partials.all.table')
                    </div>

                    <div class="page-controls">
                        <per-page-picker :response="response" :req-function="makeRequest"></per-page-picker>
                        <paginator :response="response" :req-function="makeRequest"></paginator>
                    </div>
                </div>

                <!-- Empty Stage -->
                <div class="empty-stage" v-else>
                    <i class="fa  fa-legal"></i>
                    <h4>No Items Found</h4>
                    <p>There doesn't seem to be any items that match your criteria. Try <a class="dotted clickable" @click="removeAllFilters">removing</a> filters or <a class="dotted clickable" @click="clearSearch">clear</a> the search to see more Items.</p>
                </div>

        </div>
    </items-all>
@endsection
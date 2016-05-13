@extends('layouts.app')
@section('content')
    <items-all inline-template>
        <div id="items-all" class="container">

            <!-- Add Item -->
            <section class="top align-end">
                <add-item-modal></add-item-modal>
            </section>

            <div class="page-body">

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
                    <h3>No Items Found</h3>
                    <p>There doesn't seem to be any items that match your criteria. Try <a class="dotted clickable" @click="removeAllFilters">removing</a> filters or <a class="dotted clickable" @click="clearSearch">clear</a> the search to see more Items.</p>
                </div>
            </div>
        </div>
    </items-all>
@endsection
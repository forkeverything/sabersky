@extends('layouts.app')
@section('content')
    <purchase-requests-all inline-template>
        <div class="container" id="purchase-requests-all">
            @can('pr_make')
            <section class="page-top children-right">
                <a href="{{ route('makePurchaseRequest') }}">
                    <button class="btn btn-solid-green" id="button-make-purchase-request">Make Purchase Request</button>
                </a>
            </section>
            @endcan
            <div class="page-body">
                <div class="pr-controls">
                    <div class="pr-paginate">
                        <ul class="list-unstyled list-inline">
                            <li class="paginate-link"
                                v-for="n in lastPage"
                                :class="{
                                        'active': n + 1 === currentPage
                                    }"
                            @click="goToPage(n + 1)"
                            >
                            @{{ n + 1 }}
                            </li>
                        </ul>
                    </div>
                    <div class="pr-filters dropdown" v-dropdown-toggle="showFilterDropdown">
                        <button type="button"
                                class="btn button-dotted button-show-filter-dropdown button-toggle-dropdown"
                        >Filter: @{{ filter.label | capitalize }} <i class="fa fa-chevron-down"></i></button>
                        <div class="dropdown-filters dropdown-container"
                             v-show="showFilterDropdown"
                        >
                            <span class="dropdown-title">View only</span>
                            <ul class="list-unstyled">
                                <li class="pr-dropdown-item"
                                    v-for="filter in filters"
                                    @click="changeFilter(filter)"
                                >
                                    @{{ filter.label }}
                                </li>
                                <li class="pr-dropdown-item" id="pr-filter-urgent">Show Urgent Requests only</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="container-purchase-requests">
                        <template v-for="purchaseRequest in response.data">
                            <div class="single-purchase-request" v-if="purchaseRequest.id">
                                <div class="thumbnail">
                                    <i class="fa fa-shopping-basket"></i>
                                </div>
                                <div class="details">
                                    <h5 class="item-name">@{{ purchaseRequest.item_name }}</h5>
                                    <span class="date-due">@{{ purchaseRequest.due }}</span>
                                    <span class="date-requested">@{{ purchaseRequest.created_at }}</span>
                                    <span class="project">@{{ purchaseRequest.project_name }}</span>
                                    <div class="specification">@{{ purchaseRequest.item_specification }}</div>
                                    <span class="quantity">@{{ purchaseRequest.quantity }}</span>
                                    <div class="requestor">@{{ purchaseRequest.requester_name }}</div>
                                </div>
                            </div>
                        </template>
                </div>
            </div>
        </div>
    </purchase-requests-all>
@endsection

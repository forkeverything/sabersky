@extends('layouts.app')
@section('content')
    <purchase-requests-all inline-template>
        <div class="container" id="purchase-requests-all">
            @can('pr_make')
            <div class="top align-end">
                <a href="{{ route('makePurchaseRequest') }}" class="link-make-pr">
                    <button class="btn btn-solid-green" id="button-make-purchase-request">Make Purchase Request</button>
                </a>
            </div>
            @endcan
            <div class="page-body">
                <div class="pr-controls">
                    <div class="controls-left">
                        @include('purchase_requests.partials.all.filters')
                    </div>
                    <div class="controls-right">
                        <div class="pr-states dropdown" v-dropdown-toggle="showStatesDropdown">
                            <button type="button"
                                    class="btn button-show-states-dropdown button-toggle-dropdown"
                                    v-if="response.data"
                            >@{{ response.data.query_parameters.state | capitalize }} <i
                                        class="fa fa-caret-down"></i>
                            </button>
                            <div class="dropdown-states dropdown-container right"
                                 v-show="showStatesDropdown"
                            >
                                <span class="dropdown-title">View State</span>
                                <ul class="list-unstyled">
                                    <li class="pr-dropdown-item"
                                        v-for="state in states"
                                    @click="changeState(state)"
                                    :class="{
                                        'all': state.name === 'all'
                                    }"
                                    >
                                    @{{ state.label }}
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="control-urgent">
                            <input type="checkbox"
                                   id="checkbox-pr-urgent"
                                   v-model="urgent"
                            @click="toggleUrgentOnly"
                            >
                            <label class="clickable"
                                   for="checkbox-pr-urgent"
                            ><i class="fa fa-warning badge-urgent"></i> Urgent only</label>
                        </div>
                    </div>
                    @include('purchase_requests.partials.all.filters_active')
                </div>
                <div class="has-purchase-requests" v-if="response.total > 0">
                    <div class="pr-bag table-responsive">
                        <table class="table table-bordered table-hover table-standard table-purchase-requests-all">
                            @include('purchase_requests.partials.all.table-head')
                            <tbody>
                            <template v-for="purchaseRequest in purchaseRequests">
                                <tr class="row-single-pr">
                                    <td class="no-wrap col-number"><a :href="'/purchase_requests/' + purchaseRequest.id"
                                                                      alt="Link to single PR"
                                                                      class="underline">#@{{ purchaseRequest.number }}</a><span
                                                v-if="purchaseRequest.urgent" class="badge-urgent"> <i
                                                    class="fa fa-warning"></i></span></td>
                                    <td class="col-project"><a :href="'/projects/' + purchaseRequest.project.id"
                                                               alt="project link">@{{ purchaseRequest.project.name }}</a>
                                    </td>
                                    <td class="col-quantity">@{{ purchaseRequest.quantity }}</td>
                                    <td class="col-item">
                                        <div class="item-sku"
                                             v-if="purchaseRequest.item.sku && purchaseRequest.item.sku.length > 0">@{{ purchaseRequest.item.sku }}</div>
                                        <a :href="'/items/' + purchaseRequest.item.id" alt="item link">
                                            <span class="item-brand"
                                                  v-if="purchaseRequest.item.brand.length > 0">@{{ purchaseRequest.item.brand }}</span>
                                            <span class="item-name">@{{ purchaseRequest.item.name }}</span>
                                        </a>
                                        <ul class="item-image-gallery list-unstyled list-inline"
                                            v-if="purchaseRequest.item.photos.length > 0">
                                            <li v-for="photo in purchaseRequest.item.photos">
                                                <a :href="photo.path" rel="group" class="fancybox"><img
                                                            :src="photo.thumbnail_path"
                                                            alt="Purchase Request Item Photo"></a>
                                            </li>
                                        </ul>
                                        <span class="item-specification">
                                        <text-clipper :text="purchaseRequest.item.specification"></text-clipper></span>
                                    </td>
                                    <td class="no-wrap">
                                        <span class="pr-due">@{{ purchaseRequest.due | easyDate }}</span>
                                    </td>
                                    <td>
                                        <span class="pr-requested">@{{ purchaseRequest.created_at | diffHuman }}</span>
                                    </td>
                                    <td>
                                        <span class="pr-requester">@{{ purchaseRequest.user.name | capitalize }}</span>
                                    </td>
                                </tr>
                            </template>
                            </tbody>
                        </table>
                    </div>
                    <div class="page-controls">
                        <per-page-picker :response="response" :req-function="fetchPurchaseRequests"></per-page-picker>
                        <paginator :response="response" :req-function="fetchPurchaseRequests"></paginator>
                    </div>
                </div>
                <div class="no-purchase-requests empty-stage" v-else>
                    <i class="fa fa-shopping-basket"></i>
                    <h3>Could not find any Purchase Requests</h3>
                    <p>Try changing filters or <a class="dotted clickable" @click="removeAllFilters">removing all</a> filters to see more requests.</p>
                </div>

            </div>
        </div>
    </purchase-requests-all>
@endsection

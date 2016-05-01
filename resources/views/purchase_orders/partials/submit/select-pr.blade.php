<div class="purchase_requests"
     :class="{
        'inactive': ! projectID
     }"
>
    <div class="overlay"></div>
    <label class="display-block">Purchase Requests</label>
    <div class="pr-controls">
        <form class="form-pr-search" @submit.prevent="searchPurchaseRequests">
            <input class="form-control input-item-search"
                   type="text"
                   placeholder="Search by # Number, Item (Brand or Name) or Requester"
            @keyup="searchPurchaseRequests"
            v-model="searchTerm"
            :class="{
                                    'active': searchTerm && searchTerm.length > 0
                               }"
            >
        </form>
    </div>
    <div class="pr-bag" v-show="hasPurchaseRequests">
        <div class="table-responsive">
            <table class="table table-hover table-standard table-purchase-requests-po-submit">
                <thead>
                <tr>
                    <th class="heading-center heading-select-all">
                        <div class="checkbox styled">
                            <label>
                                <i class="fa fa-check-square-o checked" v-show="allPurchaseRequestsChecked"></i>
                                <i class="fa fa-square-o empty" v-else></i>
                                <input class="clickable hidden"
                                       type="checkbox"
                                    @change="selectAllPR"
                                :checked="allPurchaseRequestsChecked"
                                >
                            </label>
                        </div>
                    </th>
                    <th class="clickable"
                    @click="changeSort('number')"
                    :class="{
                                            'current_asc': sort === 'number' && order === 'asc',
                                            'current_desc': sort === 'number' && order === 'desc'
                                        }"
                    >
                    PR
                    </th>
                    <th class="clickable"
                    @click="changeSort('item_name')"
                    :class="{
                                            'current_asc': sort === 'item_name' && order === 'asc',
                                            'current_desc': sort === 'item_name' && order === 'desc'
                                        }"
                    >
                    Item
                    </th>
                    <th class="clickable"
                    @click="changeSort('due')"
                    :class="{
                                            'current_asc': sort === 'due' && order === 'asc',
                                            'current_desc': sort === 'due' && order === 'desc'
                                        }"
                    >
                    Due</th>
                </tr>
                </thead>
                <tbody>
                <template v-for="purchaseRequest in purchaseRequests">
                    <tr class="row-single-pr">
                        <td class="col-checkbox">

                            <div class="checkbox styled">
                                <label>
                                    <i class="fa fa-check-square-o checked" v-if="alreadySelectedPR(purchaseRequest)"></i>
                                    <i class="fa fa-square-o empty" v-else></i>
                                    <input class="clickable hidden"
                                           type="checkbox"
                                    @change="selectPR(purchaseRequest)"
                                    :checked="alreadySelectedPR(purchaseRequest)"
                                    >
                                </label>
                            </div>
                        </td>
                        <td class="no-wrap col-number">
                            <a class="dotted clickable" @click="showSinglePR(purchaseRequest)">#@{{ purchaseRequest.number }}</a>
                        </td>
                        <td class="col-item">
                            <a class="dotted clickable" @click="showSinglePR(purchaseRequest)">
                                            <span class="item-brand"
                                                  v-if="purchaseRequest.item.brand.length > 0">@{{ purchaseRequest.item.brand }} - </span>
                            <span class="item-name">@{{ purchaseRequest.item.name }}</span>
                            </a>
                            <div class="bottom">
                                <span
                                        v-if="purchaseRequest.urgent" class="badge-urgent with-tooltip" v-tooltip title="Urgent Request" data-placement="bottom"> <i
                                            class="fa fa-warning"></i></span>
                                <div class="quantity"><label>QTY:</label> @{{ purchaseRequest.quantity }}</div>
                            </div>
                        </td>
                        <td class="col-due no-wrap">
                            <span class="pr-due">@{{ purchaseRequest.due | date }}</span>
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
        <div class="page-controls bottom">
            <per-page-picker :response="response" :req-function="fetchPurchaseRequests"></per-page-picker>
            <paginator :response="response"></paginator>
        </div>
    </div>
    <div class="empty-stage" v-else>
        <i class="fa  fa-hand-rock-o"></i>
        <h3>No Purchase Requests</h3>
        <p>We couldn't find any requests to fulfill. Try selecting a different Project or <a
                    class="dotted clickable" @click="clearSearch">clear</a> the search.</p>
    </div>
</div>
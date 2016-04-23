<section class="step purchase_requests" v-show="projectID">
    <h5>Select Requests to Fulfill</h5>
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
        <paginator :response="response"></paginator>
    </div>
    <div class="pr-bag table-responsive" v-if="hasPurchaseRequests">
        <table class="table table-bordered table-hover table-standard table-purchase-requests-po-submit">
            <thead>
            <tr>
                <th class="heading-center">Add</th>
                <th class="clickable"
                @click="changeSort('number')"
                :class="{
                                            'current_asc': sort === 'number' && order === 'asc',
                                            'current_desc': sort === 'number' && order === 'desc'
                                        }"
                >
                PR
                </th>
                <th class="clickable heading-center"
                @click="changeSort('quantity')"
                :class="{
                                            'current_asc': sort === 'quantity' && order === 'asc',
                                            'current_desc': sort === 'quantity' && order === 'desc'
                                        }"
                >
                Qty
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
                <th class="clickable"
                @click="changeSort('created_at')"
                :class="{
                                            'current_asc': sort === 'created_at' && order === 'asc',
                                            'current_desc': sort === 'created_at' && order === 'desc'
                                        }"
                >
                Requested
                </th>
                <th class="clickable"
                @click="changeSort('requester_name')"
                :class="{
                                            'current_asc': sort === 'requester_name' && order === 'asc',
                                            'current_desc': sort === 'requester_name' && order === 'desc'
                                        }"
                >
                By
                </th>
            </tr>
            </thead>
            <tbody>
            <template v-for="purchaseRequest in purchaseRequests">
                <tr class="row-single-pr">
                <td>
                    <input class="clickable"
                            type="checkbox"
                            @change="selectPR(purchaseRequest)"
                            :checked="alreadySelectedPR(purchaseRequest)"
                    >
                </td>
                    <td class="no-wrap col-number">
                        #@{{ purchaseRequest.number }}<span
                                v-if="purchaseRequest.urgent" class="badge-urgent"> <i
                                    class="fa fa-warning"></i></span></td>
                    <td class="col-quantity">
                        @{{ purchaseRequest.quantity }}</td>
                    <td class="col-item">
                        <div class="item-sku"
                             v-if="purchaseRequest.item.sku && purchaseRequest.item.sku.length > 0">@{{ purchaseRequest.item.sku }}</div>
                                            <span class="item-brand"
                                                  v-if="purchaseRequest.item.brand.length > 0">@{{ purchaseRequest.item.brand }}</span>
                        <span class="item-name">@{{ purchaseRequest.item.name }}</span>
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
    <div class="empty-stage" v-else>
        <i class="fa  fa-hand-rock-o"></i>
        <h3>No Purchase Requests</h3>
        <p>We couldn't find any requests to fulfill. Try selecting a different Project or <a class="dotted clickable" @click="clearSearch">clear</a> the search.</p>
    </div>
</section>
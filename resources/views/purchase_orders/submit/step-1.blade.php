<div id="po-submit-1" v-show="step === 1" class="submit-step row animated" transition="slide">
    <div class="col-md-8">
        <div class="page-body select-vendor visible-xs">
            @include('purchase_orders.submit.select-vendor')
        </div>
        <div class="page-body select-pr">
            <h3>Search For Requests</h3>
            <select-line-items :line-items.sync="lineItems"></select-line-items>
        </div>
    </div>
    <div class="col-md-4">
        <div class="page-body vendor hidden-xs">
            @include('purchase_orders.submit.select-vendor')
        </div>
        <div class="page-body line-items">
            <h3>Selected Items</h3>
            <div class="table-responsive line-items-container" v-show="hasLineItems">
                <!-- Line Items Table -->
                <table class="table table-standard table-striped">
                    <thead>
                    <tr>
                        <th>Item(s)</th>
                    <tr>
                    </thead>
                    <tbody>
                    <template v-for="(index, lineItem) in lineItems">
                        <tr>
                            <td class="col-item">
                                <div class="brand-name display-block">
                                    <span class="brand" v-if="lineItem.item.brand">@{{ lineItem.item.brand }} - </span>
                                    <span class="name">@{{ lineItem.item.name }}</span>
                                </div>
                                <div class="line-item-details">
                                    <span class="project">@{{ lineItem.project.name | capitalize }}</span><label>QTY: </label><span class="quantity">@{{ lineItem.quantity }}</span>
                                </div>
                                <button type="button" class="btn-close" @click="removeLineItem(lineItem)"><i class="fa fa-close"></i></button>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>
            <div class="empty-stage" v-else>
                <i class="fa fa-arrow-circle-left hidden-xs"></i>
                <i class="fa fa-arrow-circle-up visible-xs"></i>
                <h4>No Items Added</h4>
                <p>Add items by selecting some requests</p>
            </div>
            <div class="line-items-control" v-show="hasLineItems">
                <button type="button" class="btn btn-outline-grey btn-clear" @click="clearAllLineItems"><i class="fa fa-trash"></i> Clear All</button>
                <button type="button" class="btn btn-solid-blue btn-next" @click="goStep(2)" :disabled="! hasLineItems || ! vendor.id">Next Step <i class="fa fa-angle-double-right"></i></button>
            </div>
        </div>
    </div>
</div>
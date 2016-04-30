<div id="po-submit-1" v-show="step === 1" class="submit-step row animated" transition="slide">
    <div class="col-sm-8">
        <div class="page-body select-vendor visible-xs">
            @include('purchase_orders.partials.submit.select-vendor')
        </div>
        <div class="page-body select-pr">
            <h5>Find Requests</h5>
            <div class="project-selecter">
                <label class="display-block">Project</label>
                <user-projects-selecter :name.sync="projectID"></user-projects-selecter>
            </div>
            @include('purchase_orders.partials.submit.select-pr')
        </div>
    </div>
    <div class="col-sm-4">
        <div class="page-body vendor hidden-xs">
            @include('purchase_orders.partials.submit.select-vendor')
        </div>
        <div class="page-body line-items">
            <h5>Selected Items</h5>
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
                                <div class="details">
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
                <i class="fa fa-arrow-circle-left"></i>
                <h3>No Items Added</h3>
                <p>Add items by selecting requests from the right</p>
            </div>
            <div class="line-items-control" v-show="hasLineItems">
                <button type="button" class="btn btn-outline-grey btn-clear" @click="clearAllLineItems"><i class="fa fa-trash"></i> Clear All</button>
                <button type="button" class="btn btn-solid-blue btn-next" @click="goStep(2)">Next Step <i class="fa fa-angle-double-right"></i></button>
            </div>
        </div>
    </div>
</div>
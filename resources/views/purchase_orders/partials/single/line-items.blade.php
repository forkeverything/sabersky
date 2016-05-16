<h3>Items</h3>
<div class="line-items table-responsive">
    <!-- PO Single - Items Table -->
    <table class="table table-striped table-bordered">
        <thead>
        <tr>
            <th>#</th>
            <th>PR</th>
            <th>SKU</th>
            <th>Description</th>
            <th>Payable</th>
            <th>Delivery</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
        <tr>
        </tr>
        </thead>
        <tbody>
        <template v-for="(index, lineItem) in purchaseOrder.line_items">
            <tr>
                <td>@{{ index + 1 }}</td>
                <td>#@{{ lineItem.purchase_request.number }}</td>
                <td><span v-if="lineItem.purchase_request.item.sku"></span>@{{ lineItem.purchase_request.item.sku }}<span v-else>-</span></td>
                <td>
                    <span class="item-brand-name">
                        <span class="brand" v-if="lineItem.purchase_request.item.brand">@{{ lineItem.purchase_request.item.brand }} - </span>
                        <span class="name">@{{ lineItem.purchase_request.item.name }}</span>
                    </span>
                    <span class="item-specification">
                        @{{ lineItem.purchase_request.item.specification }}
                    </span>
                </td>
                <td>
                    @{{ lineItem.payable | easyDate }}
                </td>
                <td>
                    @{{ lineItem.delivery | easyDate }}
                </td>
                <td>
                    @{{ lineItem.quantity }}
                </td>
                <td>
                    @{{ lineItem.price }}
                </td>
                <td>
                    @{{ lineItem.total }}
                </td>
            </tr>
        </template>
        </tbody>
    </table>
</div>
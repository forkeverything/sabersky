<h3>Items</h3>
<div class="table-responsive">
    <!-- Line Items Table -->
    <table class="table table-standard table-items">
        <thead>
        <tr>
            <th>PR</th>
            <th>Item</th>
            <th class="required">QTY</th>
            <th class="required">Price</th>
            <th>Total</th>
        <tr>
        </tr>
        </thead>
        <tbody>
        <template v-for="(index, lineItem) in lineItems">
            <tr>
                <td>
                    <a class="dotted clickable" @click="showSinglePR(lineItem)">
                    #@{{ lineItem.number }}</a>
                </td>
                <td class="col-item no-wrap">
                    <a class="dotted clickable" @click="showSinglePR(lineItem)">
                                            <span class="item-brand"
                                                  v-if="lineItem.item.brand.length > 0">@{{ lineItem.item.brand }}
                                                - </span>
                    <span class="item-name">@{{ lineItem.item.name }}</span>
                    </a>
                    <div class="line-item-details">
                        <span class="project">@{{ lineItem.project.name | capitalize }}</span><label>QTY: </label><span
                                class="quantity">@{{ lineItem.quantity }}</span>
                    </div>
                    <div class="dates">
                        <div class="payable">
                            <input class="form-control input-date-payable hidden"
                                   type="text"
                                   v-datepicker
                                   button-only="true"
                                   v-model="lineItem.order_payable"
                            >
                            <span class="date-type">Payable</span>
                            <span v-show="lineItem.order_payable">@{{ lineItem.order_payable }}</span>
                        </div>
                        <div class="delivery">
                            <input class="form-control input-date-delivery hidden"
                                   type="text"
                                   v-datepicker
                                   button-only="true"
                                   v-model="lineItem.order_delivery"
                            >
                            <span class="date-type">Delivery</span>
                            <span v-show="lineItem.order_delivery">@{{ lineItem.order_delivery }}</span>
                        </div>
                    </div>
                </td>
                <td>
                    <number-input :model.sync="lineItem.order_quantity" :placeholder="'qty'"
                                  :class="['input-qty', 'form-control']"></number-input>
                </td>
                <td>
                    <number-input :model.sync="lineItem.order_price" :placeholder="'price'"
                                  :class="['input-price', 'form-control']"
                                  :decimal="currencyDecimalPoints"></number-input>
                </td>
                <td>
                    <strong>@{{ calculateTotal(lineItem) }}</strong>
                </td>
            </tr>
        </template>
        </tbody>
    </table>
</div>
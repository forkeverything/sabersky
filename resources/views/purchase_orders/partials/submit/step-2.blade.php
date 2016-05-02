<div id="po-submit-2" v-show="step === 2" class="submit-step animated" transition="slide-right">
    <div class="top-nav-buttons">
        <button type="button" class="btn btn-solid-blue btn-prev" @click="goStep(1)"><i
                class="fa fa-angle-double-left"></i>Prev Step</button>
        <button type="button" class="btn btn-solid-green btn-create" @click="createOrder">Create Order</button>
    </div>
    <div class="row flexing">
        <div class="col-sm-4">
            <div class="page-body vendor-details">
                <h5>Vendor</h5>
                <div class="name-group">
                    <label>Name</label>
                    <div class="name">
                        @{{ vendor.name }}
                        <vendor-connection :vendor="vendor"></vendor-connection>
                    </div>
                </div>
                <div class="address-selection">
                    <label v-if="! selectedAddress" class="required">Addresses</label>
                    <label v-else>Selected Address</label>
                    <ul class="list-unstyled list-address">
                        <li class="single-address clickable"
                            v-for="address in vendorAddresses"
                        @click="selectAddress(address)"
                        v-show="! selectedAddress || selectedAddress == address"
                        :class="{
                                'selected': selectedAddress == address
                            }"
                        >
                        <div class="change-overlay">
                            <i class="fa fa-repeat"></i>
                            <h3>Change</h3>
                        </div>
                        <span class="contact_person display-block"
                              v-if="address.contact_person">@{{ address.contact_person }}</span>
                        <span class="phone"><em>Phone:</em> @{{ address.phone }}</span>
                        <span class="address_1 display-block">@{{ address.address_1 }}</span>
                                    <span class="address_2 display-block"
                                          v-if="address.address_2">@{{ address.address_2 }}</span>
                        <span class="city">@{{ address.city }}</span>,
                        <div class="zip">@{{ address.zip }}</div>
                        <div class="state-country display-block">
                            <span class="state">@{{ address.state }}</span>,
                            <span class="country">@{{ address.country }}</span>
                        </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-8">
            <div class="page-body order-details">
                <h5>
                    Order
                </h5>
                <div class="currency-selection">
                    <label>Currency</label>
                    <currency-selecter :name.sync="currencyID" :default="user.company.currency"></currency-selecter>
                </div>
                <div class="set-address">
                    <div class="billing-address">
                        <label>Billing Address</label>
                        <div class="address-fields">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="shift-label-input">
                                        <input type="text" class="not-required" v-model="user.company.address.contact_person" :class="{ 'filled': user.company.address.contact_person }">
                                        <label placeholder="Contact Person"></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text" required v-model="user.company.address.phone">
                                    <label placeholder="Phone" class="required"></label>
                                 </div>
                                </div>
                            </div>
                            <div class="shift-label-input">
                                <input type="text" required v-model="user.company.address.address_1">
                                <label placeholder="Address" class="required"></label>
                             </div>
                            <div class="shift-label-input">
                                <input type="text"
                                       required
                                       v-model="user.company.address.address_2"
                                       class="not-required"
                                       :class="{
                                            'filled': user.company.address.address_2
                                        }"
                                >
                                <label placeholder="Address 2"></label>
                             </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="shift-label-input">
                                        <input type="text" required v-model="user.company.address.city">
                                        <label placeholder="City"></label>
                                    </div class="required">
                                </div>
                                <div class="col-sm-6">
                                    <div class="shift-label-input">
                                        <input type="text" required v-model="user.company.address.zip">
                                        <label placeholder="Zip"></label>
                                    </div class="required">
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group shift-select">
                                        <label class="required">Country</label>
                                        <country-selecter  :name.sync="billingAddressCountryID" :default="user.company.address.country_id" :event="selected-billing-country"></country-selecter>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group shift-select">
                                        <label class="required">State</label>
                                        <state-selecter :name.sync="billingAddressState" :default="user.company.address.state" :listen="selected-billing-country"></state-selecter>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="shipping-address">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="item-details page-body">
        <h5>Items</h5>
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
                    <th>Payable</th>
                    <th>Delivery</th>
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
                        </td>
                        <td>
                            <input class="form-control input-qty" type="text"
                                   v-model="lineItem.order_quantity | numberModel" placeholder="qty">
                        </td>
                        <td>
                            <input class="form-control input-price" type="text"
                                   v-model="lineItem.order_price | numberModel" placeholder="price">
                        </td>
                        <td>
                            <strong>@{{ calculateTotal(lineItem) }}</strong>
                        </td>
                        <td>
                            <input class="form-control input-date-payable" type="text" v-datepicker
                                   v-model="lineItem.order_payable" placeholder="payable">
                        </td>
                        <td>
                            <input class="form-control input-date-delivery" type="text" v-datepicker
                                   v-model="lineItem.order_delivery" placeholder="delivery">
                        </td>
                    </tr>
                </template>
                </tbody>
            </table>
        </div>
    </div>
    <div class="bottom-nav-buttons align-end">
        <button type="button" class="btn btn-solid-green btn-create" @click="createOrder">Create Order</button>
    </div>
</div>
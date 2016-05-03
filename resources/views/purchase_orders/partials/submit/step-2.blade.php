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
                <div class="currency-selection section">
                    <h5>
                        Currency
                    </h5>
                    <currency-selecter :name.sync="currencyID" :default="user.company.settings.currency"></currency-selecter>
                </div>
                <div class="billing-address section">
                    <h5>Billing Address</h5>
                    <div class="address-fields">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text" class="not-required"
                                           v-model="billingContactPerson"
                                           :class="{ 'filled': billingContactPerson }"
                                           :value="user.company.address.contact_person"
                                    >
                                    <label placeholder="Contact Person"></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text"
                                           required
                                           :value="user.company.address.phone"
                                           v-model="billingPhone"
                                    >
                                    <label placeholder="Phone" class="required"></label>
                                </div>
                            </div>
                        </div>
                        <div class="shift-label-input">
                            <input type="text"
                                   required
                                   :value="user.company.address.address_1"
                                   v-model="billingAddress1"
                            >
                            <label placeholder="Address" class="required"></label>
                        </div>
                        <div class="shift-label-input">
                            <input type="text"
                                   required
                                   :value="user.company.address.address_2"
                                   class="not-required"
                                   :class="{
                                            'filled': billingAddress2
                                        }"
                                   v-model="billingAddress2"
                            >
                            <label placeholder="Address 2"></label>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text"
                                           required
                                           :value="user.company.address.city"
                                           v-model="billingCity"
                                    >
                                    <label placeholder="City" class="required"></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text"
                                           required
                                           :value="user.company.address.zip"
                                           v-model="billingZip"
                                    >
                                    <label class="required" placeholder="Zip"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group shift-select">
                                    <label class="required">Country</label>
                                    <country-selecter :name.sync="billingCountryID"
                                                      :default="user.company.address.country_id"
                                                      :event="selected-billing-country"></country-selecter>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group shift-select">
                                    <label class="required">State</label>
                                    <state-selecter :name.sync="billingState"
                                                    :default="user.company.address.state"
                                                    :listen="selected-billing-country"></state-selecter>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="shipping-address section">
                    <h5>Shipping Address</h5>
                    <div class="check-same-billing checkbox styled">
                        <label>
                            <i class="fa fa-check-square-o checked" v-if="shippingAddressSameAsBilling"></i>
                            <i class="fa fa-square-o empty" v-else></i>
                            <input class="clickable hidden"
                                   type="checkbox"
                                   v-model="shippingAddressSameAsBilling">
                            Same as billing address
                        </label>
                    </div>
                    <div class="address-fields" v-if="! shippingAddressSameAsBilling">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text" class="not-required"
                                           v-model="shippingContactPerson"
                                           :class="{ 'filled': shippingContactPerson }">
                                    <label placeholder="Contact Person"></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text" required v-model="shippingPhone">
                                    <label placeholder="Phone" class="required"></label>
                                </div>
                            </div>
                        </div>
                        <div class="shift-label-input">
                            <input type="text" required v-model="shippingAddress1">
                            <label placeholder="Address" class="required"></label>
                        </div>
                        <div class="shift-label-input">
                            <input type="text"
                                   required
                                   v-model="shippingAddress2"
                                   class="not-required"
                                   :class="{
                                            'filled': shippingAddress2
                                        }"
                            >
                            <label placeholder="Address 2"></label>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text" required v-model="shippingCity">
                                    <label class="required" placeholder="City"></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text" required v-model="shippingZip">
                                    <label class="required" placeholder="Zip"></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group shift-select">
                                    <label class="required">Country</label>
                                    <country-selecter :name.sync="shippingCountryID"
                                                      :event="selected-shipping-country"></country-selecter>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group shift-select">
                                    <label class="required">State</label>
                                    <state-selecter :name.sync="shippingState"
                                                    :listen="selected-shipping-country"></state-selecter>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12">
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
                                        <number-input :model.sync="lineItem.order_quantity" :placeholder="'qty'" :class="['input-qty', 'form-control']"></number-input>
                                </td>
                                <td>
                                    <number-input :model.sync="lineItem.order_price" :placeholder="'price'" :class="['input-price', 'form-control']" :decimal="user.company.settings.currency_decimal_points"></number-input>
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
        </div>
    </div>
    <div class="bottom-nav-buttons align-end">
        <button type="button" class="btn btn-solid-green btn-create" @click="createOrder">Create Order</button>
    </div>
</div>
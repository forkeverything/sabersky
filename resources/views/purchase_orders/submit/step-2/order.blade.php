<h3>Order</h3>
<div class="currency-selection section">
    <h5 class="required">
        Currency
    </h5>
    <currency-selecter :name.sync="currency" :default="userCurrency"></currency-selecter>
</div>
<div class="billing-address section">
    <h5 class="required">Billing Address</h5>
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
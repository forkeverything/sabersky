Vue.component('po-shipping-address', {
    name: 'purchaseOrderShippingAddress',
    template: '<div class="check-same-billing checkbox styled">'+
    '<label>'+
    '<i class="fa fa-check-square-o checked" v-show="shippingAddressSameAsBilling"></i>'+
    '<i class="fa fa-square-o empty" v-else></i>'+
'<input class="clickable hidden" type="checkbox" v-model="shippingAddressSameAsBilling" :true-value="1" :false-value="0" >'+
    'Same as billing address'+
'</label>'+
'</div>'+
'<div class="address-fields" v-show="! shippingAddressSameAsBilling">'+
    '<div class="row">'+
    '<div class="col-sm-6">'+
    '<div class="shift-label-input">'+
    '<input type="text" class="not-required" v-model="shippingAddress.contact_person" :class="{' +  "'filled': shippingAddress.contact_person" +  '}">'+
    '<label placeholder="Contact Person"></label>'+
    '</div>'+
    '</div>'+
    '<div class="col-sm-6">'+
    '<div class="shift-label-input">'+
    '<input type="text" required v-model="shippingAddress.phone">'+
    '<label placeholder="Phone" class="required"></label>'+
    '</div>'+
    '</div>'+
    '</div>'+
    '<div class="shift-label-input">'+
    '<input type="text" required v-model="shippingAddress.address_1">'+
    '<label placeholder="Address" class="required"></label>'+
    '</div>'+
    '<div class="shift-label-input">'+
    '<input type="text" required v-model="shippingAddress.address_2" class="not-required" :class="{' + "'filled': shippingAddress.address_2" + '}">'+
'<label placeholder="Address 2"></label>'+
    '</div>'+
    '<div class="row">'+
    '<div class="col-sm-6">'+
    '<div class="shift-label-input">'+
    '<input type="text" required v-model="shippingAddress.city">'+
    '<label class="required" placeholder="City"></label>'+
    '</div>'+
    '</div>'+
    '<div class="col-sm-6">'+
    '<div class="shift-label-input">'+
    '<input type="text" required v-model="shippingAddress.zip">'+
    '<label class="required" placeholder="Zip"></label>'+
    '</div>'+
    '</div>'+
    '</div>'+
    '<div class="row">'+
    '<div class="col-sm-6">'+
    '<div class="form-group shift-select">'+
    '<label class="required">Country</label>'+
    '<country-selecter :name.sync="shippingAddress.country_id" :event="' + "'selected-shipping-country'" + '"></country-selecter>'+
    '</div>'+
    '</div>'+
    '<div class="col-sm-6">'+
    '<div class="form-group shift-select">'+
    '<label class="required">State</label>'+
    '<state-selecter :name.sync="shippingAddress.state" :listen="' + "'selected-shipping-country'" + '"></state-selecter>'+
    '</div>'+
    '</div>'+
    '</div>'+
    '</div>',
    data: function() {
        return {

        };
    },
    props: ['shipping-address-same-as-billing', 'shipping-address'],
    computed: {

    },
    methods: {

    },
    events: {

    },
    ready: function() {

    }
});
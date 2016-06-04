Vue.component('po-billing-address', {
    name: 'purchaseOrderSubmitBillingAddress',
    data: function () {
        return {};
    },
    template: '<div class="check-same-company checkbox styled" v-if="companyAddress">' +
    '<label>' +
    '<i class="fa fa-check-square-o checked" v-show="billingAddressSameAsCompany"></i>' +
    '<i class="fa fa-square-o empty" v-else></i>' +
    '<input class="clickable hidden" type="checkbox" v-model="billingAddressSameAsCompany" :true-value="1" :false-value="0" >' +
    'Same as Company Address' +
    '</label>' +
    '</div>' +
    '<div class="company-address" v-show="companyAddress && billingAddressSameAsCompany">' +
    '<address :address="companyAddress"></address>' +
    '</div>' +
        '<div class="address-fields" v-show="companyAddress && ! billingAddressSameAsCompany">' +
    '<div class="row">' +
    '<div class="col-sm-6">' +
    '<div class="shift-label-input">' +
    '<input type="text" class="not-required" v-model="billingAddress.contact_person" :class="{' + "'filled': billingAddress.contact_person" + '}" :value="companyAddress.contact_person" >' +
    '<label placeholder="Contact Person"></label>' +
    '</div>' +
    '</div>' +
    '<div class="col-sm-6">' +
    '<div class="shift-label-input">' +
    '<input type="text" required :value="companyAddress.phone" v-model="billingAddress.phone" >' +
    '<label placeholder="Phone" class="required"></label>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '<div class="shift-label-input">' +
    '<input type="text" required :value="companyAddress.address_1" v-model="billingAddress.address_1" >' +
    '<label placeholder="Address" class="required"></label>' +
    '</div>' +
    '<div class="shift-label-input">' +
    '<input type="text" required :value="companyAddress.address_2" class="not-required" :class="{' + "'filled': billingAddress.address_2" + '}" v-model="billingAddress.address_2" >' +
    '<label placeholder="Address 2"></label>' +
    '</div>' +
    '<div class="row">' +
    '<div class="col-sm-6">' +
    '<div class="shift-label-input">' +
    '<input type="text" required :value="companyAddress.city" v-model="billingAddress.city">' +
    '<label placeholder="City" class="required"></label>' +
    '</div>' +
    '</div>' +
    '<div class="col-sm-6">' +
    '<div class="shift-label-input">' +
    '<input type="text" required :value="companyAddress.zip" v-model="billingAddress.zip" >' +
    '<label class="required" placeholder="Zip"></label>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '<div class="row">' +
    '<div class="col-sm-6">' +
    '<div class="form-group shift-select">' +
    '<label class="required">Country</label>' +
    '<country-selecter :name.sync="billingAddress.country_id" :default="companyAddress.country_id" :event="' + "'selected-billing - country'" + '"></country-selecter>' +
    '</div>' +
    '</div>' +
    '<div class="col-sm-6">' +
    '<div class="form-group shift-select">' +
    '<label class="required">State</label>' +
    '<state-selecter :name.sync="billingAddress.state" :default="companyAddress.state" :listen="' + "'selected-billing - country'" + '"></state-selecter>' +
    '</div>' +
    '</div>' +
    '</div>' +
    '</div>',
    props: ['billing-address-same-as-company', 'billing-address', 'company'],
    computed: {
        companyAddress: function () {
            if (_.isEmpty(this.company.address)) return false;
            return this.company.address;
        }
    },
    methods: {},
    events: {},
    ready: function () {

    }
});
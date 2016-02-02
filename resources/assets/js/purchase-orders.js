new Vue({
    el: '#purchase-orders-submit',
    data: {
        vendorType: '',
        vendor_id: 'Choose an existing vendor',
        name: '',
        phone: '',
        address: '',
        bank_account_name: '',
        bank_account_number: '',
        bank_name: ''
    },
    computed: {
        readyStep3: function() {
            return (this.vendor_id !== 'Choose an existing vendor' || this.name.length > 0 && this.phone.length > 0 && this.address.length > 0 && this.bank_account_name.length > 0 && this.bank_account_number.length > 0 && this.bank_name.length > 0);
        }
    },
    methods: {
        selectVendor: function (type) {
            this.vendor_id = 'Choose an existing vendor';
            this.name = '';
            this.phone = '';
            this.address = '';
            this.bank_account_name = '';
            this.bank_account_number = '';
            this.bank_name = '';
            this.vendorType = type;
        }
    }
});
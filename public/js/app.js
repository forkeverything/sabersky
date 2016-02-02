
new Vue({
    el: '#add-line-item',
    data: {
        purchaseRequests: [],
        selectedPurchaseRequest: '',
        quantity: '',
        price: '',
        payable: '',
        delivery: ''
    },
    ready: function() {
        var self = this;
        $.ajax({
            method: 'GET',
            url: '/purchase_requests/available',
            success: function(data) {
                self.purchaseRequests = data;
            }
        });
    },
    methods: {
        selectPurchaseRequest: function($selected){
            this.selectedPurchaseRequest = $selected;
        },
        removeSelectedPurchaseRequest: function() {
            this.selectedPurchaseRequest = '';
            this.quantity = '';
            this.price = '';
            this.payable = '';
            this.delivery = '';
        },
        submitAddingPR: function() {

        }
    },
    computed: {
        subtotal: function() {
            return this.quantity * this.price;
        },
        canAddPurchaseRequest: function() {
            return (!! this.selectedPurchaseRequest && !! this.quantity & !! this.price && !! this.payable && !! this.delivery)
        }
    }
});



$(document).ready(function () {
    moment.locale('id'); // 'en'
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        startDate: 'today',
        language: 'id'
    });
});

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
$('.table-purchase-requests tbody tr').click(function () {
    window.document.location = $(this).data("href");
});

Vue.filter('date', function (value) {
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").format('DD/MM/YYYY');
    }
    return value;
});

Vue.filter('easyDate', function (value) {
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").format('DD MMMM YYYY');
    }
    return value;
});

Vue.filter('diffHuman', function (value) {
    if (value !== '0000-00-00 00:00:00') {
        return moment(value, "YYYY-MM-DD HH:mm:ss").fromNow();
    }
    return value;
});

Vue.filter('numberFormat', function (val) {
    //Seperates the components of the number
    var n = val.toString().split(".");
    //Comma-fies the first part
    n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
    //Combines the two sections
    return n.join(".");
});


Vue.filter('numberModel', {
    read: function (val) {
        //Seperates the components of the number
        var n = val.toString().split(".");
        //Comma-fies the first part
        n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        //Combines the two sections
        return n.join(".");
    },
    write: function (val, oldVal) {
        return val;
    }
});
//# sourceMappingURL=app.js.map

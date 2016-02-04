new Vue({
    name: 'addLineItem',
    el: '#add-line-item',
    data: {
        purchaseRequests: [],
        selectedPurchaseRequest: '',
        quantity: '',
        price: '',
        payable: '',
        delivery: '',
        canAjax: true,
        field: '',
        order: '',
        urgent: ''
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
        addLineItem: function() {
            var self = this;
            if(self.canAjax) {
                self.canAjax = false;
                $.ajax({
                    url: '/purchase_orders/add_line_item',
                    method: 'POST',
                    data: {
                        purchase_request_id: self.selectedPurchaseRequest.id,
                        quantity: self.quantity,
                        price: self.price,
                        payable: moment(self.payable, "DD/MM/YYYY").format("YYYY-MM-DD H:mm:ss"),
                        delivery: moment(self.delivery, "DD/MM/YYYY").format("YYYY-MM-DD H:mm:ss")
                    },
                    success: function (data) {
                        window.location='/purchase_orders/submit';
                    },
                    error: function (res, status, error) {
                        console.log(res);
                        self.canAjax = true;
                    }
                });
            }
        },
        changeSort: function($newField) {
            if(this.field == $newField) {
                this.order = (this.order == '') ? -1 : '';
            } else {
                this.field = $newField;
                this.order = ''
            }
        },
        toggleUrgent: function() {
            this.urgent = (this.urgent) ? '' : 1;
        }
    },
    computed: {
        subtotal: function() {
            return this.quantity * this.price;
        },
        validQuantity: function() {
            return (this.selectedPurchaseRequest.quantity >= this.quantity && this.quantity > 0);
        },
        canAddPurchaseRequest: function() {
            return (!! this.selectedPurchaseRequest && !! this.quantity & !! this.price && !! this.payable && !! this.delivery && this.validQuantity)
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
        bank_name: '',
        canAjax: true
    },
    computed: {
        readyStep3: function () {
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
        },
        removeLineItem: function (lineItemId) {
            console.log('hehehe');
            var self = this;
            if (self.canAjax) {
                self.canAjax = false;
                $.ajax({
                    url: '/purchase_orders/remove_line_item/' + lineItemId,
                    method: 'POST',
                    data: {},
                    success: function (data) {
                        console.log('success');
                        4
                        window.location = '/purchase_orders/submit';
                    },
                    error: function (res, status, error) {
                        console.log(error);
                        self.canAjax = true;
                    }
                });
            }
        }
    }
});

new Vue({
    name: 'allPurchaseOrders',
    el: '#purchase-orders-all',
    data: {
        purchaseOrders: [],
        headings: [
            ['created_at', 'Date Submitted'],
            ['project.name', 'Project'],
            ['', 'Item(s)'],
            ['total', 'OrderTotal'],
            ['', 'Status']
        ],
        statuses: [
            {
                key: 'pending',
                label: 'Pending'
            },
            {
                key: 'approved',
                label: 'Approved'
            },
            {
                key: 'rejected',
                label: 'Rejected'
            },
            {
                key: '',
                label: 'All'
            }
        ],
        field: '',
        order: '',
        urgent: '',
        filter: 'pending'
    },
    ready: function () {
        var self = this;
        $.ajax({
            url: '/api/purchase_orders',
            method: 'GET',
            success: function (data) {
                self.purchaseOrders = data;
            },
            error: function (data) {
                console.log(data);
            }
        });
    },
    methods: {
        changeSort: function ($newField) {
            if (this.field == $newField) {
                this.order = (this.order == '') ? -1 : '';
            } else {
                this.field = $newField;
                this.order = ''
            }
        },
        checkUrgent: function(purchaseOrder) {
            // takes a purchaseOrder and sees
            // if there are any PR's with urgent tags
            var urgent = false;
            _.forEach(purchaseOrder.line_items, function (item) {
                if(item.purchase_request.urgent) {
                    urgent = true;
                }
            });
            return urgent;
        },
        changeFilter: function (filter) {
            this.filter = filter;
        },
        toggleUrgent: function () {
            this.urgent = (this.urgent) ? '' : 1;
        },
        loadSinglePO: function(POID) {
            window.document.location = '/purchase_orders/single/' + POID;
        }
    }
});
$(document).ready(function () {
    new Vue({
        name: 'allPurchaseRequests',
        el: '#purchase-requests-all',
        data: {
            purchaseRequests: [],
            headings: [
                ['due', 'Due Date'],
                ['project.name', 'Project'],
                ['item.name', 'Item'],
                ['specification', 'Specification'],
                ['quantity', 'Quantity'],
                ['user.name', 'Made by'],
                ['created_at', 'Requested']
            ],
            field: '',
            order: '',
            urgent: '',
            filter: ''
        },
        ready: function () {
            var self = this;
            $.ajax({
                url: '/api/purchase_requests',
                method: 'GET',
                success: function (data) {
                    self.purchaseRequests = data;
                },
                error: function (res, status, req) {
                    console.log(status);
                }
            });
        },
        methods: {
            loadSinglePR: function (id) {
                window.document.location = '/purchase_requests/single/' + id;
            },
            changeSort: function ($newField) {
                if (this.field == $newField) {
                    this.order = (this.order == '') ? -1 : '';
                } else {
                    this.field = $newField;
                    this.order = ''
                }
            },
            toggleUrgent: function () {
                this.urgent = (this.urgent) ? '' : 1;
            },
            changeFilter: function (filter) {
                this.filter = filter;
            },
            checkShow: function (purchaseRequest) {
                switch (this.filter) {
                    case 'complete':
                        console.log(purchaseRequest.state);
                        if (purchaseRequest.state == 'Open' && purchaseRequest.quantity == '0') {
                            return true;
                        }
                        break;
                    case 'cancelled':
                        if (purchaseRequest.state == 'Cancelled') {
                            return true;
                        }
                        break;
                    default:
                        if (purchaseRequest.quantity > 0 && purchaseRequest.state !== 'Cancelled') {
                            return true;
                        }
                }
            }
        }
    });
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

Vue.filter('limitString', function (val, limit) {
    if (val) {
        var trimmedString = val.substring(0, limit);
        trimmedString = trimmedString.substr(0, Math.min(trimmedString.length, trimmedString.lastIndexOf(" "))) + '...';
        return trimmedString
    }

    return val;
});



//# sourceMappingURL=app.js.map

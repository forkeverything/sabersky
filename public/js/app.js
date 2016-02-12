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

$(document).ready(function () {

    /**
     * PO - SUBMIT
     */
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


    /**
     * PO - VIEW ALL
     */
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
                ['', 'Status'],
                ['', 'Paid'],
                ['', 'Delivered']
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
            checkUrgent: function (purchaseOrder) {
                // takes a purchaseOrder and sees
                // if there are any PR's with urgent tags
                var urgent = false;
                _.forEach(purchaseOrder.line_items, function (item) {
                    if (item.purchase_request.urgent) {
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
            loadSinglePO: function (POID) {
                window.document.location = '/purchase_orders/single/' + POID;
            },
            checkProperty: function (purchaseOrder, property) {
                var numLineItems = purchaseOrder.line_items.length;
                var numTrueForProperty = 0;
                _.forEach(purchaseOrder.line_items, function (item) {
                    item[property] ? numTrueForProperty++ : '';
                });
                if (numLineItems == numTrueForProperty) {
                    return true;
                }
            }
        }
    });
});
$(document).ready(function () {

    /**
     * PR - VIEW ALL
     */
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

    /**
     * PR - Make
     */

    new Vue({
        name: 'makePurchaseRequest',
        el: '#purchase-requests-add',
        data: {
            existingItem: true,
            items: [],
            existingItemName: '',
            selectedItem: ''
        },
        methods: {
            changeExistingItem: function (state) {
                this.clearSelectedExisting();
                this.existingItem = state;
            },
            selectItemName: function(name) {
                this.existingItemName = name;
            },
            selectItem: function(item) {
                this.selectedItem = item;
            },
            clearSelectedExisting: function() {
                this.selectedItem = '';
                this.existingItemName = '';
                $('#select-new-item-name')[0].selectize.clear();
                $('#field-new-item-specification').val('');
            }
        },
        computed: {
            uniqueItemNames: function () {
                return _.uniqBy(this.items, 'name');
            },
            itemsWithName: function () {
                return _.filter(this.items, {'name': this.existingItemName});
            }
        },
        ready: function () {
            var self = this;
            $.ajax({
                url: '/api/items',
                method: 'GET',
                success: function (data) {
                    self.items = data;
                }
            });

            $('#select-new-item-name').selectize({
                create: true,
                sortField: 'text',
                placeholder: 'Choose an existing name or enter a new one...'
            });
        }
    });




$(document).ready(function () {
    new Vue({
        name: 'Settings',
        el: '#system-settings',
        data: {
            settings: [],
            ajaxReady: true
        },
        ready: function() {
            var self = this;
            $.ajax({
                url: '/api/settings',
                method: 'GET',
                success: function(data) {
                    self.settings = data;
                },
                error: function(err) {
                    console.log(err);
                }
            });
        },
        methods: {
            saveSettings: function() {
                var self = this;
                if(self.ajaxReady) {
                    self.ajaxReady = false;
                    $.ajax({
                        url: '/settings',
                        method: 'POST',
                        data: self.settings,
                        success: function (data) {
                            console.log('Successfully saved settings');
                            self.ajaxReady = true;
                            flashNotify('success', 'Successfully updated settings')
                        },
                        error: function (err) {
                            console.log(err);
                            self.ajaxReady = true;
                        }
                    });
                }
            }
        },
        computed: {
            saveButtonText: function() {
                return this.ajaxReady ? 'Save Settings' : 'Saving...';
            }
        }
    });
});
$.noty.themes.customTheme = {
    name    : 'customTheme',
    helpers : {
        borderFix: function() {
            if(this.options.dismissQueue) {
                var selector = this.options.layout.container.selector + ' ' + this.options.layout.parent.selector;
                switch(this.options.layout.name) {
                    case 'top':
                    case 'topCenter':
                    case 'topLeft':
                    case 'topRight':
                    case 'bottomCenter':
                        $(selector).css({
                            borderRadius: '0',
                            width: '100%'
                        });
                        $(selector).first().css({
                            'border-top-left-radius': '0',
                            'border-top-right-radius': '0',
                            width: '100%'
                        });
                        $(selector).last().css({'border-bottom-left-radius': '0', 'border-bottom-right-radius': '0'});
                        break;
                    case 'bottomLeft':
                    case 'bottomRight':
                    case 'center':
                    case 'centerLeft':
                    case 'centerRight':
                    case 'inline':
                    case 'bottom':
                    default:
                        break;
                }
            }
        }
    },
    modal   : {
        css: {
            position       : 'fixed',
            width          : '100%',
            height         : '100%',
            backgroundColor: '#000',
            zIndex         : 10000,
            opacity        : 0.6,
            display        : 'none',
            left           : 0,
            top            : 0
        }
    },
    style   : function() {

        this.$bar.css({
            overflow  : 'hidden'
        });

        this.$message.css({
            fontSize  : '16px',
            lineHeight: '16px',
            textAlign : 'center',
            padding   : '8px 10px 9px',
            width     : 'auto',
            position  : 'relative',
            height: '60px',
            display: 'flex',
            justifyContent: 'center',
            alignItems: 'center'
        });

        this.$closeButton.css({
            position  : 'absolute',
            top       : 4, right: 4,
            width     : 10, height: 10,
            background: "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAKCAQAAAAnOwc2AAAAxUlEQVR4AR3MPUoDURSA0e++uSkkOxC3IAOWNtaCIDaChfgXBMEZbQRByxCwk+BasgQRZLSYoLgDQbARxry8nyumPcVRKDfd0Aa8AsgDv1zp6pYd5jWOwhvebRTbzNNEw5BSsIpsj/kurQBnmk7sIFcCF5yyZPDRG6trQhujXYosaFoc+2f1MJ89uc76IND6F9BvlXUdpb6xwD2+4q3me3bysiHvtLYrUJto7PD/ve7LNHxSg/woN2kSz4txasBdhyiz3ugPGetTjm3XRokAAAAASUVORK5CYII=)",
            display   : 'none',
            cursor    : 'pointer'
        });

        this.$buttons.css({
            padding        : 5,
            textAlign      : 'right',
            borderTop      : '1px solid #ccc',
            backgroundColor: '#fff'
        });

        this.$buttons.find('button').css({
            marginLeft: 5
        });

        this.$buttons.find('button:first').css({
            marginLeft: 0
        });

        this.$bar.on({
            mouseenter: function() {
                $(this).find('.noty_close').stop().fadeTo('normal', 1);
            },
            mouseleave: function() {
                $(this).find('.noty_close').stop().fadeTo('normal', 0);
            }
        });

        switch(this.options.layout.name) {
            case 'top':
            case 'topCenter':
            case 'center':
            case 'bottomCenter':
                this.$bar.css({
                    borderRadius: '0',
                    borderTop      : '2px solid #eee',
                    boxShadow   : "0 2px 4px rgba(0, 0, 0, 0.1)"
                });
                break;
            case 'inline':
            case 'topLeft':
            case 'topRight':
            case 'bottomLeft':
            case 'bottomRight':
            case 'centerLeft':
            case 'centerRight':
            case 'bottom':
            default:
                this.$bar.css({
                    border   : '2px solid #eee',
                    boxShadow: "0 2px 4px rgba(0, 0, 0, 0.1)"
                });
                break;
        }

        switch(this.options.type) {
            case 'alert':
            case 'notification':
                this.$bar.css({backgroundColor: '#A1A4AA', borderColor: '#989898', color: '#FFF'});
                break;
            case 'warning':
                this.$bar.css({backgroundColor: '#F1C40F', borderColor: '#F39C12', color: '#FFF'});
                this.$buttons.css({borderTop: '1px solid #FFC237'});
                break;
            case 'error':
                this.$bar.css({
                    backgroundColor: '#E74C3C', borderColor: '#C0392B', color: '#FFFFFF'
                });
                this.$buttons.css({borderTop: '1px solid darkred'});
                break;
            case 'information':
                this.$bar.css({backgroundColor: '#3498DB', borderColor: '#2980B9', color: '#FFFFFF'});
                this.$buttons.css({borderTop: '1px solid #0B90C4'});
                break;
            case 'success':
                this.$bar.css({backgroundColor: '#2ECC71', borderColor: '#27AE60', color: '#FFF'});
                this.$buttons.css({borderTop: '1px solid #50C24E'});
                break;
            default:
                this.$bar.css({backgroundColor: '#FFF', borderColor: '#CCC', color: '#444'});
                break;
        }
    },
    callback: {
        onShow : function() {
            $.noty.themes.defaultTheme.helpers.borderFix.apply(this);
        },
        onClose: function() {
            $.noty.themes.defaultTheme.helpers.borderFix.apply(this);
        }
    }
};

Vue.directive('selectize', {
    twoWay: true,
    bind: function () {
        $(this.el).selectize({
                sortField: 'text',
                placeholder: 'Type to select...'
            })
            .on("change", function (e) {
                this.set($(this.el).val());
            }.bind(this));
    },
    update: function (newValue, oldValue) {
//            $(this.el).trigger("change");
        $(this.el)[0].selectize.clear();
    }
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
        if(val) {
            //Seperates the components of the number
            var n = val.toString().split(".");
            //Comma-fies the first part
            n[0] = n[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            //Combines the two sections
            return n.join(".");
        }
    },
    write: function (val, oldVal, limit) {
        val = val.replace(/\s/g, ''); // remove spaces
        limit = limit || 0; // is there a limit?
        if(limit) {
            val = val.substring(0, limit); // if there is a limit, trim the value
        }
        //val = val.replace(/[^0-9.]/g, ""); // remove characters
        return parseInt(val.replace(/[^0-9.]/g, ""))
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

Vue.filter('percentage', {
    read: function(val) {
        return (val * 100);
    },
    write: function(val, oldVal){
        return val / 100;
    }
});





//# sourceMappingURL=app.js.map

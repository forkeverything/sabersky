Vue.component('items-all', apiRequestAllBaseComponent.extend({
    name: 'allItems',
    el: function () {
        return '#items-all';
    },
    data: function () {
        return {
            requestUrl: '/api/items',
            hasFilter: true,
            filterOptions: [
                {
                    value: 'brand',
                    label: 'Brand'
                },
                {
                    value: 'name',
                    label: 'Name'
                },
                {
                    value: 'project',
                    label: 'Project'
                }
            ]
        };
    },
    computed: {
        items: function() {
            return _.omit(this.response.data, 'query_parameters');
        },
        hasItems: function() {
            return !_.isEmpty(this.items);
        }
    },
    methods: {
        getItemProjects: function (item) {
            // Parses out project names from an Item's Purchase Requests
            var projects = [];
            _.forEach(item.purchase_requests, function (pr) {
                projects.push(pr.project);
            });
            return _.uniqBy(projects, 'id');
        }
    },
    events: {},
    ready: function () {
    }
}));
Vue.component('item-single', {
    name: 'itemSingle',
    el: function () {
        return '#item-single'
    },
    data: function () {
        return {
            ajaxReady: true,
            photos: [],
            fileErrors: []
        };
    },
    props: ['itemId'],
    computed: {},
    methods: {
        deletePhoto: function(photo) {
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/items/' + self.itemId + '/photo/' + photo.id,
                method: 'DELETE',
                success: function(data) {
                   // success
                    console.log(data);
                   self.photos = _.reject(self.photos, photo);
                   self.ajaxReady = true;
                },
                error: function(response) {
                    self.ajaxReady = true;
                }
            });
        },
        clearErrors: function() {
            this.fileErrors = [];
        }
    },
    events: {},
    ready: function () {

        var self = this;

        // Fetch item photos
        $.ajax({
            url: '/api/items/' + self.itemId,
            method: 'GET',
            success: function(data) {
               // success
                self.photos = data.photos
            },
            error: function(response) {
                console.log(response);
            }
        });

        new Dropzone("#item-photo-uploader", {
            autoProcessQueue: true,
            maxFilesize: 5,
            acceptedFiles: 'image/*',
            previewTemplate: '<div class="dz-image-row">' +
            '                       <div class="dz-image">' +
            '                           <img data-dz-thumbnail>' +
            '                       </div>' +
            '                       <div class="dz-file-details">' +
            '                           <div class="name-status">' +
            '                               <span data-dz-name class="file-name"></span>' +
            '                               <div class="dz-success-mark status-marker"><span>✔</span></div>' +
            '                               <div class="dz-error-mark status-marker"><span>✘</span></div>' +
            '                           </div>' +
            '                           <span class="file-size" data-dz-size></span>' +
            '                           <div class="dz-progress progress">' +
            '                               <span class="dz-upload progress-bar progress-bar-striped active" data-dz-uploadprogress></span>' +
            '                           </div>' +
            '                       </div>' +
            '                </div>',
            init: function () {
                this.on("complete", function (file) {
                    setTimeout(function () {
                        this.removeFile(file);
                    }.bind(this), 5000);
                });
                this.on("success", function (files, response) {
                    // Upload was successful, receive response
                    // of Photo Model back from the server.
                    self.photos.push(response);
                });
                this.on("error", function (file, err) {
                    if(typeof err === 'object') {
                        _.forEach(err.file, function (error) {
                            self.fileErrors.push(file.name + ': ' + error);
                        });
                    } else {
                        self.fileErrors.push(file.name + ': ' + err);
                    }
                });
            }
        });
    }
});
Vue.component('projects-add-team', {
    name: 'projectAddTeam',
    el: function() {
        return '#projects-team-add'
    },
    data: function() {
        return {
        };
    },
    props: [],
    computed: {

    },
    methods: {

    },
    events: {

    },
    ready: function() {
    }
});
Vue.component('projects-all', {
    name: 'projectsAll',
    el: function () {
        return '#projects-all'
    },
    data: function () {
        return {
            projects: [],
            popupVisible: true,
            projectToDelete: {},
            ajaxReady: true
        };
    },
    props: [],
    computed: {},
    methods: {
        deleteProject: function (project) {
            this.projectToDelete = project;

            var settings = {
                title: 'Confirm Delete ' + project.name,
                body: 'Deleting a Project is permanent and cannot be reversed. Deleting a project will mean Team Members (staff) who are a part of the project will no longer receive notifications or perform actions for the Project. If you started the Project again, you will have to re-add all Team Members individually.',
                buttonText: 'Permanently Remove ' + project.name,
                buttonClass: 'btn btn-danger',
                callbackEventName: 'remove-project'
            };
            this.$broadcast('new-modal', settings);
        }
    },
    events: {
        'remove-project': function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/projects/' + self.projectToDelete.id,
                method: 'DELETE',
                success: function (data) {
                    // success
                    self.projects = _.reject(self.projects, self.projectToDelete);
                    flashNotify('success', 'Permanently Deleted ' + self.projectToDelete.name);
                    self.projectToDelete = {};
                    self.ajaxReady = true;
                },
                error: function (response) {
                    self.ajaxReady = true;
                }
            });
        }
    },
    ready: function () {

        // Fetch projects
        var self = this;
        $.ajax({
            url: '/api/projects',
            method: 'GET',
            success: function(data) {
               // success
               self.projects = data;
            },
            error: function(response) {
            }
        });

        // Popup Stuff
            // Bind click
            $(document).on('click', '.button-project-dropdown', function (e) {
                e.stopPropagation();

                $('.button-project-dropdown.active').removeClass('active');
                $(this).addClass('active');

                $('.project-popup').hide();
                $(this).next('.project-popup').show();
            });

            // To hide popup
            $(document).click(function (event) {
                if (!$(event.target).closest('.project-popup').length && !$(event.target).is('.project-popup')) {
                    $('.button-project-dropdown.active').removeClass('active');
                    $('.project-popup').hide();
                }
            });

    }
});
Vue.component('project-single', {
    name: 'projectSingle',
    el: function() {
        return '#project-single-view'
    },
    data: function() {
        return {
            ajaxReady: true,
            teamMembers: [],
            tableHeaders: [
                {
                    label: 'Name',
                    path: ['name'],
                    sort: 'name'
                },
                {
                    label: 'Role',
                    path: ['role', 'position'],
                    sort: 'role.position'
                },
                {
                    label: 'Email',
                    path: ['email'],
                    sort: 'email'
                }
            ]
        };
    },
    props: [],
    computed: {
    },
    methods: {

    },
    events: {
    },
    ready: function() {
        var self = this;
        if(!self.ajaxReady) return;
        self.ajaxReady = false;
        $.ajax({
            url: '/api/projects/' + $('#hidden-project-id').val() +'/team',
            method: '',
            success: function(data) {
               // success
               self.teamMembers = data;
               self.ajaxReady = true;
            },
            error: function(response) {
                console.log(response);
                self.ajaxReady = true;
            }
        });
    }
});
Vue.component('purchase-orders-all', apiRequestAllBaseComponent.extend({
    name: 'allPurchaseOrders',
    el: function () {
        return '#purchase-orders-all';
    },
    data: function () {
        return {
            requestUrl: '/api/purchase_orders',
            statuses: ['pending', 'approved', 'rejected', 'all'],
            hasFilters: true,
            filterOptions: [
                {
                    value: 'number',
                    label: '# Number'
                },
                {
                    value: 'project_id',
                    label: 'Project'
                },
                {
                    value: 'total',
                    label: 'Total Cost'
                },
                {
                    value: 'item_sku',
                    label: 'Item - SKU'
                },
                {
                    value: 'item_brand',
                    label: 'Item - Brand'
                },
                {
                    value: 'item_name',
                    label: 'Item - Name'
                },
                {
                    value: 'submitted',
                    label: 'Submitted Date'
                },
                {
                    value: 'user_id',
                    label: 'Made by'
                }
            ]
        };
    },
    computed: {
        purchaseOrders: function () {
            return _.omit(this.response.data, 'query_parameters');
        }
    },
    methods: {
        changeStatus: function (status) {
            this.makeRequest(updateQueryString({
                status: status,
                page: 1
            }));
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
    },
    ready: function () {
    }
}));
Vue.component('purchase-orders-submit', {
    el: function () {
        return '#purchase-orders-submit';
    },
    data: function () {
        return {
            step: 1,
            ajaxReady: true,
            lineItems: [],
            vendor: {
                linked_company: {},
                addresses: [],
                bank_accounts: []
            },
            selectedVendorAddress: '',
            selectedVendorBankAccount: '',
            currency: '',
            billingAddressSameAsCompany: 1,
            billingAddress: {
                contact_person: '',
                phone: '',
                address_1: '',
                address_2: '',
                city: '',
                zip: '',
                country_id: '',
                state: ''
            },
            shippingAddressSameAsBilling: 1,
            shippingAddress: {
                contact_person: '',
                phone: '',
                address_1: '',
                address_2: '',
                city: '',
                zip: '',
                country_id: '',
                state: ''
            },
            additionalCosts: []
        };
    },
    props: ['user'],
    computed: {
        PORequiresAddress: function () {
            return this.user.company.settings.po_requires_address;
        },
        PORequiresBankAccount: function () {
            return this.user.company.settings.po_requires_bank_account;
        },
        currencyDecimalPoints: function () {
            return this.user.company.settings.currency_decimal_points;
        },
        userCurrency: function () {
            return this.user.company.settings.currency;
        },
        currencySymbol: function () {
            return this.currency ?  this.currency.currency_symbol : this.userCurrency.currency_symbol;
        },
        company: function () {
            return this.user.company;
        },
        companyAddress: function () {
            if (_.isEmpty(this.user.company.address)) return false;
            return this.user.company.address;
        },
        hasLineItems: function () {
            return this.lineItems.length > 0;
        },
        vendorAddresses: function () {
            // Only if we have a vendor
            if (!this.vendor.id) return [];
            // Grab the addresses associated with Vendor model
            var vendorAddresses = this.vendor.addresses || [];
            // If we have addresses and a linked company - add the Company's address
            if (vendorAddresses && this.vendor.linked_company_id) vendorAddresses.push(this.vendor.linked_company.address);
            return vendorAddresses;
        },
        validBillingAddress: function () {
            return !!this.billingAddress.phone && !!this.billingAddress.address_1 && !!this.billingAddress.city && !!this.billingAddress.zip && !!this.billingAddress.country_id && !!this.billingAddress.state;
        },
        validShippingAddress: function () {
            return !!this.shippingAddress.phone && !!this.shippingAddress.address_1 && !!this.shippingAddress.city && !!this.shippingAddress.zip && !!this.shippingAddress.country_id && !!this.shippingAddress.state;
        },
        canCreateOrder: function () {
            var validVendor = true,
                validOrder = true,
                validItems = true;

            // Vendor
            // one selected
            if (!this.vendor.id) validVendor = false;
            // if we need address and no address
            if (this.user.company.settings.po_requires_address && !this.selectedVendorAddress) validVendor = false;
            // if we need bank account and no bank account selected
            if (this.user.company.settings.po_requires_bank_account && !this.selectedVendorBankAccount) validVendor = false;

            // Order
            // currency set!
            if (!this.currency) validOrder = false;
            // Billing address required fields valid
            if (!this.billingAddressSameAsCompany && !this.validBillingAddress) validOrder = false;
            // If shipping NOT the same &&  Shipping address required fields not valid
            if (!this.shippingAddressSameAsBilling && !this.validShippingAddress) validOrder = false;

            // Items
            // Make sure we have some items
            if (!this.lineItems.length > 0) validItems = false;
            // for each line item...
            _.forEach(this.lineItems, function (item) {
                // quantity and price is filled
                if (!item.order_quantity || !item.order_price) validItems = false;
                // quantity to order <= quantity requested
                if (item.order_quantity > item.quantity) validItems = false;
            });

            // Create away if all valid
            return validVendor && validOrder && validItems
        }
    },
    methods: {
        removeLineItem: function (lineItem) {
            this.lineItems = _.reject(this.lineItems, lineItem);
        },
        clearAllLineItems: function () {
            this.lineItems = [];
        },
        goStep: function (step) {
            this.step = step;
        },
        selectAddress: function (address) {
            this.selectedVendorAddress = this.selectedVendorAddress ? null : address;
        },
        visibleAddress: function (address) {
            if (_.isEmpty(this.selectedVendorAddress)) return true;
            return this.selectedVendorAddress == address;
        },
        calculateTotal: function (lineItem) {
            if (!lineItem.order_quantity || !lineItem.order_price) return '-';
            var currencySymbol = this.currencySymbol || '$';
            return accounting.formatMoney(lineItem.order_quantity * lineItem.order_price, currencySymbol + ' ', this.user.company.settings.currency_decimal_points);
        },
        createOrder: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/purchase_orders/submit',
                method: 'POST',
                data: {
                    "vendor_id": self.vendor.id,
                    "vendor_address_id": self.selectedVendorAddress.id,
                    "vendor_bank_account_id": self.selectedVendorBankAccount.id,
                    "currency_id": self.currency.id,
                    "billing_address_same_as_company": self.billingAddressSameAsCompany,
                    "billing_contact_person": self.billingAddress.contact_person,
                    "billing_phone": self.billingAddress.phone,
                    "billing_address_1": self.billingAddress.address_1,
                    "billing_address_2": self.billingAddress.address_2,
                    "billing_city": self.billingAddress.city,
                    "billing_zip": self.billingAddress.zip,
                    "billing_country_id": self.billingAddress.country_id,
                    "billing_state": self.billingAddress.state,
                    "shipping_address_same_as_billing": self.shippingAddressSameAsBilling,
                    "shipping_contact_person": self.shippingAddress.contact_person,
                    "shipping_phone": self.shippingAddress.phone,
                    "shipping_address_1": self.shippingAddress.address_1,
                    "shipping_address_2": self.shippingAddress.address_2,
                    "shipping_city": self.shippingAddress.city,
                    "shipping_zip": self.shippingAddress.zip,
                    "shipping_country_id": self.shippingAddress.country_id,
                    "shipping_state": self.shippingAddress.state,
                    "line_items": self.lineItems,
                    "additional_costs": self.additionalCosts
                },
                success: function (data) {
                    // success
                    flashNotifyNextRequest('success', 'Submitted Purchase Order');
                    location = "/purchase_orders";
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);
                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    mixins: [modalSinglePR],
    ready: function () {
        vueEventBus.$on('po-submit-selected-vendor', function() {
            this.selectedVendorAddress = '';
            this.selectedVendorBankAccount = '';
        }.bind(this));
    }
});
Vue.component('purchase-requests-all', apiRequestAllBaseComponent.extend({
    name: 'allPurchaseRequests',
    el: function () {
        return '#purchase-requests-all';
    },
    data: function () {
        return {
            requestUrl: '/api/purchase_requests',
            finishLoading: false,
            hasFilters: true,
            filterOptions: [
                {
                    value: 'number',
                    label: '# Number'
                },
                {
                    value: 'project_id',
                    label: 'Project'
                },
                {
                    value: 'quantity',
                    label: 'Quantity'
                },
                {
                    value: 'item_sku',
                    label: 'Item - SKU'
                },
                {
                    value: 'item_brand',
                    label: 'Item - Brand'
                },
                {
                    value: 'item_name',
                    label: 'Item - Name'
                },
                {
                    value: 'due',
                    label: 'Due Date'
                },
                {
                    value: 'requested',
                    label: 'Requested Date'
                },
                {
                    value: 'user_id',
                    label: 'Requester'
                }
            ],
            states: ['open', 'fulfilled', 'cancelled', 'all']
        };
    },
    computed: {
        purchaseRequests: function() {
            return _.omit(this.response.data, 'query_parameters');
        }
    },
    methods: {
        changeState: function (state) {
            this.makeRequest(updateQueryString({
                state: state,
                page: 1
            }));
        },
        toggleUrgentOnly: function () {
            var urgent = this.params.urgent ? 0 : 1;
            this.makeRequest(updateQueryString({
                state: this.params.state, // use same state
                page: 1, // Reset to page 1
                urgent: urgent
            }));
        }
    },
    ready: function () {
    }
}));
Vue.component('purchase-requests-make', {
    name: 'makePurchaseRequest',
    el: function () {
        return '#purchase-requests-add';
    },
    data: function () {
        return {
            pageReady: false,
            ajaxReady: true,
            projectID: '',
            itemID: '',
            quantity: '',
            due: '',
            urgent: ''
        };
    },
    methods: {
        submitMakePRForm: function () {
            var self = this;


            // Send Req. via Ajax
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/purchase_requests/make',
                method: 'POST',
                data: {
                    'project_id': self.projectID,
                    'item_id': self.itemID,
                    'quantity': self.quantity,
                    'due': self.due,
                    'urgent': (self.urgent) ? 1 : 0
                },
                success: function (data) {
                    // success
                    console.log(data);
                    console.log('success!');
                    flashNotifyNextRequest('success', 'Made a new Purchase Request');
                    window.location.href = "/purchase_requests";
                },
                error: function (response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    computed: {

    },
    ready: function () {
        var self = this;

        $('#pr-item-selection').selectize({
            valueField: 'id',
            searchField: ['sku', 'brand', 'name'],
            create: false,
            placeholder: 'Search by SKU, Brand or Name',
            render: {
                option: function (item, escape) {

                    var sku = (item.sku) ? escape(item.sku) : '';
                    var brand = (item.brand) ? escape(item.brand) + ' - ' : '';
                    var image = (item.photos[0]) ? ('<img src="' + escape(item.photos[0].thumbnail_path) + '">') : '<i class="fa fa-image"></i>';

                    return '<div class="item-single-option">' +
                        '       <div class="item-thumbnail">' +
                                    image +
                        '       </div>' +
                        '       <div class="details">' +
                        '           <span class="brand">' + brand + '</span>' +
                        '           <span class="name">' + escape(item.name) + '</span>' +
                        '           <span class="sku">' + sku + '</span>' +
                        '       </div>' +
                        '</div>';
                },
                item: function (item, escape) {

                    var sku = (item.sku) ? escape(item.sku) : '';
                    var brand = (item.brand) ? escape(item.brand) + ' - ' : '';
                    var image = (item.photos[0]) ? ('<img src="' + escape(item.photos[0].thumbnail_path) + '">') : '<i class="fa fa-image"></i>';
                    var imageGallery =  '';
                    if(item.photos.length > 0) {
                        imageGallery += '<ul class="item-images list-unstyled">';
                        for(var i = 0 ; i < item.photos.length; i++) {
                            imageGallery += '<li class="item-select-image"><a class="fancybox" rel="group" href="' + escape(item.photos[i].path) + '"><img src="' + escape(item.photos[i].thumbnail_path) + '" alt="" /></a></li>'
                        }
                        imageGallery += '</ul>';
                    }

                    return '<div class="item-selected">' +
                        '       <div class="item-thumbnail">' +
                                    image +
                        '       </div>' +
                        '       <div class="details">' +
                        '           <span class="brand">' + brand + '</span>' +
                        '           <span class="name">' + escape(item.name) + '</span>' +
                        '           <span class="sku">' + sku + '</span>' +
                        '           <span class="specification">' + escape(item.specification) + '</span>' +
                        '       </div>' +
                                imageGallery +
                        '</div>'
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/items/search/' + encodeURIComponent(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        console.log(res);
                        callback(res);
                    }
                });
            },
            onChange: function (value) {
                self.itemID = value;
            }
    });

        self.$nextTick(function () {
            self.pageReady = true;
        });
    }
});


Vue.component('settings', {
    name: 'Settings',
    el: function () {
        return '#system-settings';
    },
    data: function () {
        return {
            settingsView: 'company',
            navLinks: [
                {
                    label: 'Company',
                    section: 'company'
                },
                {
                    label: 'Permissions',
                    section: 'permissions'
                },
                {
                    label: 'Rules',
                    section: 'rules'
                }
            ],
            roles: []   // shared with Permissions, Rules
        }
    },
    props: ['user'],
    methods: {
        changeView: function (view) {
            this.settingsView = view;
        }
    },
    components: {
        settingsCompany: 'settings-company',
        settingsPermissions: 'settings-permissions',
        settingsRules: 'settings-rules'
    }
});

Vue.component('team-all', {
    name: 'teamAll',
    el: function() {
        return '#team-all'
    },
    data: function() {
        return {
            employees: [],
            tableHeaders: [
                {
                    label: 'Name',
                    path: ['name'],
                    sort: 'name'
                },
                {
                    label: 'Role',
                    path: ['role', 'position'],
                    sort: 'role.position'
                },
                {
                    label: 'Email',
                    path: ['email'],
                    sort: 'email'
                },
                {
                    label: 'Status',
                    path: ['status'],
                    sort: 'status'
                }
            ]
        };
    },
    props: ['user'],
    computed: {
        
    },
    methods: {
        
    },
    events: {
        
    },
    ready: function() {
        var self = this;
        $.ajax({
            url: '/api/users/team',
            method: 'GET',
            success: function(data) {
               // success
               self.employees = _.map(data, function(staff) {
                   staff.name = '<a href="/team/user/' + staff.id + '">' + staff.name + '</a>';
                   staff.status = staff.invite_key ? '<span class="badge badge-warning">Pending</span>' : '<span class="badge badge-success">Confirmed</span>';
                   return staff;
               });
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
});
Vue.component('team-single-user', {
    name: 'teamSingleUser',
    el: function() {
        return '#team-single-user'
    },
    data: function() {
        return {
            roles: [],
            changeButton: false,
            userToDelete: {},
            ajaxReady: true
        };
    },
    props: [],
    computed: {

    },
    methods: {
        showChangeButton: function() {
            this.changeButton = true;
        },
        confirmDelete: function(user) {
            this.userToDelete = user;
            this.$broadcast('new-modal', {
                title: 'Confirm Permanently Delete ' + user.name,
                body: 'Deleting a User is immediate and permanent. All data regarding the User will automatically be removed. This action is irreversible. Any pending actions may become incompletable.',
                buttonText: 'Delete ' + user.name + ' and all corresponding data',
                buttonClass: 'btn-danger',
                callbackEventName: 'delete-user'
            });
        }
    },
    events: {
        'delete-user': function() {
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/team/user/' + self.userToDelete.id,
                method: 'DELETE',
                success: function(data) {
                   // success
                   self.ajaxReady = true;
                    window.location.href = '/team';
                },
                error: function(response) {
                    self.ajaxReady = true;
                }
            });
        }
    },
    ready: function() {
        var self = this;
    }
});
Vue.component('vendors-add-new', {
    name: 'addNewVendor',
    el: function() {
        return '#vendors-add-new'
    },
    data: function() {
        return {
            navLinks: [ 'search', 'custom'],
            currentTab: 'search'
        };
    },
    props: [],
    computed: {

    },
    methods: {
        changeTab: function (tab) {
            this.currentTab = tab;
        }
    },
    events: {

    },
    ready: function() {

    }
});
Vue.component('vendor-requests', {
    name: 'vendorRequests',
    el: function() {
        return '#vendor-requests'
    },
    data: function() {
        return {
            ajaxReady: true,
            pendingVendors: []
        };
    },
    props: [],
    computed: {
        
    },
    methods: {
        respondRequest: function(vendor, action) {
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + vendor.id + '/request/' + action,
                method: 'POST',
                success: function(data) {
                   // success
                    self.pendingVendors = _.reject(self.pendingVendors, vendor);
                    if(action === 'verify') flashNotify('success', 'Verified vendor request');
                   self.ajaxReady = true;
                },
                error: function(response) {
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {
        
    },
    ready: function() {
        // Fetch Companies that have pending Vendor requests to user's
        var self = this;
        $.ajax({
            url: '/api/vendors/pending_requests',
            method: 'GET',
            success: function(data) {
               self.pendingVendors = data;
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
});
Vue.component('vendor-single', {
    name: 'vendorSingle',
    el: function () {
        return '#vendor-single'
    },
    data: function () {
        return {
            ajaxReady: true,
            vendorID: '',
            vendor: {
                bank_accounts: [],
                addresses: []
            },
            description: '',
            editDescription: false,
            savedDescription: '',
            companyIDToLink: ''
        };
    },
    props: [],
    computed: {
        vendorLink: function () {
            if (this.vendor.linked_company_id) {
                if (this.vendor.verified) return 'verified';
                return 'pending';
            }
            return 'custom';
        }
    },
    methods: {
        startEditDescription: function () {
            this.editDescription = true;
            this.$nextTick(function () {
                $editor = $('.description-editor');
                $editor.focus();
                autosize.update($editor);
            });
        },
        saveDescription: function () {
            this.editDescription = false;
            this.savedDescription = 'saving';
            var self = this;
            if (!self.ajaxReady) {
                self.savedDescription = 'error';
                return;
            }
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendorID + '/description',
                method: 'POST',
                data: {
                    "description": self.description
                },
                success: function (data) {
                    // success
                    self.savedDescription = 'success';
                    self.vendor.description = self.description;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    self.savedDescription = 'error';
                    self.ajaxReady = true;
                }
            });
        },
        addressSetPrimary: function (address) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/address/' + address.id + '/set_primary',
                method: 'PUT',
                success: function (data) {
                    // success
                    self.vendor.addresses = _.map(self.vendor.addresses, function (vendorAddress) {
                        if (vendorAddress.id === address.id) {
                            vendorAddress.primary = 1;
                        } else {
                            vendorAddress.primary = 0;
                        }
                        return vendorAddress;
                    });
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        removeAddress: function (address) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/address/' + address.id,
                method: 'DELETE',
                success: function (data) {
                    // success
                    flashNotify('success', 'Removed address');
                    self.vendor.addresses = _.reject(self.vendor.addresses, address);
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        bankSetPrimary: function (account) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendorID + '/bank_accounts/' + account.id + '/set_primary',
                method: 'POST',
                success: function (data) {
                    self.vendor.bank_accounts = _.map(self.vendor.bank_accounts, function (bankAccount) {
                        if (bankAccount.id === account.id) {
                            bankAccount.primary = 1;
                        } else {
                            bankAccount.primary = 0;
                        }
                        return bankAccount;
                    });
                    self.ajaxReady = true;
                },
                error: function (response) {
                    flashNotify('error', 'Could not set Bank Account as primary');
                    self.ajaxReady = true;
                }
            });
        },
        deleteAccount: function (account) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendor.id + '/bank_accounts/' + account.id,
                method: 'DELETE',
                success: function (data) {
                    self.vendor.bank_accounts = _.reject(self.vendor.bank_accounts, account);
                    flashNotify('success', 'Removed bank account');
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);
                    flashNotify('error', 'Could not remove bank account');
                    self.ajaxReady = true;
                }
            });
        },
        unlinkCompany: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendor.id + '/unlink',
                method: 'PUT',
                data: {
                    "vendor_id": self.vendor.id
                },
                success: function (data) {
                    // success
                    flashNotify('success', 'Unlinked company to vendor');
                    self.vendor = data;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);

                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {
        'address-added': function (address) {
            this.vendor.addresses.push(address);
        }
    },
    ready: function () {
        var self = this;
        $.ajax({
            url: '/api/vendors/' + self.vendorID,
            method: 'GET',
            success: function (data) {
                self.vendor = data;
            },
            error: function (response) {
                console.log(response);
            }
        });
    }
});
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
    '<address>' +
    '<span class="company_name">{{ company.name }}</span>' +
    '<span class="display-block v-if="companyAddress.contact_person" >' +
    '{{ companyAddress.contact_person }}' +
    '</span>' +
    '<span class="address_1 display-block">{{ companyAddress.address_1 }}</span>' +
    '<span class="address_2 display-block" v-if="companyAddress.address_2">{{ companyAddress.address_2 }}</span>' +
    '<span class="city">{{ companyAddress.city }}</span>,' +
    '<span class="zip">{{ companyAddress.zip }}</span>' +
    '<div class="state-country display-block">' +
    '<span class="state">{{ companyAddress.state }}</span>,' +
    '<span class="country">{{ companyAddress.country }}</span><br>' +
    '<span class="phone"><abbr title="Phone">P:</abbr> {{ companyAddress.phone }}</span>' +
    '</div>' +
    '</address>' +
    '</div>' +
    '<div class="address-fields" v-show="companyAddress && ! billingAddressSameAsCompany">' +
    '<div class="row">' +
    '<div class="col-sm-6">' +
    '<div class="shift-label-input">' +
    '<input type="text" class="not-required" v-model="billingAddress.contact_person" :class="{' +  "'filled': billingAddress.contact_person" + '}" :value="companyAddress.contact_person" >' +
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
    '<state-selecter :name.sync="billingAddress.state" :default="companyAddress.state" :listen="'+ "'selected-billing - country'" + '"></state-selecter>'+
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
Vue.component('select-line-items', {
    name: 'selectLineItems',
    template: '<div class="project-selecter">'+
    '<h5>Project</h5>'+
    '<user-projects-selecter :name.sync="projectID"></user-projects-selecter>'+
    '</div>'+
    '<div class="purchase_requests"'+
':class="{'+
"'inactive': ! projectID"+
'}"'+
'>'+
'<div class="overlay"></div>'+
    '<h5>Purchase Requests</h5>'+
'<div class="pr-controls">'+
    '<form class="form-pr-search" @submit.prevent="searchPurchaseRequests">'+
    '<input class="form-control input-item-search"'+
'type="text"'+
'placeholder="Search by # Number, Item (Brand or Name) or Requester"'+
'@keyup="searchPurchaseRequests"'+
'v-model="searchTerm"'+
':class="{'+
"'active': searchTerm && searchTerm.length > 0"+
'}"'+
'>'+
'</form>'+
'</div>'+
'<div class="pr-bag" v-show="hasPurchaseRequests">'+
    '<div class="table-responsive">'+
    '<table class="table table-hover table-standard table-purchase-requests-po-submit">'+
    '<thead>'+
    '<tr>'+
    '<th class="heading-center heading-select-all">'+
    '<div class="checkbox styled">'+
    '<label>'+
    '<i class="fa fa-check-square-o checked" v-show="allPurchaseRequestsChecked"></i>'+
    '<i class="fa fa-square-o empty" v-else></i>'+
'<input class="clickable hidden"'+
'type="checkbox"'+
'@change="selectAllPR"'+
':checked="allPurchaseRequestsChecked"'+
    '>'+
    '</label>'+
    '</div>'+
    '</th>'+
    '<th class="clickable"'+
'@click="changeSort(' + "'number'" +')"'+
':class="{'+
"'current_asc': sort === 'number' && order === 'asc',"+
    "'current_desc': sort === 'number' && order === 'desc'"+
'}"'+
'>'+
'PR'+
'</th>'+
'<th class="clickable"'+
'@click="changeSort(' + "'item_name'" + ')"'+
':class="{'+
"'current_asc': sort === 'item_name' && order === 'asc',"+
    "'current_desc': sort === 'item_name' && order === 'desc'"+
'}"'+
'>'+
'Item'+
'</th>'+
'<th class="clickable"'+
'@click="changeSort(' + "'due'" + ')"'+
':class="{'+
"'current_asc': sort === 'due' && order === 'asc',"+
    "'current_desc': sort === 'due' && order === 'desc'"+
'}"'+
'>'+
'Due</th>'+
'</tr>'+
'</thead>'+
'<tbody>'+
'<template v-for="purchaseRequest in purchaseRequests">'+
    '<tr class="row-single-pr">'+
    '<td class="col-checkbox">'+
    '<div class="checkbox styled">'+
    '<label>'+
    '<i class="fa fa-check-square-o checked" v-if="alreadySelectedPR(purchaseRequest)"></i>'+
    '<i class="fa fa-square-o empty" v-else></i>'+
'<input class="clickable hidden"'+
'type="checkbox"'+
'@change="selectPR(purchaseRequest)"'+
':checked="alreadySelectedPR(purchaseRequest)"'+
    '>'+
    '</label>'+
    '</div>'+
    '</td>'+
    '<td class="no-wrap col-number">'+
    '<a class="dotted clickable" @click="showSinglePR(purchaseRequest)">#{{ purchaseRequest.number }}</a>'+
'</td>'+
'<td class="col-item">'+
    '<a class="dotted clickable" @click="showSinglePR(purchaseRequest)">'+
    '<span class="item-brand"'+
'v-if="purchaseRequest.item.brand.length > 0">{{ purchaseRequest.item.brand }} - </span>'+
'<span class="item-name">{{ purchaseRequest.item.name }}</span>'+
'</a>'+
'<div class="bottom">'+
    '<span '+
'v-if="purchaseRequest.urgent" class="badge-urgent with-tooltip" v-tooltip title="Urgent Request" data-placement="bottom"> <i '+
'class="fa fa-warning"></i></span>'+
    '<div class="quantity"><label>QTY:</label> {{ purchaseRequest.quantity }}</div>'+
'</div>'+
'</td>'+
'<td class="col-due no-wrap">'+
'<span class="pr-due">{{ purchaseRequest.due | date }}</span>'+
'</td>'+
'</tr>'+
'</template>'+
'</tbody>'+
'</table>'+
'</div>'+
'<div class="page-controls bottom">'+
    '<per-page-picker :response="response" :req-function="fetchPurchaseRequests"></per-page-picker>'+
    '<paginator :response="response" :event-name="' + "'po-submit-pr-page'" + '"></paginator>'+
    '</div>'+
    '</div>'+
    '<div class="empty-stage" v-else>'+
'<i class="fa  fa-hand-rock-o"></i>'+
    '<h4>No Purchase Requests</h4>'+
'<p>We couldn\'t find any requests to fulfill. Try selecting a different Project or <a '+
'class="dotted clickable" @click="clearSearch">clear</a> the search.</p>'+
'</div>'+
'</div>',
    data: function() {
        return {
            ajaxReady: true,
            ajaxObject: {},
            response: {},
            projectID: '',
            purchaseRequests: [],
            sort: 'number',
            order: 'asc',
            urgent: '',
            searchTerm: ''
        };
    },
    props: ['line-items'],
    computed: {
        hasPurchaseRequests: function () {
            return !_.isEmpty(this.purchaseRequests);
        },
        allPurchaseRequestsChecked: function () {
            var purchaseRequestIDs = _.map(this.purchaseRequests, function (request) {
                return request.id
            });
            var lineItemIDs = _.map(this.lineItems, function (item) {
                return item.id
            });
            return _.intersection(lineItemIDs, purchaseRequestIDs).length === purchaseRequestIDs.length;
        }
    },
    methods: {
        fetchPurchaseRequests: function (page) {
            var self = this;
            page = page || 1;

            var url = '/api/purchase_requests?' +
                'state=open' +
                '&quantity=1+' +
                '&project_id=' + self.projectID +
                '&sort=' + self.sort +
                '&order=' + self.order +
                '&per_page=8' +
                '&search=' + self.searchTerm;

            if (page) url += '&page=' + page;

            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            self.ajaxObject = $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    // Update data
                    self.response = response;

                    self.purchaseRequests = _.omit(response.data, 'query_parameters');

                    // Pull flags from response (better than parsing url)
                    self.sort = response.data.query_parameters.sort;
                    self.order = response.data.query_parameters.order;
                    self.urgent = response.data.query_parameters.urgent;

                    self.ajaxReady = true;

                    // self.$nextTick(function() {
                    //     self.finishLoading = true;
                    // })
                    // TODO ::: Add a loader for each request

                },
                error: function (res, status, req) {
                    console.log(status);
                    self.ajaxReady = true;
                }
            });
        },
        changeSort: function (sort) {
            if (this.sort === sort) {
                this.order = (this.order === 'asc') ? 'desc' : 'asc';
                this.fetchPurchaseRequests();
            } else {
                this.sort = sort;
                this.order = 'asc';
                this.fetchPurchaseRequests();
            }
        },
        searchPurchaseRequests: _.debounce(function () {
            var self = this;
            // If we're still waiting on a response cancel, abort, and fire a new request
            if (self.ajaxObject && self.ajaxObject.readyState != 4) self.ajaxObject.abort();
            self.fetchPurchaseRequests();
        }, 200),
        clearSearch: function () {
            this.searchTerm = '';
            this.searchPurchaseRequests();
        },
        selectPR: function (purchaseRequest) {
            this.alreadySelectedPR(purchaseRequest) ? this.lineItems = _.reject(this.lineItems, purchaseRequest) : this.lineItems.push(purchaseRequest);
        },
        alreadySelectedPR: function (purchaseRequest) {
            return _.find(this.lineItems, function (pr) {
                return pr.id === purchaseRequest.id;
            });
        },
        selectAllPR: function () {
            var self = this;
            if (self.allPurchaseRequestsChecked) {
                _.forEach(self.purchaseRequests, function (request) {
                    self.lineItems = _.reject(self.lineItems, request);
                });
            } else {
                _.forEach(self.purchaseRequests, function (request) {
                    if (!self.alreadySelectedPR(request)) self.lineItems.push(request);
                });
            } 
        }
    },
    mixins: [modalSinglePR],
    ready: function() {

        // select a new project -> load relevant PRs
        this.$watch('projectID', function (val) {
            if (!val) return;
            this.fetchPurchaseRequests();
        });

        // listen to our custom go to page event name
        vueEventBus.$on('po-submit-pr-page', function (page) {
            this.fetchPurchaseRequests(page);
        }.bind(this));
    }
});
Vue.component('po-submit-summary', {
    name: 'summary',
    template: '<div class="summary table-responsive">' +
    '<table class="table table-standard table-summary">' +
    '<tbody>' +
    '<tr>' +
    '<td class="col-title">Subtotal</td>' +
    '<td class="col-amount">{{ formatNumber(orderSubtotal, currencyDecimalPoints) }}</td>' +
    '<td class="col-currency">{{ currencySymbol }}</td>' +
    '</tr>' +
    '<template v-for="cost in additionalCosts">' +
    '<tr class="row-added-costs">' +
    '<td class="col-title">' +
    '{{ cost.name }}' +
    '<button type="button" class="close" aria-label="Close" @click="removeAdditionalCost(cost)"><span aria-hidden="true">&times;</span></button>' +
    '</td>' +
    '<td class="col-amount">{{ formatNumber(cost.amount, currencyDecimalPoints) }}</td>' +
    '<td class="col-currency">{{ cost.type }}</td>' +
    '</tr>' +
    '</template>' +
    '<tr class="row-inputs">' +
    '<td class="col-title">' +
    '<input type="text" class="form-control" placeholder="cost / discount" v-model="newCost.name">' +
    '</td>' +
    '<td class="col-amount">' +
    '<number-input :model.sync="newCost.amount" :placeholder="' + "'amount'" + '" :class="[' + "'form-control'" + ']"></number-input>' +
    '</td>' +
    '<td class="col-currency">' +
    '<select-picker :options="[{value:' + "'%', label: '%'" + '}, {value: currencySymbol, label: currencySymbol }]" :name.sync="newCost.type"></select-picker>' +
    '</td>' +
    '</tr>' +
    '<tr v-show="canAddNewCost" class="row-add-button">' +
    '<td></td>' +
    '<td></td>' +
    '<td>' +
    '<button type="button" class="btn btn-small btn-add-cost btn-outline-blue" @click="addAdditionalCost"><i class="fa fa-plus"></i> Cost / Discount</button></td>' +
    '</tr>' +
    '<tr class="row-total">' +
    '<td class="col-title">Total Cost</td>' +
    '<td class="col-amount">{{ formatNumber(orderTotal, currencyDecimalPoints) }}</td>' +
    '<td class="col-currency">{{ currencySymbol }}</td>' +
    '</tr>' +
    '</tbody>' +
    '</table>' +
    '</div>',
    data: function () {
        return {
            newCost: {
                name: '',
                type: '%',
                amonut: ''
            }
        };
    },
    props: ['line-items', 'additional-costs', 'currency-symbol', 'currency-decimal-points'],
    computed: {
        orderSubtotal: function () {
            var self = this;
            var subtotal = 0;
            if (!self.lineItems.length > 0) return;
            _.forEach(self.lineItems, function (item) {
                if (item.order_quantity && item.order_price && isNumeric(item.order_quantity) && isNumeric(item.order_price)) subtotal += (item.order_quantity * item.order_price);
            });
            return subtotal;
        },
        canAddNewCost: function () {
            return this.newCost.name && this.newCost.amount && this.newCost.type;
        },
        orderTotal: function () {
            var subtotal = this.orderSubtotal;
            var total = subtotal;
            _.forEach(this.additionalCosts, function (cost) {
                var amount = parseFloat(cost.amount);
                if (cost.type == '%') {

                    // Calculate the percentage off the sub-total NOT running total. This implies
                    // that other additional costs are NOT taxable. If user wants to include
                    // taxable costs, add as separate additional costs / discounts.

                    total += (subtotal * amount / 100);
                } else {
                    total += amount;
                }
            });
            return total;
        }
    },
    methods: {
        removeAdditionalCost: function (cost) {
            this.additionalCosts = _.reject(this.additionalCosts, cost);
        },
        addAdditionalCost: function () {
            this.additionalCosts.push(this.newCost);
            this.newCost = {
                name: '',
                type: '%',
                amonut: ''
            }
        }
    },
    events: {},
    mixins: [numberFormatter],
    ready: function () {
    }
});
Vue.component('settings-company', {
    name: 'settingsCompany',
    template: '',
    el: function () {
        return '#settings-company';
    },
    data: function () {
        return {
            ajaxReady: true,
            company: false
        }
    },
    props: [
        'settingsView',
        'user'
    ],
    computed: {
        canUpdateCompany: function () {
            if (this.user) return this.user.company.name;
            return false;
        },
        userCurrency: {
            get: function () {
                return this.user.company.settings.currency;
            },
            set: function (newValue) {
                // if we get a object
                if (newValue !== null && typeof newValue === 'object') {
                    // Update currency ID property (server persistence)
                    this.user.company.settings.currency_id = newValue.id;
                    // Update currency object (client)
                    this.user.company.settings.currency = newValue;
                }
            }
        },
        currencyDecimalPoints: function () {
            return this.user.company.settings.currency_decimal_points;
        }
    },
    methods: {
        updateCompany: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/company',
                method: 'PUT',
                data: {
                    name: self.user.company.name,
                    description: self.user.company.description,
                    currency_id: self.user.company.settings.currency_id,
                    currency_decimal_points: self.user.company.settings.currency_decimal_points
                },
                success: function (data) {
                    // success
                    flashNotify('success', 'Updated Company information');
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    ready: function () {
        var self = this;
    }
});
Vue.component('settings-permissions', {
    name: 'settingsPermissions',
    el: function () {
        return '#settings-permissions'
    },
    data: function () {
        return {
            ajaxReady: true,
            roleToRemove: false,
            roleSelect: '',
            selectedRole: false,
            editingRole: false,
            editRolePosition: false,
            roleToUpdate: {},
            updatedRoleVal: ''
        };
    },
    props: [
        'roles',
        'settingsView'
    ],
    computed: {},
    methods: {
        hasPermission: function (permission, role) {
            return _.some(role.permissions, permission);
        },
        removePermission: function (permission, role) {
            var self = this;
            $.ajax({
                url: '/api/roles/remove_permission',
                method: 'POST',
                data: {
                    role: role,
                    permission: permission
                },
                success: function () {
                    // remove role from roles
                    self.roles = _.reject(self.roles, role);
                    // modify role
                    role.permissions = _.reject(role.permissions, permission);
                    // Add role back to roles
                    self.roles.push(role);
                },
                error: function (response) {
                    // error
                    console.log('GET REQ Error!');
                    console.log(response);
                }
            });
        },
        givePermission: function (permission, role) {
            var self = this;
            $.ajax({
                url: '/api/roles/give_permission',
                method: 'POST',
                data: {
                    role: role,
                    permission: permission
                },
                success: function () {
                    self.roles = _.reject(self.roles, role);
                    role.permissions.push(permission);
                    self.roles.push(role)
                },
                error: function (response) {
                    // error
                    console.log('GET REQ Error!');
                    console.log(response);
                }
            });
        },
        addRole: function () {
            var newRole = {};
        },
        setRemoveRole: function (role) {
            this.$broadcast('new-modal', {
                title: 'Permanently Remove ' + strCapitalize(role.position),
                body: "Removing a role is irreversible. Any team members that have those roles will lose all their permissions and won't be able to complete any tasks until you assign them a new role.",
                buttonClass: 'btn-danger',
                buttonText: 'remove',
                callbackEventName: 'remove-role'
            });
            this.roleToRemove = role;
        },
        removeRole: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/roles/delete',
                method: 'POST',
                data: {
                    role: self.roleToRemove
                },
                success: function (data) {
                    // success
                    self.roles = _.reject(self.roles, self.roleToRemove);
                    // Remove from selectbox
                    self.roleSelect.removeOption(self.roleToRemove.position);
                    self.roleSelect.removeItem(self.roleToRemove.position, false);
                    self.selectedRole = false;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log('Error removing Role');
                    if(response.status === 406) flashNotify('error', 'Can not remove Role with assigned Staff');
                    self.ajaxReady = true;
                }
            });
        },
        editRole: function (role) {
            var self = this;
            self.editingRole = role;
            self.editRolePosition = role.position;
            self.$nextTick(function () {
                var $inputEdit = $('.input-editing-role');
                $inputEdit.focus();
                var blurFired = false; // blur fired flag
                $inputEdit.keypress(function (e) {
                    if (e.which == 13) {
                        this.blur();
                    }
                });
                $inputEdit.blur(function () {
                    var newRoleVal = $inputEdit.val().toLowerCase();
                    if (blurFired) return;
                    blurFired = true;
                    if (newRoleVal !== role.position && newRoleVal.length !== 0) {
                        self.confirmEdit(role, newRoleVal);
                    }
                    self.editingRole = false;
                    self.editRolePosition = false;

                });
            });

        },
        notEditing: function (role) {
            return role !== this.editingRole;
        },
        confirmEdit: function (oldRole, newRoleVal) {
            this.roleToUpdate = oldRole;
            this.updatedRoleVal = newRoleVal;
            this.$broadcast('new-modal', {
                title: 'Confirm Edit ' + strCapitalize(this.editingRole.position) + ' to ' + strCapitalize(newRoleVal),
                body: 'Role changes are immediate and will automatically effect all team members that have the role.',
                buttonClass: 'btn-primary',
                buttonText: 'update',
                callbackEventName: 'update-role'
            });
        },
        updateRole: function () {
            var self = this;
            $.ajax({
                url: '/api/roles/' + self.roleToUpdate.id,
                method: 'PUT',
                data: {
                    role: self.roleToUpdate,
                    newPosition: self.updatedRoleVal
                },
                success: function (role) {
                    self.roles = _.reject(self.roles, self.roleToUpdate);
                    self.roles.push(role);

                    self.roleSelect.updateOption(self.roleToUpdate.position, {
                        value: role.position,
                        text: strCapitalize(role.position)
                    });

                    // select new option
                    if (self.selectedRole.position === self.roleToUpdate.position) self.selectedRole = role;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                }
            });
        }
    },
    events: {
        'remove-role': function () {
            this.removeRole();
        },
        'update-role': function () {
            this.updateRole();
        }
    },
    ready: function () {
        var self = this;

        // GET company roles
        $.ajax({
            url: '/api/roles',
            method: 'GET',
            success: function (data) {
                self.roles = data;
            },
            error: function (err) {
                console.log(err);
            }
        });

        var $addRoleLink = $('#link-add-role');

        $addRoleLink.editable({
            type: 'text',
            mode: 'inline',
            showbuttons: false,
            placeholder: 'Position Title'
        });

        $addRoleLink.on('shown', function () {
            setTimeout(function () {
                $addRoleLink.editable('setValue', '');
            }, 0);
        });

        $addRoleLink.on('hidden', function (e, reason) {
            $addRoleLink.editable('setValue', 'Add New Role');
        });

        function saveRole(position, successFn, errorFn) {
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/roles',
                method: 'POST',
                data: {
                    position: position
                },
                success: function (data) {
                    self.roles.push(data);
                    successFn ? successFn() : null;
                    self.ajaxReady = true;
                },
                error: function (res) {
                    console.log('Error: saving new role');
                    console.log(res);
                    errorFn ? errorFn() : null;
                    self.ajaxReady = true;
                }
            });
        }

        self.roleSelect = uniqueSelectize('#select-settings-role', 'Select or type to add a new role');

        $addRoleLink.on('save', function (e, params) {
            self.roleSelect.addOption({
                value: params.newValue,
                text: params.newValue
            });
        });

        self.roleSelect.on("option_add", function (value, $item) {
            self.roleSelect.updateOption(value, {
                value: value,
                text: strCapitalize(value)
            });

            saveRole(value, function () {
                // success
                self.selectedRole = _.find(self.roles, {position: value});
            }, function () {
                // error:
                self.roleSelect.removeOption(value);
            });
        });

        self.roleSelect.on("item_add", function (value, $item) {
            self.selectedRole = _.find(self.roles, {position: value});
        });
    }
});

Vue.component('settings-rules', {
    name: 'settingsRules',
    el: function () {
        return '#settings-rules'
    },
    data: function () {
        return {
            ajaxReady: true,
            rules: [],
            ruleProperties: [],
            selectedProperty: false,
            selectedTrigger: false,
            selectedRuleRoles: [],
            ruleLimit: '',
            ruleToRemove: false
        };
    },
    props: [
        'user',
        'roles',
        'settingsView'
    ],
    computed: {
        currencySymbol: function() {
          return this.user.company.settings.currency.currency_symbol;  
        },
        ruleHasLimit: function () {
            return (this.selectedTrigger && this.selectedTrigger.has_limit);
        },
        canSubmitRule: function () {
            if (this.ruleHasLimit) {
                if (this.selectedRuleRoles) {
                    return this.selectedProperty && this.selectedTrigger && this.selectedRuleRoles.length > 0 && this.ruleLimit > 0;
                }
                return false;
            }
            return this.selectedProperty && this.selectedTrigger && this.selectedRuleRoles.length > 0;
        },
        hasRules: function () {
            return !_.isEmpty(this.rules);
        }
    },
    methods: {
        setTriggers: function () {
            this.selectedTrigger = '';
        },
        addRule: function () {
            var self = this;
            var postData = {
                rule_property_id: self.selectedProperty.id,
                rule_trigger_id: self.selectedTrigger.id,
                limit: self.ruleLimit,
                roles: self.selectedRuleRoles
            };
            $.ajax({
                url: '/api/rules',
                method: 'POST',
                data: postData,
                success: function (data) {
                    // success
                    self.fetchRules();
                    flashNotify('success', 'Successfully added a new Rule');
                    self.resetRuleValues();
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                    self.resetRuleValues();
                    if (response.status === 409) {
                        flashNotify('error', 'Rule already exists');
                    } else {
                        flashNotify('error', 'Could not add Rule');
                    }

                }
            });
        },
        resetRuleValues: function () {
            this.ruleLimit = '';
            this.selectedRuleRoles = [];
        },
        setRemoveRule: function (rule) {
            this.ruleToRemove = rule;
            this.$broadcast('new-modal', {
                title: 'Confirm Remove Rule',
                body: "Removing a rule is irreversible. Any Pending (Unapproved) Purchase Orders that is waiting for the Rule to be approved may automatically be approved for processing.",
                buttonClass: 'btn-danger',
                buttonText: 'remove',
                callbackEventName: 'remove-rule'
            });
        },
        removeRule: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/rules/' + self.ruleToRemove.id + '/remove',
                method: 'DELETE',
                success: function (data) {
                    // success
                    self.fetchRules();
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        fetchRules: function () {
            var self = this;
            $.ajax({
                url: '/api/rules',
                method: 'GET',
                success: function (data) {
                    // success
                    self.rules = data;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                }
            });
        }
    },
    events: {
        'remove-rule': function () {
            this.removeRule();
        }
    },
    ready: function () {
        var self = this;

        $.ajax({
            url: '/api/rules/properties_triggers',
            method: 'GET',
            success: function (data) {
                // success
                self.ruleProperties = data;
            },
            error: function (response) {
                console.log('Request Error!');
                console.log(response);
            }
        });

        self.fetchRules();
    }
});
Vue.component('add-bank-account-modal', {
    name: 'add-bank-account-modal',
    template: '<button type="button"' +
    '               class="btn btn-add-modal btn-outline-blue"' +
    '               @click="showModal"' +
    '          >' +
    '           New Account' +
    '</button>' +
    '          <div class="modal-bank-account-add modal-form" v-show="visible" @click="hideModal">' +
    '               <form class="form-add-bank-account main-form" @click.stop="" @submit.prevent="addBankAccount">' +
    '                   <form-errors></form-errors>' +
    '                   <h4>Add New Bank Account</h4>'+
    '                   <div class="account_info">'+
    '                       <label>Account Information</label>'+
    '                       <div class="row">'+
    '                           <div class="col-xs-6">'+
    '                               <div class="shift-label-input no-validate">'+
    '                                   <input type="text" v-model="accountName" required>'+
    '                                   <label placeholder="Account Name" class="required"></label>'+
    '                               </div>'+
    '                           </div>'+
    '                           <div class="col-xs-6">'+
    '                               <div class="shift-label-input no-validate">'+
    '                                   <input type="text" v-model="accountNumber" required>'+
    '                                   <label placeholder="# Number" class="required"></label>'+
    '                               </div>'+
    '                           </div>'+
    '                       </div>'+
    '                   </div>'+
    '                   <div class="bank_info">'+
    '                       <label>Bank Details</label>'+
    '                       <div class="visible-xs">'+
    '                           <div class="shift-label-input no-validate">'+
    '                               <input type="text" v-model="bankName" required>'+
    '                               <label placeholder="Bank Name" class="required"></label>'+
    '                           </div>'+
    '                       </div>'+
    '                       <div class="row hidden-xs">'+
    '                           <div class="col-sm-4">'+
    '                               <div class="shift-label-input no-validate">'+
    '                                   <input type="text" v-model="bankName" required>'+
    '                                   <label placeholder="Bank Name" class="required"></label>'+
    '                               </div>'+
    '                           </div>'+
    '                           <div class="col-sm-4">'+
    '                               <div class="shift-label-input no-validate">'+
    '                                   <input type="text" ' +
    '                                       class="not-required"'+
    '                                       v-model="swift" ' +
    '                                       :class="{'+
    "                                           'filled': swift.length > 0"+
    '                                       }">'+
    '                                   <label placeholder="SWIFT / IBAN"></label>'+
    '                               </div>'+
    '                           </div>'+
    '                           <div class="col-sm-4">'+
    '                               <div class="shift-label-input no-validate">'+
    '                                   <input type="text" ' +
    '                                       class="not-required" ' +
    '                                       v-model="bankPhone" ' +
    '                                       :class="{'+
    "                                           'filled': bankPhone.length > 0"+
    '                                       }">'+
    '                                   <label placeholder="Phone Number"></label>'+
    '                               </div>'+
    '                           </div>'+
    '                  </div>'+
    '                  <div class="row visible-xs">'+
    '                       <div class="col-xs-6">'+
    '                           <div class="shift-label-input no-validate">'+
    '                               <input type="text" ' +
    '                                      class="not-required"'+
    '                                      v-model="swift" ' +
    '                                      :class="{' +
    "                                           'filled': swift.length > 0"+
    '                                       }">'+
    '                               <label placeholder="SWIFT / IBAN"></label>'+
    '                           </div>'+
    '                      </div>'+
    '                      <div class="col-xs-6">'+
    '                           <div class="shift-label-input no-validate">'+
    '                               <input type="text" ' +
    '                                      class="not-required" ' +
    '                                      v-model="bankPhone" ' +
    '                                      :class="{' +
    "                                           'filled': bankPhone.length > 0 "+
    '                                       }">'+
    '                                       <label placeholder="Phone Number"></label>' +
    '                           </div>'+
    '                       </div>'+
    '                </div>'+
    '                <div class="shift-label-input no-validate">'+
    '                       <input type="text" ' +
    '                              class="not-required" ' +
    '                              v-model="bankAddress" ' +
    '                               :class="{'+
    "                                   'filled': bankAddress.length > 0" +
    '                               }">'+
    '                       <label placeholder="Address"></label>'+
    '               </div>'+
    '           </div>'+
    '           <div class="align-end">'+
    '               <button type="submit" class="btn btn-solid-blue"><i class="fa fa-plus"></i> Bank Account</button>'+
    '           </div>'+
    '       </form>' +
    ' </div>',
    data: function() {
        return {
            ajaxReady: true,
            ajaxObject: {},
            visible: false,
            accountName: '',
            accountNumber: '',
            bankName: '',
            swift: '',
            bankPhone: '',
            bankAddress: ''
        };
    },
    props: ['vendor'],
    computed: {
        
    },
    methods: {
        showModal: function () {
            this.visible = true;
        },
        hideModal: function () {
            this.visible = false;
        },
        addBankAccount: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendor.id + '/bank_accounts',
                method: 'POST',
                data: {
                    "account_name": self.accountName,
                    "account_number": self.accountNumber,
                    "bank_name": self.bankName,
                    "swift": self.swift,
                    "bank_phone": self.bankPhone,
                    "bank_address": self.bankAddress
                },
                success: function (data) {
                    // Push to front
                    self.vendor.bank_accounts.push(data);
                    // Reset Fields
                    self.accountName = '';
                    self.accountNumber = '';
                    self.bankName = '';
                    self.swift = '';
                    self.bankPhone = '';
                    self.bankAddres = '';
                    // Flash
                    flashNotify('success', 'Added bank account to vendor');
                    self.visible = false;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    flashNotify('error', 'Could not add bank account to vendor')
                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        },
    },
    events: {
        
    },
    ready: function() {
        
    }
});
Vue.component('vendor-add-search', {
    name: 'vendorAddSearchCompany',
    el: function () {
        return '#vendor-add-search'
    },
    data: function () {
        return {
            ajaxReady: true,
            linkedCompanyID: ''
        };
    },
    props: ['currentTab'],
    computed: {},
    methods: {
        addCompanyAsNewVendor: function() {
            var self = this;
            vueClearValidationErrors(self);
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/link',
                method: 'POST',
                data: {
                    "linked_company_id": self.linkedCompanyID
                },
                success: function(data) {
                   // success
                    flashNotifyNextRequest('success', 'Sent request to link Company as a Vendor');
                    location.href = "/vendors";
                   self.ajaxReady = true;
                },
                error: function(response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {},
    ready: function () {
    }
});
Vue.component('vendor-add-custom', {
    name: 'vendorAddCustom',
    el: function() {
        return '#vendor-add-custom'
    },
    data: function() {
        return {
        
        };
    },
    props: ['currentTab'],
    computed: {
        
    },
    methods: {
        
    },
    events: {
        
    },
    ready: function() {

    }
});
Vue.component('vendor-single-link-company', {
    name: 'vendorLinkCompany',
    template:  '<form class="form-link-company" v-else @submit.prevent="linkCompany" v-if="! vendor.linked_company_id">'+
    '               <form-errors></form-errors>'+
    '               <div class="form-group">'+
    '                   <p class="text-muted">Search for this Vendor on SaberSky</p>'+
    '                   <company-search-selecter :name.sync="companyIDToLink"></company-search-selecter>'+
    '               </div>'+
    '               <button type="submit" class="btn btn-solid-blue btn-full btn-small" v-show="companyIDToLink" :disabled="! companyIDToLink">Send Link Request</button>'+
    '            </form>',
    data: function() {
        return {
            ajaxReady: true,
            companyIDToLink: ''
        };
    },
    props: ['vendor'],
    computed: {

    },
    methods: {
        linkCompany: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/link',
                method: 'POST',
                data: {
                    "vendor_id": self.vendor.id,
                    "linked_company_id": self.companyIDToLink
                },
                success: function (data) {
                    // success
                    flashNotify('success', 'Linked company to vendor');
                    self.companyIDToLink = '';
                    self.vendor = data;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);
                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {

    },
    ready: function() {

    }
});
//# sourceMappingURL=page.js.map

Vue.component('items-all', {
    name: 'allItems',
    el: function () {
        return '#items-all';
    },
    data: function () {
        return {
            ajaxReady: true,
            brands: [],
            projects: [],
            items: [],
            itemsFilterDropdown: false,
            filterOptions: [
                {
                    value: 'brand',
                    label: 'Brand'
                },
                {
                    value: 'project',
                    label: 'Project'
                }
            ],
            filter: '',
            filterBrand: '',
            filterProject: '',
            response: {},
            activeBrandFilter: '',
            activeProjectFilter: '',
            searchTerm: '',
            sort: '',
            order: '',
            lastPage: '',
            currentPage: '',
            itemsPerPage: '',
            ajaxObject: {}
        };
    },
    computed: {},
    methods: {
        setLoadQuery: function () {
            var currentQuery = window.location.href.split('?')[1];

            return currentQuery
        },
        getCompanyItems: function (query) {
            var self = this;
            var url = query ? '/api/items?' + query : '/api/items';
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            self.ajaxObject = $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    self.response = response;
                    self.items = response.data;

                    self.activeBrandFilter = response.data.brand;
                    self.activeProjectFilter = _.find(self.projects, {id: parseInt(response.data.projectID)});
                    self.searchTerm = response.data.search;
                    self.sort = response.data.sort;
                    self.order = response.data.order;

                    // push state (if query is different from url)
                    pushStateIfDiffQuery(query);

                    // Scrolltop
                    document.getElementById('body-content').scrollTop = 0;

                    self.ajaxReady = true;
                },
                error: function (err) {
                    self.ajaxReady = true;
                }
            });
        },
        getBrands: function () {
            var self = this;
            $.ajax({
                url: '/api/items/brands',
                method: 'GET',
                success: function (data) {
                    // success
                    self.brands = _.map(data, function (brand) {
                        if (brand.brand) {
                            brand.value = brand.brand;
                            brand.label = strCapitalize(brand.brand);
                            return brand;
                        }
                    });
                },
                error: function (response) {
                    console.log(response);
                }
            });
        },
        getProjects: function () {
            var self = this;
            $.ajax({
                url: '/api/projects',
                method: 'GET',
                success: function (data) {
                    // success
                    self.projects = _.map(data, function (project) {
                        if (project.name) {
                            project.value = project.id;
                            project.label = strCapitalize(project.name);
                            return project;
                        }
                    });
                },
                error: function (response) {
                    console.log(response);
                }
            });
        },
        addItemsFilter: function () {
            var filterQuery;
            if (this.filter === 'brand' && this.filterBrand) {
                this.getCompanyItems(updateQueryString({
                    brand: this.filterBrand,
                    page: 1
                }));
                this.resetFilter();
            } else if (this.filter === 'project' && this.filterProject) {
                this.getCompanyItems(updateQueryString({
                    project: this.filterProject,
                    page: 1
                }));
                this.resetFilter();
            }
        },
        resetFilter: function () {
            this.filter = '';
            this.filterBrand = '';
            this.filterProject = '';
            this.itemsFilterDropdown = false;
        },
        removeFilter: function (type) {
            if (type === 'brand') {
                this.getCompanyItems(updateQueryString({
                    brand: null,
                    page: 1
                }));
            } else if (type === 'project') {
                this.getCompanyItems(updateQueryString({
                    project: null,
                    page: 1
                }));
            }
        },
        searchItemQuery: function () {
            var self = this;

            if (self.ajaxObject && self.ajaxObject.readyState != 4) self.ajaxObject.abort();

            if (self.searchTerm) {
                self.getCompanyItems(updateQueryString({
                    search:  self.searchTerm,
                    page: 1
                }));
            } else {
                self.getCompanyItems(updateQueryString({
                    search: null,
                    page: 1
                }));
            }

        },
        changeSort: function (sort) {
            if (this.sort === sort) {
                var newOrder = (this.order === 'asc') ? 'desc' : 'asc';
                this.getCompanyItems(updateQueryString('order', newOrder));
            } else {
                this.getCompanyItems(updateQueryString({
                    sort: sort,
                    order: 'asc',
                    page: 1
                }));
            }
        },
        getItemProjectNames: function(item){
            // Parses out project names from an Item's Purchase Requests
            var projects = [];
            _.forEach(item.purchase_requests, function (pr) {
                if(projects.indexOf(pr.project.name) === -1 )projects.push(pr.project.name);
            });
            return projects;
        }
    },
    events: {},
    ready: function () {

        this.getCompanyItems(this.setLoadQuery());
        this.getBrands();
        this.getProjects();

        onPopQuery(this.getCompanyItems);

    }
});
Vue.component('add-line-item', {
    name: 'addLineItem',
    el: function () {
        return '#add-line-item';
    },
    data: function () {
        return {
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
        };
    },
    ready: function () {
        var self = this;
        $.ajax({
            method: 'GET',
            url: '/api/purchase_requests/available',
            success: function (data) {
                self.purchaseRequests = data;
            }
        });
    },
    methods: {
        selectPurchaseRequest: function ($selected) {
            this.selectedPurchaseRequest = $selected;
        },
        removeSelectedPurchaseRequest: function () {
            this.selectedPurchaseRequest = '';
            this.quantity = '';
            this.price = '';
            this.payable = '';
            this.delivery = '';
        },
        addLineItem: function () {
            var self = this;
            if (self.canAjax) {
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
                        window.location = '/purchase_orders/submit';
                    },
                    error: function (res, status, error) {
                        console.log(res);
                        self.canAjax = true;
                    }
                });
            }
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
        }
    },
    computed: {
        subtotal: function () {
            return this.quantity * this.price;
        },
        validQuantity: function () {
            return (this.selectedPurchaseRequest.quantity >= this.quantity && this.quantity > 0);
        },
        canAddPurchaseRequest: function () {
            return (!!this.selectedPurchaseRequest && !!this.quantity & !!this.price && !!this.payable && !!this.delivery && this.validQuantity)
        }
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
                url: '/api/projects/' + self.projectToDelete.id,
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
Vue.component('purchase-orders-all',{
    name: 'allPurchaseOrders',
    el: function() {
        return '#purchase-orders-all';
    },
    data: function() {
        return {
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
        };
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
Vue.component('purchase-orders-submit', {
    el: function() {
        return '#purchase-orders-submit';
    },
    data: function() {
        return {
            vendorType: '',
            vendor_id: 'Choose an existing vendor',
            name: '',
            phone: '',
            address: '',
            bank_account_name: '',
            bank_account_number: '',
            bank_name: '',
            canAjax: true
        };
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
Vue.component('purchase-requests-all', {
    name: 'allPurchaseRequests',
    el: function () {
        return '#purchase-requests-all';
    },
    data: function () {
        return {
            response: {},
            order: '',
            urgent: '',
            filter: '',
            sort: '',
            showFilterDropdown: false,
            filters: [
                {
                    name: 'open',   // What gets sent to server
                    label: 'Open'   // Displayed to client
                },
                {
                    name: 'complete',
                    label: 'Completed'
                },
                {
                    name: 'cancelled',
                    label: 'Cancelled'
                },
                {
                    name: 'all',
                    label: 'All Statuses'
                }
            ],
            ajaxReady: true,
            finishLoading: false
        };
    },
    computed: {},
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
        checkShow: function (purchaseRequest) {
            switch (this.filter) {
                case 'complete':
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
        },
        setLoadQuery: function () {
            // The currenty query
            var currentQuery = window.location.href.split('?')[1];
            // If filter set - use query. Else - set a default for the filter
            currentQuery = getParameterByName('filter') ? currentQuery : updateQueryString('filter', 'open');
            return currentQuery;
        },
        fetchPurchaseRequests: function (query) {
            var url = query ? '/api/purchase_requests?' + query : '/api/purchase_requests';
            var self = this;

            // self.finishLoading = false;

            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    // Update data
                    self.response = response;

                    // Pull flags from response (better than parsing url)
                    self.filter = response.data.filter;
                    self.sort = response.data.sort;
                    self.order = response.data.order;
                    self.urgent = response.data.urgent;

                    // push state (if query is different from url)
                    pushStateIfDiffQuery(query);

                    document.getElementById('body-content').scrollTop = 0;
                    
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
        changeFilter: function (filter) {
            this.filter = filter;
            this.showFilterDropdown = false;
            this.fetchPurchaseRequests(updateQueryString({
                filter: filter.name,
                page: 1
            }));
        },
        toggleUrgentOnly: function () {
            var urgent = this.urgent ? 0 : 1;
            this.fetchPurchaseRequests(updateQueryString({
                filter: this.filter, // use same filter
                page: 1, // Reset to page 1
                urgent: urgent
            }));
        },
        changeSort: function (sort) {
            if (this.sort === sort) {
                var newOrder = (this.order === 'asc') ? 'desc' : 'asc';
                this.fetchPurchaseRequests(updateQueryString('order', newOrder));
            } else {
                this.fetchPurchaseRequests(updateQueryString({
                    sort: sort,
                    order: 'asc',
                    page: 1
                }));
            }
        }
    },
    ready: function () {
        // If exists
        this.fetchPurchaseRequests(this.setLoadQuery());

        onPopQuery(this.fetchPurchaseRequests);
    }
});
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
            url: '/api/team',
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
Vue.component('settings-company', {
    name: 'settingsCompany',
    template: '',
    el: function () {
        return '#settings-company';
    },
    data: function() {
        return {
            ajaxReady: true,
            company: false
        }
    },
    props: [
      'settingsView'
    ],
    computed: {
        canUpdateCompany: function () {
            if (this.company) {
                return this.company.name.length > 0 && this.company.currency.length > 0;
            }
            return false;
        }
    },
    methods: {
        updateCompany: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/company',
                method: 'PUT',
                data: {
                    name: self.company.name,
                    description: self.company.description,
                    currency: self.company.currency
                },
                success: function (data) {
                    // success
                    flashNotify('success', 'Updated Company information');
                    self.$dispatch('update-company');
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
    ready: function() {
        var self = this;
        // GET user company info
        $.ajax({
            url: '/api/company',
            method: 'GET',
            success: function (data) {
                // success
                self.company = data;
            },
            error: function (response) {
                console.log('Request Error!');
                console.log(response);
            }
        });

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
//# sourceMappingURL=page.js.map

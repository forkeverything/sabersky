Vue.component('items-all', {
    name: 'allItems',
    el: function () {
        return '#items-all';
    },
    data: function () {
        return {
            ajaxReady: true,
            items: [],
            itemsFilterDropdown: false,
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
            ],
            filter: '',
            filterValue: '',
            response: {},
            queryParams: {
                brand: '',
                name: '',
                project: ''
            },
            searchTerm: '',
            sort: '',
            order: '',
            lastPage: '',
            currentPage: '',
            itemsPerPage: '',
            ajaxObject: {}
        };
    },
    computed: {
        hasItems: function() {
            return !_.isEmpty(this.items);
        }
    },
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
                    self.items = _.omit(response.data, 'query_parameters');

                    self.queryParams = {};
                    _.forEach(response.data.query_parameters, function (value, key) {
                        self.queryParams[key] = value;
                    });

                    self.searchTerm = response.data.query_parameters.search;
                    self.sort = response.data.query_parameters.sort;
                    self.order = response.data.query_parameters.order;

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
        addItemsFilter: function () {
            var queryObj = {
                page: 1
            };
            queryObj[this.filter] = this.filterValue;
            this.getCompanyItems(updateQueryString(queryObj));

            // reset filter values
            this.filter = '';
            this.filterValue = '';

            // hide dropdown
            this.itemsFilterDropdown = false;

        },
        removeFilter: function (type) {
            var queryObj = {
                page: 1
            };
            queryObj[type] = null;
            this.getCompanyItems(updateQueryString(queryObj));
        },
        searchItemQuery: function () {
            var self = this;

            if (self.ajaxObject && self.ajaxObject.readyState != 4) self.ajaxObject.abort();

            if (self.searchTerm) {
                self.getCompanyItems(updateQueryString({
                    search: self.searchTerm,
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
        getItemProjects: function (item) {
            // Parses out project names from an Item's Purchase Requests
            var projects = [];
            _.forEach(item.purchase_requests, function (pr) {
                if (projects.indexOf(pr.project.name) === -1)projects.push(pr.project);
            });
            return projects;
        },
        removeAllFilters: function() {
            var self = this;
            var queryObj = {};
            _.forEach(self.filterOptions, function (option) {
                queryObj[option.value] = null;
            });
            this.getCompanyItems(updateQueryString(queryObj));

        },
        clearSearch: function() {
            this.searchTerm = '';
            this.searchItemQuery();
        }
    },
    events: {},
    ready: function () {

        this.getCompanyItems(this.setLoadQuery());
        onPopQuery(this.getCompanyItems);

    }
});
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
    el: function () {
        return '#purchase-orders-submit';
    },
    data: function () {
        return {
            ajaxReady: true,
            ajaxObject: {},
            response: {},
            projects: [],
            projectID: '',
            purchaseRequests: [],
            sort: '',
            order: '',
            urgent: '',
            searchTerm: '',
            selectedPRs: []
        };
    },
    computed: {
        hasPurchaseRequests: function() {
            return ! _.isEmpty(this.purchaseRequests);
        }
    },
    methods: {
        fetchPurchaseRequests: function (projectID, sort, order, page, search) {
            var self = this;

            sort = sort || 'number';
            order = order || 'asc';
            search = search || '';

            var url = '/api/purchase_requests?' +
                'state=open' +
                '&quantity=1+' +
                '&project_id=' + projectID +
                '&sort=' + sort +
                '&order=' + order +
                '&per_page=3' +
                '&search=' + search;

            if(page) url += '&page=' + page;
            
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
                var newOrder = (this.order === 'asc') ? 'desc' : 'asc';
                this.fetchPurchaseRequests(this.projectID, this.sort, newOrder);
            } else {
                this.fetchPurchaseRequests(this.projectID, sort, 'asc');
            }
        },
        searchPurchaseRequests: function() {
            var self = this;

            if (self.ajaxObject && self.ajaxObject.readyState != 4) self.ajaxObject.abort();

            self.fetchPurchaseRequests(self.projectID, self.sort, self.order, 1, self.searchTerm);
        },
        clearSearch: function() {
            this.searchTerm = '';
            this.searchPurchaseRequests();
        },
        selectPR: function(purchaseRequest) {
            this.alreadySelectedPR(purchaseRequest) ? this.selectedPRs = _.reject(this.selectedPRs, purchaseRequest) : this.selectedPRs.push(purchaseRequest) ;
        },
        alreadySelectedPR: function(purchaseRequest) {
            return _.find(this.selectedPRs, function(pr) {
                return pr.id === purchaseRequest.id;
            });
        }
    },
    events: {
        'go-to-page': function (page) {
            this.fetchPurchaseRequests(this.projectID, this.sort, 'asc', page);
        }
    },
    ready: function () {
        this.$watch('projectID', function (val) {
            if (val)this.fetchPurchaseRequests(val);
        });
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
            purchaseRequests: [],
            order: '',
            urgent: '',
            state: '',
            filter: '',
            sort: '',
            showStatesDropdown: false,
            showFiltersDropdown: false,

            filterValue: '',
            minFilterValue: ' ',
            maxFilterValue: ' ',

            activeFilters: {
               number_filter_integer: '',
                project: '',
                quantity_filter_integer: '',
                item_brand: '',
                item_name: ''
            },

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
            states: [
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
        setLoadQuery: function () {
            // The currenty query
            var currentQuery = window.location.href.split('?')[1];
            // If state set - use query. Else - set a default for the state
            currentQuery = getParameterByName('state') ? currentQuery : updateQueryString('state', 'open');
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
                    self.purchaseRequests = _.omit(response.data, 'query_parameters');

                    // Pull flags from response (better than parsing url)
                    self.state = response.data.query_parameters.state;
                    self.sort = response.data.query_parameters.sort;
                    self.order = response.data.query_parameters.order;
                    self.urgent = response.data.query_parameters.urgent;

                    // Attach filters
                        // Reset obj
                        self.activeFilters = {};
                        // Loop through and attach everything (Only pre-defined keys in data obj above will be accessible with Vue)
                        _.forEach(response.data.query_parameters, function (value, key) {
                            self.activeFilters[key] = value;
                        });


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
        changeState: function (state) {
            this.state = state;
            this.showStatesDropdown = false;
            this.fetchPurchaseRequests(updateQueryString({
                state: state.name,
                page: 1
            }));
        },
        toggleUrgentOnly: function () {
            var urgent = this.urgent ? 0 : 1;
            this.fetchPurchaseRequests(updateQueryString({
                state: this.state, // use same state
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
        },
        removeFilter: function (type) {
            var queryObj = {
                page: 1
            };
            queryObj[type] = null;
            this.fetchPurchaseRequests(updateQueryString(queryObj))
        },
        addPRsFilter: function() {
            var self = this;
            var value = self.filterValue || [self.minFilterValue, self.maxFilterValue];

            self.fetchPurchaseRequests(updateQueryString(self.filter, value));

            // Reset values
            this.filter = '';
            this.filterValue = '';
            this.minFilterValue = ' ';
            this.maxFilterValue = ' ';

            // Hide dropdown
            this.showFiltersDropdown = false;
        },
        removeAllFilters: function() {
            var self = this;
            var queryObj = {};
            _.forEach(self.filterOptions, function (option) {
                queryObj[option.value] = null;
            });
            this.fetchPurchaseRequests(updateQueryString(queryObj));
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
Vue.component('vendor-custom', {
    name: 'vendorCustom',
    el: function () {
        return '#vendor-single-custom'
    },
    data: function () {
        return {
            ajaxReady: true,
            vendorID: '',
            vendor: {},
            description: '',
            editDescription: false,
            savedDescription: ''
        };
    },
    props: [],
    computed: {},
    methods: {
        startEditDescription: function () {
            this.editDescription = true;
            this.$nextTick(function () {
                $('.description-editor').focus();
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
        setPrimary: function(address) {
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/address/' + address.id + '/set_primary',
                method: 'PUT',
                success: function(data) {
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
                error: function(response) {
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        removeAddress: function(address){
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/address/' + address.id,
                method: 'DELETE',
                success: function(data) {
                   // success
                    flashNotify('success', 'Removed address');
                    self.vendor.addresses = _.reject(self.vendor.addresses, address);
                   self.ajaxReady = true;
                },
                error: function(response) {
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        addBankAccount: function() {

        }
    },
    events: {
        'address-added': function(address) {
            this.vendor.addresses.push(address);
        }
    },
    ready: function () {
        var self = this;
        $.ajax({
            url: '/api/vendors/' + self.vendorID,
            method: 'GET',
            success: function(data) {
                self.vendor = data;
            },
            error: function(response) {
                console.log(response);
            }
        });
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
        var self = this;

        $('#vendor-search-company-selecter').selectize({
            valueField: 'id',
            searchField: ['name'],
            create: false,
            placeholder: 'Search by Company Name',
            render: {
                option: function (item, escape) {

                    var optionClass = 'class="option company-single-option ',
                        connectionSpan;

                    switch (item.connection) {
                        case 'pending':
                            optionClass += 'disabled"';
                            connectionSpan = '<span class="vendor-connection pending">pending</span>';
                            break;
                        case 'verified':
                            optionClass += 'disabled"';
                            connectionSpan = '<span class="vendor-connection verified">verified</span>';
                            break;
                        default:
                            optionClass += '"';
                            connectionSpan = '';
                    }


                    return '<div ' + optionClass +'>' +
                        '       <span class="name">' + escape(item.name) + '</span>' +
                                connectionSpan +
                        '   </div>'
                },
                item: function (item, escape) {

                    var selectedClass = 'class="company-selected ',
                        connectionSpan;

                    switch (item.connection) {
                        case 'pending':
                            selectedClass += 'disabled"';
                            connectionSpan = '<span class="connection pending"> <em>pending</em></span>';
                            break;
                        case 'verified':
                            selectedClass += 'disabled"';
                            connectionSpan = '<span class="connection verified"> <i class="fa fa-check"></i> <em>verified</em></span>';
                            break;
                        default:
                            selectedClass += '"';
                            connectionSpan = '';
                    }

                    return '<div ' + selectedClass + '>' +
                        '           <label>Selected Company</label>' +
                        '           <div class="name">' + escape(item.name) +
                                        connectionSpan +
                        '           </div>' +
                        '           <span class="description">' + escape(item.description) + '</span>' +
                        '       </div>' +
                        '</div>'
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/company/search/' + encodeURIComponent(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function (value) {
                self.linkedCompanyID = value;
            }
        });
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
//# sourceMappingURL=page.js.map

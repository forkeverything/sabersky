Vue.component('items-all', {
    name: 'allItems',
    el: function() {
        return '#items-all';
    },
    data: function() {
        return {
            items: []
        };
    },
    computed: {
        itemNames: function() {
            var names = [];
            _.forEach(this.items, function (item) {
                names.push(item.name);
            });
            return names;
        }
    },
    ready: function() {
        var self = this;
        $.ajax({
            url: '/api/items',
            method: 'GET',
            success: function(data) {
                self.items = data;
            },
            error: function(err) {
                console.log(err);
            }
        })
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
            lastPage: '',
            currentPage: '',
            showFilterDropdown: false,
            filters: [
                {
                    name: 'open',   // What gets sent to server
                    label: 'Open'   // Displayed to client
                },
                {
                    name: 'complete',
                    label: 'Fulfilled'
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
            finishLoading: false,
            itemsPerPage: 8,
            itemsPerPageOptions: [
                {
                    value: 8,
                    label: '8 Requests / Page'
                }, {
                    value: 16,
                    label: '16 Requests / Page'
                },
                {
                    value: 32,
                    label: '32 Requests / Page'
                }
            ]
        };
    },
    computed: {
        paginatedPages: function () {
            switch (this.currentPage) {
                case 1:
                case 2:
                    var endPage = (this.lastPage < 5) ? this.lastPage : 5;
                    return this.makePagesArray(1, endPage);
                    break;
                case this.lastPage:
                case this.lastPage - 1:
                    var startPage = (this.lastPage > 5) ? this.lastPage - 4 : 1;
                    var endPage = this.lastPage;
                    return this.makePagesArray(startPage, endPage);
                    break;
                default:
                    var startPage = this.currentPage - 2;
                    var endPage = this.currentPage + 2;
                    return this.makePagesArray(startPage, endPage);
            }
        }
    },
    methods: {
        makePagesArray: function (startPage, endPage) {
            var pagesArray = [];
            for (var i = startPage; i <= endPage; i++) {
                pagesArray.push(i);
            }
            return pagesArray;
        },
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

                    // set flags
                    self.filter = response.data.filter;
                    self.sort = response.data.sort;
                    self.order = response.data.order;
                    self.urgent = response.data.urgent;
                    self.lastPage = response.last_page;
                    self.currentPage = response.current_page;
                    self.itemsPerPage = response.per_page;

                    // push state (if query is different from url)
                    if (query !== window.location.href.split('?')[1]) {
                        window.history.pushState({}, "", '?' + query);
                    }
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
        updateQuery: function (name, value) {
            var fullQuery = window.location.href.split('?')[1];
            var queryArray = fullQuery ? fullQuery.split('&') : [];
            var queryObj = {};

            // Build up object
            queryArray.forEach(function (item) {
                var x = item.split('=');
                queryObj[x[0]] = x[1];
            });

            /**
             * TO DO CHECK HERE
             */
            if (typeof arguments[0] === 'string') {
                queryObj[arguments[0]] = arguments[1]; // Set the new name and value
            } else {
                // Received an object with key-value pairs of query names
                _.forEach(arguments[0], function (value, key) {
                    queryObj[key] = value;
                });
            }


            // _.forEach()

            var newQuery = '';

            _.forEach(queryObj, function (value, name) {
                newQuery += name + '=' + value + '&';
            });

            return newQuery.substring(0, newQuery.length - 1);  // Trim last '&'
        },
        goToPage: function (page) {
            if (0 < page && page <= this.lastPage) this.fetchPurchaseRequests(this.updateQuery('page', page));
        },
        changeFilter: function (filter) {
            this.filter = filter;
            this.showFilterDropdown = false;
            this.fetchPurchaseRequests(this.updateQuery({
                filter: filter.name,
                page: 1
            }));
        },
        toggleUrgentOnly: function () {
            var urgent = this.urgent ? 0 : 1;
            this.fetchPurchaseRequests(this.updateQuery({
                filter: this.filter, // use same filter
                page: 1, // Reset to page 1
                urgent: urgent
            }));
        },
        setLoadQuery: function () {
            var currentQuery = window.location.href.split('?')[1];
            currentQuery = getParameterByName('filter') ? currentQuery : this.updateQuery('filter', 'open');
            return currentQuery;
        },
        changeSort: function (sort) {
            if (this.sort === sort) {
                var newOrder = (this.order === 'asc') ? 'desc' : 'asc';
                this.fetchPurchaseRequests(this.updateQuery('order', newOrder));
            } else {
                this.fetchPurchaseRequests(this.updateQuery({
                    sort: sort,
                    order: 'asc',
                    page: 1
                }));
            }
        },
        changeItemsPerPage: function() {
            this.fetchPurchaseRequests(this.updateQuery({
                filter: this.filter, // use same filter
                page: 1, // Reset to page 1
                urgent: (this.urgent) ? 1 : 0, // Keep urgent flag
                per_page: this.itemsPerPage
            }));
        }
    },
    ready: function () {
        // If exists
        this.fetchPurchaseRequests(this.setLoadQuery());

        window.onpopstate = function (e) {
            if (e.state) {
                this.fetchPurchaseRequests(window.location.href.split('?')[1]);
            }
        }.bind(this);


        this.updateQuery('rina', 'boo');
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
            existingItem: true,
            items: [],
            existingItemName: '',
            selectedItem: '',
            uploadedFiles: [],
            ajaxReady: true,
            projectID: '',
            newItemName: '',
            newItemSpecification: '',
            quantity: '',
            due: '',
            urgent: ''
        };
    },
    methods: {
        changeExistingItem: function (state) {
            this.clearSelectedExisting();
            this.existingItem = state;
        },
        selectItemName: function (name) {
            this.existingItemName = name;
        },
        selectItem: function (item) {
            this.selectedItem = item;
        },
        clearSelectedExisting: function () {
            this.selectedItem = '';
            this.existingItemName = '';
            $('#select-new-item-name')[0].selectize.clear();
            $('#field-new-item-specification').val('');
            $('.input-item-photos').fileinput('clear');
        },
        submitMakePRForm: function() {
            var self = this;

            // Create new FormData Instance
            var fd = new FormData();

            // Attach our previously uploaded files to data
            _.forEach(self.uploadedFiles, function (file) {
                fd.append('item_photos[]', file);
            });

            // Append our other data
            fd.append('project_id', self.projectID);
            if(self.selectedItem) fd.append('item_id', self.selectedItem.id);
            fd.append('name', self.newItemName);
            fd.append('specification', self.newItemSpecification);
            fd.append('quantity', self.quantity);
            fd.append('due', self.due);
            
            // Send Req. via Ajax
            vueClearValidationErrors(self);
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/purchase_requests/make',
                method: 'POST',
                data: fd,
                contentType: false,
                processData: false,
                success: function(data) {
                   // success
                    console.log('success!');
                    console.log(data);
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

        // Initialize using selectize.js
        var newItemNameSelecter = $('#select-new-item-name').selectize({
            create: true,
            sortField: 'text',
            placeholder: 'Select or enter a new Item',
            createFilter: function (input) {
                // Filter that makes sure value names added are unique
                input = input.toLowerCase();
                var array = $.map(newItemNameSelecter.options, function (value) {
                    return [value];
                });
                var unmatched = true;
                _.forEach(array, function (option) {
                    if ((option.text).toLowerCase() === input) {
                        unmatched = false;
                    }
                });
                return unmatched;
            },
            onChange: function(value) {
                // When we select / enter a new value - enter it into our data
                self.newItemName = value;
            }
        })[0].selectize;

            // Initialize File Uploads
            $('#item-photos-upload').fileupload({
                autoUpload: false
            }).on('fileuploadadd', function (e, data) {
                _.forEach(data.files, function(file) {
                    self.uploadedFiles.push(file);
                });
            });

        // $("#form-make-purchase-request").submit(function(e) {
        //     if (uploadedFiles.length > 0) {
        //         e.preventDefault();
        //         $('#item-photos-upload').fileupload('send', {files: uploadedFiles})
        //             .complete(function (result, textStatus, jqXHR) {
        //                 // window.location='back to view-page after submit?'
        //             });
        //     } else {
        //         // plain default submit
        //     }
        // });
        // });

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

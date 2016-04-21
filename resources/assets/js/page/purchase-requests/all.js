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
            state: '',
            filter: '',
            sort: '',
            showStatesDropdown: false,
            showFiltersDropdown: false,
            numberFilterMin: ' ',
            numberFilterMax: ' ',
            activeNumberFilter: '',
            projects: [],
            filterProject: ' ',
            activeProjectFilter: '',
            filterOptions: [
                {
                    value: 'number',
                    label: '# Number'
                },
                {
                    value: 'project',
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
                    value: 'user',
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

                    // Pull flags from response (better than parsing url)
                    self.state = response.data.state;
                    self.sort = response.data.sort;
                    self.order = response.data.order;
                    self.urgent = response.data.urgent;
                        // Attach filters
                        self.activeNumberFilter = response.data.number_filter_integer;
                        self.activeProjectFilter = response.data.project;


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
        getProjects: function () {
            var self = this;
            $.ajax({
                url: '/api/user/projects',
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
        resetFilter: function() {
            this.filter = '';
            this.numberFilterMin = ' ';
            this.numberFilterMax = ' ';
            this.showFiltersDropdown = false;
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
            switch (self.filter) {
                case 'number':
                    self.fetchPurchaseRequests(updateQueryString('number', [self.numberFilterMin, self.numberFilterMax]));
                    break;
                case 'project':
                    self.fetchPurchaseRequests(updateQueryString('project_id', self.filterProject));
                    break;
            }
            self.resetFilter();
        }
    },
    ready: function () {
        this.getProjects();

        // If exists
        this.fetchPurchaseRequests(this.setLoadQuery());

        onPopQuery(this.fetchPurchaseRequests);
    }
});
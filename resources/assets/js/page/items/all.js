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
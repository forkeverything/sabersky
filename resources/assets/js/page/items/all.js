Vue.component('items-all', {
    name: 'allItems',
    el: function () {
        return '#items-all';
    },
    data: function () {
        return {
            ajaxReady: true,
            response: {},
            params: {},
            itemsFilterDropdown: false,
            filter: '',
            filterValue: '',
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
            ajaxObject: {}
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
        getCompanyItems: function (query) {
            var self = this,
                url = '/api/items';

            query = query || window.location.href.split('?')[1];
            if(query) url = url + '?' + query;


            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            self.ajaxObject = $.ajax({
                url: url,
                method: 'GET',
                success: function (response) {
                    self.response = response;

                    self.params = {};
                    _.forEach(response.data.query_parameters, function (value, key) {
                        self.params[key] = value;
                    });

                    // push state (if query is different from url)
                    pushStateIfDiffQuery(query);

                    // Scrolltop
                    document.getElementById('body-content').scrollTop = 0;

                    self.ajaxReady = true;
                },
                error: function (err) {
                    console.log(err);
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

            if (self.params.search) {
                self.getCompanyItems(updateQueryString({
                    search: self.params.search,
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
            if (this.params.sort === sort) {
                var newOrder = (this.params.order === 'asc') ? 'desc' : 'asc';
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
                projects.push(pr.project);
            });
            return _.uniqBy(projects, 'id');
        },
        removeAllFilters: function() {
            var queryObj = {};
            _.forEach(this.filterOptions, function (option) {
                queryObj[option.value] = null;
            });
            this.getCompanyItems(updateQueryString(queryObj));

        },
        clearSearch: function() {
            this.params.search = '';
            this.searchItemQuery();
        }
    },
    events: {},
    ready: function () {
        this.getCompanyItems();
        onPopCallFunction(this.getCompanyItems);
    }
});
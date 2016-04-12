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
            visibleAddItemModal: false,
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
        showAddItemModal: function () {
            this.visibleAddItemModal = true;
        },
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
                    console.log(err);
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
    },
    events: {
        'added-new-item': function (item) {
            this.items.push(item);
        }
    },
    ready: function () {

        this.getCompanyItems(this.setLoadQuery());
        this.getBrands();
        this.getProjects();

        onPopQuery(this.getCompanyItems);

    }
});
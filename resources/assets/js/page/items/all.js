Vue.component('items-all', {
    name: 'allItems',
    el: function() {
        return '#items-all';
    },
    data: function() {
        return {
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
            response: {}
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
    methods: {
        showAddItemModal: function() {
            this.visibleAddItemModal = true;
        },
        setLoadQuery: function() {
            var currentQuery = window.location.href.split('?')[1];

            return currentQuery
        },
        getCompanyItems: function(query) {
            var self = this;
            var url = query ? '/api/items?' + query : '/api/items';
            $.ajax({
                url: url,
                method: 'GET',
                success: function(response) {
                    self.response = response;
                },
                error: function(err) {
                    console.log(err);
                }
            });
        },
        getBrands: function() {
            var self = this;
            $.ajax({
                url: '/api/items/brands',
                method: 'GET',
                success: function(data) {
                    // success
                    self.brands = _.map(data, function(brand) {
                        if(brand.brand) {
                            brand.value = brand.brand;
                            brand.label = strCapitalize(brand.brand);
                            return brand;
                        }
                    });
                },
                error: function(response) {
                    console.log(response);
                }
            });
        },
        getProjects: function() {
            var self = this;
            $.ajax({
                url: '/api/projects',
                method: 'GET',
                success: function(data) {
                   // success
                    self.projects = _.map(data, function(project) {
                        if(project.name) {
                            project.value = project.name;
                            project.label = strCapitalize(project.name);
                            return project;
                        }
                    });
                },
                error: function(response) {
                    console.log(response);
                }
            });
        },
        addItemsFilter: function() {

        }
    },
    events: {
        'added-new-item': function (item) {
            this.items.push(item);
        }
    },
    ready: function() {

        this.getCompanyItems(this.setLoadQuery);
        this.getBrands();
        this.getProjects();

    }
});
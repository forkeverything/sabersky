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
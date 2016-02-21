new Vue({
    name: 'allItems',
    el: '#items-all',
    data: {
        items: []
    },
    computed: {
        uniqueItems: function() {
            var itemsArray = [];
            var self = this;
            var uniqueNames = _.uniq(_.map(this.items, 'name'));
            _.forEach(uniqueNames, function (name) {

                var itemWithPhoto = _.find(self.items, function (item) {
                    return (item.name === name && item.photos.length > 0);
                });

                var itemFirst = _.find(self.items, function (item) {
                    return item.name === name;
                });

                itemWithPhoto ? itemsArray.push(itemWithPhoto) : itemsArray.push(itemFirst);

            });
            return itemsArray;
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
    },
    methods: {
        getVariants: function(item) {
            // Get variants of an item name
            var givenItemName = item.name;
            return _.filter(this.items, function(item) {
                return item.name === givenItemName;
            });
        },
        getProjects: function(item) {
            var variants = this.getVariants(item);
            var projects = [];
            _.forEach(variants, function (variant) {
                _.forEach(variant.projects, function (project) {
                    projects.push(project.name);
                });
            });
            return _.uniq(projects);
        }
    }
});
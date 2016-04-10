Vue.component('items-all', {
    name: 'allItems',
    el: function() {
        return '#items-all';
    },
    data: function() {
        return {
            items: [],
            visibleAddItemModal: false
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
        }
    },
    events: {
        'added-new-item': function (item) {
            this.items.push(item);
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
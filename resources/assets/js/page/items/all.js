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
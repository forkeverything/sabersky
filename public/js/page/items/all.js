new Vue({
    name: 'allItems',
    el: '#items-all',
    data: {
        items: []
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
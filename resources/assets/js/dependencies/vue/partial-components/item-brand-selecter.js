Vue.component('item-brand-selecter', {
    name: 'itemBrandSelecter',
    template: '<select class="item-brand-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.item-brand-search-selecter').selectize({
            valueField: 'brand',
            searchField: 'brand',
            create: false,
            placeholder: 'Search for a brand',
            render: {
                option: function(item, escape) {
                    return '<div class="single-brand-option">' + escape(item.brand) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-brand">' + escape(item.brand) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/items/brands/search/' + encodeURI(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function(value) {
                self.name = value;
            }
        });
    }
});
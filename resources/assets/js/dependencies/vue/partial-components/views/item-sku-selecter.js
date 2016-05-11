Vue.component('item-sku-selecter', {
    name: 'itemSKUSelecter',
    template: '<select class="item-sku-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.item-sku-search-selecter').selectize({
            valueField: 'sku',
            searchField: 'sku',
            create: false,
            placeholder: 'Search for SKU',
            render: {
                option: function(item, escape) {
                    return '<div class="single-sku-option">' + escape(item.sku) + ' - ' + escape(item.name) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-sku">' + escape(item.sku) + ' - ' + escape(item.name) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/items/search/sku/' + encodeURI(query),
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
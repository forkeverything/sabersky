Vue.component('vendor-selecter', {
    name: 'vendorSelecter',
    template: '<select class="vendor-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name'],
    ready: function() {
        var self = this;
        $('.vendor-search-selecter').selectize({
            valueField: 'id',
            searchField: 'name',
            maxItems: 1,
            create: false,
            placeholder: 'Search for vendor',
            render: {
                option: function(item, escape) {
                    return '<div class="single-vendor-option">' + escape(item.name) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-vendor">' + escape(item.name) + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/api/vendors/search/' + encodeURI(query),
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
Vue.component('vendor-selecter', {
    name: 'vendorSelecter',
    template: '<select class="vendor-search-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['vendor'],
    methods: {
        clearVendor: function () {
            this.vendor = {
                linked_company: {},
                addresses: [],
                bank_accounts: []
            };
        },
        fetchVendor: function(vendorID) {
            $.get('/api/vendors/' + vendorID, function (data) {
                this.vendor = data;
            }.bind(this));
        }
    },
    ready: function () {
        var self = this;
        $('.vendor-search-selecter').selectize({
            valueField: 'id',
            searchField: 'name',
            maxItems: 1,
            create: false,
            placeholder: 'Search for vendor',
            render: {
                option: function (item, escape) {
                    return '<div class="single-vendor-option">' + escape(item.name) + '</div>'
                },
                item: function (item, escape) {
                    return '<div class="selected-vendor">' + escape(item.name) + '</div>'
                }
            },
            load: function (query, callback) {
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
            onChange: function (value) {
                vueEventBus.$emit('po-submit-selected-vendor');
                value ? self.fetchVendor(value) : self.clearVendor();
            }
        });
    }
});
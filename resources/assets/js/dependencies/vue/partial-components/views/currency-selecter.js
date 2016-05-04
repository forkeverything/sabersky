Vue.component('currency-selecter', {
    name: 'currencySelecter',
    template: '<select class="currency-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name', 'default'],
    ready: function() {
        var self = this;
        var selecter = $('.currency-selecter').selectize({
            valueField: 'id',
            searchField: ['name', 'currency', 'currency_code', 'currency_symbol'],
            create: false,
            placeholder: 'Search for a currency',
            render: {
                option: function(item, escape) {
                    return '<div class="option-currency">' + escape(item.name) + ' - ' + escape(item.currency_symbol) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-currency">' + escape(item.name) + ' - ' + escape(item.currency_symbol)  + '</div>'
                }
            },
            load: function(query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/countries/currency/search/' + encodeURI(query),
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
                if(! value) self.name = '';
                $.get('/countries/' + value, function (data) {
                    self.name = data;
                });
            }
        });

        // Setting the default (company's saved) currency
        var _selecter = selecter[0].selectize;
        var defaultCurrency;

        self.$watch('default', function (value) {
            // if we've already added it, return
            if(defaultCurrency && defaultCurrency.id === value.id) return;
            defaultCurrency = value;
            _selecter.addOption(value);
            _selecter.setValue(value.id);
        });
    }
});
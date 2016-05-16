Vue.component('currency-selecter', {
    name: 'currencySelecter',
    template: '<select class="currency-selecter">' +
    '<option></option>' +
    '</select>',
    props: ['name', 'default', 'id'],
    ready: function() {
        var self = this;
        var selecter = $('.currency-selecter').selectize({
            valueField: 'id',
            searchField: ['country_name', 'name', 'code', 'symbol'],
            create: false,
            placeholder: 'Search for a currency',
            maxItems: 1,
            render: {
                option: function(item, escape) {
                    return '<div class="option-currency">' + escape(item.country_name) + ' - ' + escape(item.symbol) + '</div>'
                },
                item: function(item, escape) {
                    return '<div class="selected-currency">' + escape(item.country_name) + ' - ' + escape(item.symbol)  + '</div>'
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
                self.id = value;

                if(! value) {
                    self.name = '';
                    return;
                }

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
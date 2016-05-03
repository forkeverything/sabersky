Vue.component('country-selecter', {
    name: 'countrySelecter',
    template: '<select class="country-selecter"><option></option></select>',
    data: function() {
        return {

        };
    },
    props: ['name', 'event', 'default'],
    computed: {

    },
    methods: {

    },
    events: {

    },
    ready: function() {
        var self = this,
            select_country;
        $select_country = $(self.$el).selectize({
            valueField: 'id',
            searchField: 'name',
            create: false,
            placeholder: 'Country',
            render: {
                option: function (item, escape) {
                    return '<div class="single-country-option">' + escape(item.name) + '</div>'
                },
                item: function (item, escape) {
                    return '<div class="selected-country">' + escape(item.name) + '</div>'
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/countries/search/' + encodeURIComponent(query),
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
                if (!value.length) return;

                // Update the name prop to pass data onto parent component
                self.name = value;

                var eventName = self.event || 'selected-country';
                // Fire event
                vueEventBus.$emit(eventName, value);
            }
        });

        select_country = $select_country[0].selectize;
        // IF we got a default country ID
        self.$watch('default', function (countryID) {
            // fetch associated country
            $.get('/countries/' + countryID, function(data) {
                // Add option
                select_country.addOption(data);

                // Select the option - we set to silent because there may be other
                // selecters watching this one for changes, and they may have
                // their own default values: ie. state-selecter
                select_country.setValue(countryID, true);

                // Update the name value
                self.name = countryID;
            });
        });
    }
});
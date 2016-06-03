Vue.component('select-type', {
    name: 'selectType',
    template: '<select class="select-type" v-show="receivedOptions">' +
    '<option></option>' +
    '               <option value="{{ option.value }}" v-for="option in options">{{ option.label }}</option>' + '' +
    '          </select>',
    data: function () {
        return {
            receivedOptions: false,
            selectize: {}
        };
    },
    props: [
        'name',
        'options',
        'create',
        'unique',
        'placeholder'
    ],
    ready: function () {


        var self = this;

        var unique = this.unique !== false,
            create = this.create !== false;
            placeholder = this.placeholder || 'Type to select...';

        this.$watch('name', function (value) {
            if(! value)this.selectize.clear();
        });

        this.$watch('options', function () {
            this.receivedOptions = true;
            if (!_.isEmpty(this.selectize)) this.selectize.destroy();
            this.selectize = $(this.$el).selectize({
                create: create,
                sortField: 'text',
                placeholder: placeholder,
                createFilter: function (input) {
                    input = input.toLowerCase();
                    var optionsArray = $.map(unique.options, function (value) {
                        return [value];
                    });
                    var unmatched = true;
                    _.forEach(optionsArray, function (option) {
                        if ((option.text).toLowerCase() === input) {
                            unmatched = false;
                        }
                    });
                    return unmatched;   // true if unmatched (ie. new) value
                },
                onChange: function (value) {
                    // When we select / enter a new value - enter it into our data
                    self.name = value;
                }
            })[0].selectize;
            // Let parent component know select is loaded
            this.$dispatch('select-loaded');
        });


        // TODO :: Add ability to re-render when options changes
        //      - Maybe define options on selectize and render options / item through plugin (instead of Vue)
        //      - Call clearOption()?
        //      - Clear Cache? Some bug, unknown if fixed

    },
    beforeDestroy: function () {
        this.selectize.destroy();   // TODO :: Check if valid & necessary
    }
});
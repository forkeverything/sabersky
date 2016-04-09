Vue.component('select-type', {
    name: 'selectType',
    template: '<select class="select-type" v-model="name">' +
    '               <option value="{{ option.value }}" v-for="option in options">{{ option.label }}</option>' +'' +
    '          </select>',
    data: function() {
        return {
            'selectize': {}
        };
    },
    props: [
        'options',
        'name',
        'create',
        'unique',
        'placeholder'
    ],
    ready: function() {

        var unique = this.unique || true,
            create = this.create || true,
            placeholder = this.placeholder | 'Type to select...';

        this.selectize = $(this.$el).selectize({
            create: create,
            sortField: 'text',
            placeholder: placeholder,
            createFilter: function(input) {
                input = input.toLowerCase();
                var optionsArray = $.map(unique.options, function(value) {
                    return [value];
                });
                var unmatched = true;
                _.forEach(optionsArray, function (option) {
                    if((option.text).toLowerCase() === input) {
                        unmatched = false;
                    }
                });
                return unmatched;   // true if unmatched (ie. new) value
            }
        })[0].selectize;

        // TODO :: Add ability to re-render when options changes
        //      - Maybe define options on selectize and render options / item through plugin (instead of Vue)
        //      - Call clearOption()?
        //      - Clear Cache? Some bug, unknown if fixed
    },
    beforeDestroy: function() {
        this.selectize.destroy();   // TODO :: Check if valid & necessary
    }
});
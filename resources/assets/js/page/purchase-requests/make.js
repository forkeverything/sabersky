/**
 * PR - Make
 */

new Vue({
    name: 'makePurchaseRequest',
    el: '#purchase-requests-add',
    data: {
        existingItem: true,
        items: [],
        existingItemName: '',
        selectedItem: ''
    },
    methods: {
        changeExistingItem: function (state) {
            this.clearSelectedExisting();
            this.existingItem = state;
        },
        selectItemName: function(name) {
            this.existingItemName = name;
        },
        selectItem: function(item) {
            this.selectedItem = item;
        },
        clearSelectedExisting: function() {
            this.selectedItem = '';
            this.existingItemName = '';
            $('#select-new-item-name')[0].selectize.clear();
            $('#field-new-item-specification').val('');
        }
    },
    computed: {
        uniqueItemNames: function () {
            return _.uniqBy(this.items, 'name');
        },
        itemsWithName: function () {
            return _.filter(this.items, {'name': this.existingItemName});
        }
    },
    ready: function () {
        var self = this;
        $.ajax({
            url: '/api/items',
            method: 'GET',
            success: function (data) {
                self.items = data;
            }
        });

        var unique = $('#select-new-item-name').selectize({
            create: true,
            sortField: 'text',
            placeholder: 'Choose an existing name or enter a new one...',
            createFilter: function(input) {
                input = input.toLowerCase();
                var array = $.map(unique.options, function(value) {
                    return [value];
                });
                var unmatched = true;
                _.forEach(array, function (option) {
                    if((option.text).toLowerCase() === input) {
                        unmatched = false;
                    }
                });
                return unmatched;
            }
        })[0].selectize;
    }
});


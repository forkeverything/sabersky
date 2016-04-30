Vue.component('per-page-picker', {
    name: 'itemsPerPagePicker',
    template: '<div class="per-page-picker">' +
    '<span>Results Per Page</span>' +
    '<select-picker :name.sync="newItemsPerPage" :options.sync="itemsPerPageOptions" :function="changeItemsPerPage"></select-picker>' +
    '</div>',
    el: function() {
        return ''
    },
    data: function() {
        return {
            newItemsPerPage: '',
            itemsPerPageOptions: [
                {
                    value: 8,
                    label: 8
                }, {
                    value: 16,
                    label: 16
                },
                {
                    value: 32,
                    label: 32
                }
            ]
        };
    },
    props: ['response', 'reqFunction'],
    computed: {
        itemsPerPage: function() {
            return this.response.per_page;
        }
    },
    methods: {
        changeItemsPerPage: function() {
            var self = this;
            if(self.newItemsPerPage !== self.itemsPerPage) {
                self.reqFunction(updateQueryString({
                    page: 1, // Reset to page 1
                    per_page: self.newItemsPerPage // Update items per page
                }));
            }
        }
    }
});
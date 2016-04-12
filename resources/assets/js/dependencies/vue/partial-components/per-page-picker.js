Vue.component('per-page-picker', {
    name: 'itemsPerPagePicker',
    template: '<div class="per-page-picker">' +
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
                    label: '8 Requests / Page'
                }, {
                    value: 16,
                    label: '16 Requests / Page'
                },
                {
                    value: 32,
                    label: '32 Requests / Page'
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
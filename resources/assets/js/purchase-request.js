$(document).ready(function () {

    /**
     * PR - VIEW ALL
     */
    new Vue({
        name: 'allPurchaseRequests',
        el: '#purchase-requests-all',
        data: {
            purchaseRequests: [],
            headings: [
                ['due', 'Due Date'],
                ['project.name', 'Project'],
                ['item.name', 'Item'],
                ['specification', 'Specification'],
                ['quantity', 'Quantity'],
                ['user.name', 'Made by'],
                ['created_at', 'Requested']
            ],
            field: '',
            order: '',
            urgent: '',
            filter: ''
        },
        ready: function () {
            var self = this;
            $.ajax({
                url: '/api/purchase_requests',
                method: 'GET',
                success: function (data) {
                    self.purchaseRequests = data;
                },
                error: function (res, status, req) {
                    console.log(status);
                }
            });
        },
        methods: {
            loadSinglePR: function (id) {
                window.document.location = '/purchase_requests/single/' + id;
            },
            changeSort: function ($newField) {
                if (this.field == $newField) {
                    this.order = (this.order == '') ? -1 : '';
                } else {
                    this.field = $newField;
                    this.order = ''
                }
            },
            toggleUrgent: function () {
                this.urgent = (this.urgent) ? '' : 1;
            },
            changeFilter: function (filter) {
                this.filter = filter;
            },
            checkShow: function (purchaseRequest) {
                switch (this.filter) {
                    case 'complete':
                        console.log(purchaseRequest.state);
                        if (purchaseRequest.state == 'Open' && purchaseRequest.quantity == '0') {
                            return true;
                        }
                        break;
                    case 'cancelled':
                        if (purchaseRequest.state == 'Cancelled') {
                            return true;
                        }
                        break;
                    default:
                        if (purchaseRequest.quantity > 0 && purchaseRequest.state !== 'Cancelled') {
                            return true;
                        }
                }
            }
        }
    });
});

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

            $('#select-new-item-name').selectize({
                create: true,
                sortField: 'text',
                placeholder: 'Choose an existing name or enter a new one...'
            });
        }
    });




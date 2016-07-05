Vue.component('purchase-request-single', {
    name: 'PurchaseRequestSingle',
    el: function() {
        return '#purchase-request-single'
    },
    data: function() {
        return {
            ajaxReady: true,
            showConfirm: false
        };
    },
    props: ['purchase-request'],
    computed: {
        canFulfill: function() {
            return this.purchaseRequest.state === 'open' && this.purchaseRequest.quantity > 0
        },
        lineItems: function() {
            // Only return first 5 line items
            return _.take(this.purchaseRequest.item.line_items, 5);
        },
        numOpenRequests: function() {
            return _.filter(this.purchaseRequest.project.purchase_requests, function(o) {
                return o.state === 'open';
            }).length;
        },
        numCompleteRequests: function() {
            return _.filter(this.purchaseRequest.project.purchase_requests, function(o) {
                return o.quantity === 0;
            }).length;
        },
        numCancelledRequests: function() {
            return _.filter(this.purchaseRequest.project.purchase_requests, function(o) {
                return o.state === 'cancelled';
            }).length;
        }
    },
    methods: {
        toggleConfirm: function() {
            this.showConfirm = !this.showConfirm;
        },
        sendRequest: function(action) {
            this.showConfirm = false;
            var method = 'DELETE';
            var url = '/purchase_requests/' + this.purchaseRequest.id;

            if(action === 'reopen') {
                method = 'GET';
                url += '/reopen';
            }

            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: url,
                method: method,
                success: function(data) {
                    // Updated using pusher
                    self.ajaxReady = true;
                },
                error: function(response) {
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {

    },
    mixins: [userCompany],
    ready: function() {
        var self = this;
        pusherChannel.bind('App\\Events\\PurchaseRequestUpdated', function(data) {
            console.log(data);
            // status
            self.purchaseRequest.state = data.purchaseRequest.state;
            // qty
            self.purchaseRequest.quantity = data.purchaseRequest.quantity;
            self.purchaseRequest.fulfilled_quantity = data.purchaseRequest.fulfilled_quantity;
            self.purchaseRequest.initial_quantity = data.purchaseRequest.initial_quantity;
        });
    }
});
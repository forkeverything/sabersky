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
                    location.reload();
                },
                error: function(response) {
                    console.log(response);
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
            self.purchaseRequest = data.purchaseRequest;
            console.log(data);
        });
    }
});
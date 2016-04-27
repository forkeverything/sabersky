Vue.component('vendor-requests', {
    name: 'vendorRequests',
    el: function() {
        return '#vendor-requests'
    },
    data: function() {
        return {
            ajaxReady: true,
            pendingVendors: []
        };
    },
    props: [],
    computed: {
        
    },
    methods: {
        respondRequest: function(vendor, action) {
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + vendor.id + '/request/' + action,
                method: 'POST',
                success: function(data) {
                   // success
                    self.pendingVendors = _.reject(self.pendingVendors, vendor);
                    if(action === 'verify') flashNotify('success', 'Verified vendor request');
                   self.ajaxReady = true;
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
    ready: function() {
        // Fetch Companies that have pending Vendor requests to user's
        var self = this;
        $.ajax({
            url: '/api/vendors/pending_requests',
            method: 'GET',
            success: function(data) {
               self.pendingVendors = data;
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
});
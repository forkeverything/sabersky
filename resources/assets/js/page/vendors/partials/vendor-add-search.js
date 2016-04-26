Vue.component('vendor-add-search', {
    name: 'vendorAddSearchCompany',
    el: function () {
        return '#vendor-add-search'
    },
    data: function () {
        return {
            ajaxReady: true,
            linkedCompanyID: ''
        };
    },
    props: ['currentTab'],
    computed: {},
    methods: {
        addCompanyAsNewVendor: function() {
            var self = this;
            vueClearValidationErrors(self);
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/link',
                method: 'POST',
                data: {
                    "linked_company_id": self.linkedCompanyID
                },
                success: function(data) {
                   // success
                    flashNotifyNextRequest('success', 'Sent request to link Company as a Vendor');
                    location.href = "/vendors";
                   self.ajaxReady = true;
                },
                error: function(response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {},
    ready: function () {
    }
});
Vue.component('vendor-single-link-company', {
    name: 'vendorLinkCompany',
    template:  '<form class="form-link-company" v-else @submit.prevent="linkCompany" v-if="! vendor.linked_company_id">'+
    '               <form-errors></form-errors>'+
    '               <div class="form-group">'+
    '                   <p class="text-muted">Search for this Vendor on SaberSky</p>'+
    '                   <company-search-selecter :name.sync="companyIDToLink"></company-search-selecter>'+
    '               </div>'+
    '               <button type="submit" class="btn btn-solid-blue btn-full btn-small" v-show="companyIDToLink" :disabled="! companyIDToLink">Send Link Request</button>'+
    '            </form>',
    data: function() {
        return {
            ajaxReady: true,
            companyIDToLink: ''
        };
    },
    props: ['vendor'],
    computed: {

    },
    methods: {
        linkCompany: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/link',
                method: 'POST',
                data: {
                    "vendor_id": self.vendor.id,
                    "linked_company_id": self.companyIDToLink
                },
                success: function (data) {
                    // success
                    flashNotify('success', 'Linked company to vendor');
                    self.companyIDToLink = '';
                    self.vendor = data;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log(response);
                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {

    },
    ready: function() {

    }
});
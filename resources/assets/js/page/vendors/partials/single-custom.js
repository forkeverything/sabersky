Vue.component('vendor-custom', {
    name: 'vendorCustom',
    el: function () {
        return '#vendor-single-custom'
    },
    data: function () {
        return {
            ajaxReady: true,
            vendorID: '',
            vendor: {},
            description: '',
            editDescription: false,
            savedDescription: ''
        };
    },
    props: [],
    computed: {},
    methods: {
        startEditDescription: function () {
            this.editDescription = true;
            this.$nextTick(function () {
                $('.description-editor').focus();
            });
        },
        saveDescription: function () {
            this.editDescription = false;
            this.savedDescription = 'saving';
            var self = this;
            if (!self.ajaxReady) {
                self.savedDescription = 'error';
                return;
            }
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendorID + '/description',
                method: 'POST',
                data: {
                    "description": self.description
                },
                success: function (data) {
                    // success
                    self.savedDescription = 'success';
                    self.vendor.description = self.description;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    self.savedDescription = 'error';
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {
        'address-added': function(address) {
            this.vendor.addresses.push(address);
        }
    },
    ready: function () {
        var self = this;
        $.ajax({
            url: '/api/vendors/' + self.vendorID,
            method: 'GET',
            success: function(data) {
                self.vendor = data;
            },
            error: function(response) {
                console.log(response);
            }
        });
    }
});
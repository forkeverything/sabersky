Vue.component('vendor-custom', {
    name: 'vendorCustom',
    el: function () {
        return '#vendor-single-custom'
    },
    data: function () {
        return {
            ajaxReady: true,
            vendorID: '',
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
                    self.ajaxReady = true;
                },
                error: function (response) {
                    self.savedDescription = 'error';
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {},
    ready: function () {


    }
});
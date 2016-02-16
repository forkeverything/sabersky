new Vue({
    name: 'Settings',
    el: '#system-settings',
    data: {
        settings: [],
        ajaxReady: true
    },
    ready: function() {
        var self = this;
        $.ajax({
            url: '/api/settings',
            method: 'GET',
            success: function(data) {
                self.settings = data;
            },
            error: function(err) {
                console.log(err);
            }
        });
    },
    methods: {
        saveSettings: function() {
            var self = this;
            if(self.ajaxReady) {
                self.ajaxReady = false;
                $.ajax({
                    url: '/settings',
                    method: 'POST',
                    data: self.settings,
                    success: function (data) {
                        console.log('Successfully saved settings');
                        self.ajaxReady = true;
                        flashNotify('success', 'Successfully updated settings')
                    },
                    error: function (err) {
                        console.log(err);
                        self.ajaxReady = true;
                    }
                });
            }
        }
    },
    computed: {
        saveButtonText: function() {
            return this.ajaxReady ? 'Save Settings' : 'Saving...';
        }
    }
});
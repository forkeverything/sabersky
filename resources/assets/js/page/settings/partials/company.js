Vue.component('settings-company', {
    name: 'settingsCompany',
    template: '',
    el: function () {
        return '#settings-company';
    },
    data: function() {
        return {
            ajaxReady: true,
            company: false
        }
    },
    props: [
      'settingsView',
        'user'
    ],
    computed: {
        canUpdateCompany: function () {
                if(this.user) return this.user.company.name;
            return false;
        }
    },
    methods: {
        updateCompany: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/company',
                method: 'PUT',
                data: {
                    name: self.user.company.name,
                    description: self.user.company.description,
                    currency_id: self.user.company.settings.currency_id,
                    currency_decimal_points: self.user.company.settings.currency_decimal_points
                },
                success: function (data) {
                    // success
                    flashNotify('success', 'Updated Company information');
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    ready: function() {
        var self = this;
    }
});
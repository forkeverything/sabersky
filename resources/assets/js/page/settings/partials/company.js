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
      'settingsView'
    ],
    computed: {
        canUpdateCompany: function () {
            if (this.company) {
                return this.company.name.length > 0;
            }
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
                    name: self.company.name,
                    description: self.company.description,
                    currency: self.company.currency
                },
                success: function (data) {
                    // success
                    flashNotify('success', 'Updated Company information');
                    self.$dispatch('update-company');
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
        // GET user company info
        $.ajax({
            url: '/api/company',
            method: 'GET',
            success: function (data) {
                // success
                self.company = data;
            },
            error: function (response) {
                console.log('Request Error!');
                console.log(response);
            }
        });

    }
});
Vue.component('settings-company', {
    name: 'settingsCompany',
    template: '',
    el: function () {
        return '#settings-company';
    },
    data: function () {
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
            if (this.user) return this.user.company.name;
            return false;
        },
        userCurrency: {
            get: function () {
                return this.user.company.settings.currency;
            },
            set: function (newValue) {
                // if we get a object
                if (newValue !== null && typeof newValue === 'object') {
                    // Update currency ID property (server persistence)
                    this.user.company.settings.currency_id = newValue.id;
                    // Update currency object (client)
                    this.user.company.settings.currency = newValue;
                }
            }
        },
        currencyDecimalPoints: function () {
            return this.user.company.settings.currency_decimal_points;
        }
    },
    methods: {
        updateCompany: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/company',
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
    ready: function () {
        var self = this;
    }
});
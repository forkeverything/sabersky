Vue.component('settings-company', {
    name: 'settingsCompany',
    template: '',
    el: function () {
        return '#settings-company';
    },
    data: function () {
        return {
            ajaxReady: true,
            company: false,
            selectedCurrency: ''
        }
    },
    props: [
        'settingsView'
    ],
    computed: {
        canUpdateCompany: function () {
            if (this.user) return this.user.company.name;
            return false;
        },
        currencyDecimalPoints: function () {
            return this.user.company.settings.currency_decimal_points;
        },
        canAddCurrency: function () {
            return this.selectedCurrency && !_.find(this.availableCurrencies, {id: this.selectedCurrency.id});
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
        },
        addCurrency: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/company/currencies',
                method: 'POST',
                data: {
                    "currency_id": self.selectedCurrency.id
                },
                success: function (data) {
                    self.selectedCurrency = '';
                    self.user.company = data;
                    self.ajaxReady = true;
                    vueEventBus.$emit('updated-company-currency');
                },
                error: function (response) {
                    self.selectedCurrency = '';
                    self.ajaxReady = true;
                }
            });
        },
        removeCurrency: function(currency) {
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/company/currencies/' + currency.id,
                method: 'DELETE',
                success: function(data) {
                    self.user.company = data;
                   self.ajaxReady = true;
                    vueEventBus.$emit('updated-company-currency');
                },
                error: function(response) {
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        }
    },
    mixins: [userCompany],
    ready: function () {
        var self = this;
    }
});
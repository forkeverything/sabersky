var userCompany = {
    props: ['user'],
    computed: {
        company: function () {
            return this.user.company;
        },
        availableCurrencies: function() {
            if(! this.user.id) return [];
            return this.user.company.settings.currencies;
        },
        companyCurrencies: function() {
            if(! this.user.id) return [];
            return this.user.company.currencies;
        },
        currencyDecimalPoints: function () {
            return this.user.company.settings.currency_decimal_points;
        },
        companyAddress: function () {
            if (_.isEmpty(this.user.company.address)) return false;
            return this.user.company.address;
        },
        PORequiresAddress: function () {
            return this.user.company.settings.po_requires_address;
        },
        PORequiresBankAccount: function () {
            return this.user.company.settings.po_requires_bank_account;
        }
    }
};
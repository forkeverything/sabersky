var userCompany = {
    props: ['user'],
    computed: {
        company: function () {
            return this.user.company;
        },
        userCurrency: function () {
            return this.user.company.settings.currency;
        },
        currencySymbol: function () {
            return this.currency ? this.currency.currency_symbol : this.userCurrency.currency_symbol;
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
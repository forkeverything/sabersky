var numberFormatter = {
    created: function () {
    },
    methods: {
        formatNumber: function (number) {
            return accounting.formatNumber(number, this.user.company.settings.currency_decimal_points, ',');
        }
    }
};
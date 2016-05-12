var numberFormatter = {
    created: function () {
    },
    methods: {
        formatNumber: function (number, decimalPoints, currencySymbol) {

            // Default decimal points
            if(decimalPoints === null || decimalPoints === '') decimalPoints = 2;

            // If we gave a currency symbol - format it as money
            if(currencySymbol) return accounting.formatMoney(number, currencySymbol, decimalPoints, ',');

            // otherwise just a norma lnumber format will do
            return accounting.formatNumber(number, decimalPoints, ',');
        }
    }
};
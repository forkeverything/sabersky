var numberFormatter = {
    created: function () {
    },
    methods: {
        formatNumber: function (number, decimalPoints) {
            if(decimalPoints === null || decimalPoints === '') decimalPoints = 2;
            return accounting.formatNumber(number, decimalPoints, ',');
        }
    }
};
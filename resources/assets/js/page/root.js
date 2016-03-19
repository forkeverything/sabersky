new Vue({
    el: '#app-layout',
    data: {
        currencySymbol: '$'
    },
    events: {
        'update-currency': function(newCurrency) {
            this.currencySymbol = newCurrency;
            localStorage.setItem('currency', newCurrency);
        }
    },
    ready: function () {
        var self = this;

        // Set Company Currency
        var savedCurrency = localStorage.getItem('currency');
        if (savedCurrency) {
            self.currencySymbol = savedCurrency;
        } else {
            $.ajax({
                url: '/api/company/currency',
                method: 'GET',
                success: function (data) {
                    // success
                    self.currencySymbol = data;
                    localStorage.setItem('currency', data);
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                }
            });
        }
    }
});
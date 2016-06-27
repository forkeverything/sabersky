Vue.component('settings-billing', {
    name: 'settingsBillingPage',
    el: function() {
        return '#settings-billing'
    },
    data: function() {
        return {
            ajaxReady: true,
            showCreditCardForm: false
        };
    },
    props: [],
    computed: {
    },
    methods: {
        toggleCreditCardForm: function() {
            this.showCreditCardForm = !this.showCreditCardForm;
        },
        activateNewSubscription: function(creditCardToken) {
            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/subscription/new',
                method: 'POST',
                data: {
                    "credit_card_token": creditCardToken
                },
                success: function(data) {
                   // success
                    location.reload();
                   self.ajaxReady = true;
                },
                error: function(response) {
                    console.log(response);
                    flashNotify('error', 'Could not activate subscription');
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {
        
    },
    ready: function() {
        vueEventBus.$on('new-cc-token', function (creditCardToken) {
            this.activateNewSubscription(creditCardToken);
        }.bind(this));
    }
});
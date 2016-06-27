Vue.component('form-credit-card', {
    name: 'creditCardform',
    template: '<form id="form-registration-billing" @submit.prevent="submitCard" v-el:stripe-form class="credit-card-form">'+
    '<div class="shift-label-input validated-input" '+
    ':class="{'+
        "'is-filled': ccNumber,"+
        "'is-error': cardError.param === 'number'"+
    '}">'+
    '<input data-stripe="number" type="text" required size="20" v-model="ccNumber" >'+
    '<label placeholder="Card Number"></label>'+
    '</div>'+
    '<div class="row">'+
    '<div class="col-sm-6">'+
    '<div class="shift-label-input">'+
    '<input data-stripe="name" type="text" required v-model="ccName">'+
    '<label placeholder="Name On Card"></label>'+
    '</div>'+
    '</div>'+
    '<div class="col-sm-6 expiry">'+
    '<div class="shift-label-input month validated-input" '+
    ':class="{'+
        "'is-filled': ccExpMonth,"+
        "'is-error': cardError.param === 'exp_month'"+
    '}">'+
    '<input data-stripe="exp_month" type="text" required size="2" v-model="ccExpMonth">'+
    '<label placeholder="MM"></label>'+
    '</div>'+
    '<span class="separator">/</span>'+
    '<div class="shift-label-input year validated-input" '+
    ':class="{'+
        "'is-filled': ccExpYear,"+
        "'is-error': cardError.param === 'exp_year'"+
    '}">'+
    '<input data-stripe="exp_year" type="text" required size="4" v-model="ccExpYear">'+
    '<label placeholder="YYYY"></label>'+
    '</div>'+
    '</div>'+
    '</div>'+
    '<div class="shift-label-input validated-input" '+
    ':class="{'+
        "'is-filled': ccCVC,"+
        "'is-error': cardError.param === 'cvc'"+
    '}">'+
    '<input data-stripe="cvc" type="text" required size="4" v-model="ccCVC">'+
    '<label placeholder="CVC"></label>'+
    '</div>'+
    '<div class="billing-buttons align-end">'+
    '<button type="submit" class="btn btn-solid-green" :disabled="! validCardDetails">{{ submitButtonText }}</button>'+
    '</div>'+
    '</form>',
    data: function() {
        return {
            cardError: '',
            waitingStripeResponse: false,
            ccName: '',
            ccNumber: '',
            ccExpMonth: '',
            ccExpYear: '',
            ccCVC: ''
        };
    },
    props: [],
    computed: {
        validCardDetails: function() {
            return ! this.waitingStripeResponse && this.ccName && this.ccNumber && this.ccExpMonth && this.ccExpYear && this.ccCVC;
        },
        submitButtonText: function() {
            if(this.waitingStripeResponse) return 'processing...';
            return 'Add card';
        }
    },
    methods: {
        submitCard: function() {
            var $form = $(this.$els.stripeForm);

            this.cardError = '';
            this.waitingStripeResponse = true;

            Stripe.card.createToken($form, function(status, response) {

                if(response.error) { // Card error
                    this.cardError = response.error;
                    this.waitingStripeResponse = false;
                        
                } else {
                    vueEventBus.$emit('new-cc-token', response.id);
                }
            }.bind(this));
        }
    },
    events: {

    },
    ready: function() {
        // Set stripe public key
        Stripe.setPublishableKey($('meta[name="stripe-key"]').attr('content'));
    }
});
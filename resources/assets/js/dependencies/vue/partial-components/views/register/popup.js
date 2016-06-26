Vue.component('registration-popup', {
    name: 'registration-popup',
    el: function () {
        return '#registration-popup'
    },
    data: function () {
        return {
            ajaxReady: true,
            showRegisterPopup: false,
            section: 'account',
            companyName: '',
            validCompanyName: 'unfilled',
            companyNameError: '',
            email: '',
            validEmail: 'unfilled',
            emailError: '',
            password: '',
            validPassword: 'unfilled',
            name: '',
            validName: 'unfilled',
            cardError: '',
            submitting: false,
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
        registerButtonText: function() {
            if(this.waitingStripeResponse) return 'hold on...';
            return 'Register company';
        }
    },
    methods: {
        toggleShowRegistrationPopup: function () {
            this.showRegisterPopup = !this.showRegisterPopup;
        },
        checkCompanyName: function () {
            var self = this;
            self.validCompanyName = 'unfilled';
            if (self.companyName.length > 0) {
                // No symbols in name
                if (!alphaNumeric(self.companyName)) {
                    self.validCompanyName = false;
                    self.companyNameError = 'Company name cannot contain symbols';
                    return;
                }
                self.validCompanyName = 'loading';
                if (!self.ajaxReady) return;
                self.ajaxReady = false;
                $.ajax({
                    url: '/api/company/profile/' + encodeURI(self.companyName),
                    method: '',
                    success: function (data) {
                        // success
                        if (!_.isEmpty(data)) {
                            self.validCompanyName = false;
                            self.companyNameError = 'That Company name is already taken'
                        } else {
                            self.validCompanyName = true;
                            self.companyNameError = '';
                        }
                        self.ajaxReady = true;
                    },
                    error: function (response) {
                        console.log(response);
                        self.ajaxReady = true;
                    }
                });
            }
        },
        checkEmail: function () {
            var self = this;
            self.validEmail = 'unfilled';
            if (self.email.length > 0) {
                if (validateEmail(self.email)) {
                    self.validEmail = 'loading';
                    if (!self.ajaxReady) return;
                    self.ajaxReady = false;
                    $.ajax({
                        url: '/user/email/' + self.email + '/check',
                        method: 'GET',
                        success: function (data) {
                            // success
                            if (data) {
                                self.validEmail = true;
                                self.emailError = '';
                            }
                            self.ajaxReady = true;
                        },
                        error: function (response) {
                            console.log(response);
                            self.ajaxReady = true;
                            self.validEmail = false;
                            self.emailError = 'Account already exists for that email';
                        }
                    });
                } else {
                    self.validEmail = false;
                    self.emailError = 'Invalid email format - you@example.com';
                }
            }
        },
        checkPassword: function () {
            this.validPassword = 'unfilled';
            if (this.password.length > 0) {
                this.validPassword = (this.password.length >= 6);
            }
        },
        checkName: function () {
            this.validName = this.name.length > 0 ? true : 'unfilled';
        },
        goToSection: function(section) {
            this.section = section;
        },
        submitBilling: function() {

            var $form = $(this.$els.stripeForm);

            this.cardError = '';
            this.waitingStripeResponse = true;

            Stripe.card.createToken($form, function(status, response) {

                if(response.error) { // Card error
                    this.cardError = response.error;
                    this.waitingStripeResponse = false;

                } else {
                    this.registerNewCompany(response.id);
                }
            }.bind(this));
        },
        registerNewCompany: function (creditCardToken) {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/company',
                method: 'POST',
                data: {
                    company_name: self.companyName,
                    name: self.name,
                    email: self.email,
                    password: self.password,
                    credit_card_token: creditCardToken
                },
                success: function (data) {
                    // success
                    location.href = "/";
                    self.ajaxReady = true;
                },
                error: function (response) {
                    flashNotify('Registration error - please contact support');
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {},
    ready: function () {
        this.$watch('showRegisterPopup', function (val) {
            if (val) $('#register-popup-icon').playLiviconEvo();
        });
    }
});
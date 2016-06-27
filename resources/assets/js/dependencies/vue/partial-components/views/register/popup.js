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
            validName: 'unfilled'
        };
    },
    props: [],
    computed: {},
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
    ready: function () {

        var self = this;

        vueEventBus.$on('new-cc-token', function (creditCardToken) {
            self.registerNewCompany(creditCardToken);
        });

        this.$watch('showRegisterPopup', function (val) {
            if (val) $('#register-popup-icon').playLiviconEvo();
        });
    }
});
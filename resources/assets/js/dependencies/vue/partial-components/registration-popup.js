Vue.component('registration-popup', {
    name: 'registration-popup',
    el: function () {
        return '#registration-popup'
    },
    data: function () {
        return {
            showRegisterPopup: false,
            email: '',
            password: '',
            companyName: '',
            validCompanyName: 'unfilled',
            validEmail: 'unfilled',
            validPassword: 'unfilled',
            ajaxReady: true
        };
    },
    props: [],
    computed: {},
    methods: {
        toggleShowRegistrationPopup: function () {
            this.showRegisterPopup = !this.showRegisterPopup;
        },
        checkCompanyName: function() {
            var self = this;
            self.validCompanyName = 'unfilled';
            if(self.companyName.length > 0) {
                // No symbols in name
                if(! alphaNumeric(self.companyName)) {
                    self.validCompanyName = false;
                    return;
                }
                self.validCompanyName = 'loading';
                if(!self.ajaxReady) return;
                self.ajaxReady = false;
                $.ajax({
                    url: '/api/company/profile/' + encodeURI(self.companyName),
                    method: '',
                    success: function(data) {
                       // success
                        self.validCompanyName = _.isEmpty(data);
                       self.ajaxReady = true;
                    },
                    error: function(response) {
                        console.log(response);

                        vueValidation(response, self);
                        self.ajaxReady = true;
                    }
                });
            }
        },
        checkEmail: function() {
            this.validEmail = 'unfilled';
            if(this.email.length > 0) {
                this.validEmail =  validateEmail(this.email);
            }
        },
        checkPassword: function() {
            this.validPassword = 'unfilled';
            if(this.password.length > 0) {
                this.validPassword = (this.password.length >= 6);
            }
        }
    },
    events: {},
    ready: function () {
    }
});
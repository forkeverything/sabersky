Vue.component('registration-popup', {
    name: 'registration-popup',
    el: function() {
        return '#registration-popup'
    },
    data: function () {
        return {
            showRegisterPopup: false,
            email: '',
            password: '',
            companyName: ''
        };
    },
    props: [],
    computed: {},
    methods: {
        toggleShowRegistrationPopup: function() {
            this.showRegisterPopup = !this.showRegisterPopup;
        }
    },
    events: {},
    ready: function () {

    }
});
Vue.component('form-errors', {
    template: '<div class="validation-errors" v-show="errors.length > 0">' +
    '<h5 class="errors-heading">{{ errorHeading }}</h5>' +
    '<ul class="errors-list list-unstyled"' +
    'v-show="errors.length > 0"' +
    '>' +
    '<li v-for="error in errors">{{ error }}</li>' +
    '</ul>' +
    '</div>',
    data: function () {
        return {
            errors: []
        }
    },
    computed: {
        errorHeading: function() {
            if(this.errors.length > 1) {
                return 'There were '  + errors.length + ' errors'
            } else if (this.errors.length == 1){
                return 'There was 1 error'
            }
        }
    },
    events: {
        'new-errors': function(errors) {
            var self = this;
            var newErrors = [];
            _.forEach(errors, function (error) {
                newErrors.push(error);
            });
            self.errors = newErrors;
        },
        'clear-errors': function() {
            this.errors = [];
        }
    }
});
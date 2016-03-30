Vue.component('form-errors', {
    data: function () {
        return {
            errors: []
        }
    },
    template: '<ul ' +
    'class="alert alert-danger list-unstyled"' +
    'v-show="errors.length > 0"' +
    '>' +
    '<li v-for="error in errors">{{ error }}</li>' +
    '</ul>',
    events: {
        'new-errors': function(errors) {
            var self = this;
            var newErrors = [];
            _.forEach(errors, function (error) {
                newErrors.push(error);
            });
            self.errors = newErrors;
            setTimeout(function () {
                self.errors = [];
            }, 3500);
        }
    }
});
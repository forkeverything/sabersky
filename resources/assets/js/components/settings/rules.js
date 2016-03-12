var rulesComponent = Vue.extend({
    template: '#component-rules',
    data: function () {
        return {
            properties: [],
            triggers: []
        }
    },
    props: ['ajaxReady', 'modalTitle', 'modalBody', 'modalMode', 'modalFunction'],
    methods: {},
    ready: function() {
        var self = this;
        $.ajax({
            url: '/api/rules/properties_triggers',
            method: 'GET',
            success: function(data) {
               // success
                self.properties = data.properties;
                self.triggers = data.triggers;
            },
            error: function(response) {
                console.log('Request Error!');
                console.log(response);
            }
        });
    },
});

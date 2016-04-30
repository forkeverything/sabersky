Vue.component('toast-alert', {
    name: 'toaster',
    template: '<div id="toast-plate">' +
    '               <div class="toast animated"' +
    '                    v-for="(index, alert) in alerts"' +
    '                    transition="fade"' +
    '                    :class="alert.type">' +
    '<button type="button" class="btn-close" @click="dismiss(alert) "><i class="fa fa-close"></i></button>' +
    '{{{ alert.content }}}' +
    '</div>' +
    '</div>',
    data: function() {
        return {
            alerts: []
        };
    },
    methods: {
        addToQueue: function(alert) {
            // Attach a timeout ID and use it as unique id
            alert.timerID = setTimeout(function () {
                // dismiss (hide) the alert after 3 secs...
                this.dismiss(alert);
            }.bind(this), 3000);
            // finally push alert
            this.alerts.push(alert);
        },
        dismiss: function(alert) {
            // if we prematurely cleared it.. clear the timeout
            clearTimeout(alert.timerID);
            // Remove it from array (will work because of unique timerID)
            this.alerts = _.reject(this.alerts, alert);
        }
    },
    events: {
        'serve-toast': function(alert) {
            this.addToQueue(alert);
        }
    },
    ready: function() {
        /*
        TODO ::: Implement this component to handle alerts if/when we
        make the jump to Vue for handling all client-side. Which
        includes routing, auth etc.
         */
    }
});
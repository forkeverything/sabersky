Vue.component('pr-single-cancel', {
    name: 'cancelPR',
    template: '<div class="state-control">' +
    '<div class="cancel-pr" v-if="purchaseRequest.state === ' + "'open'" + '">' +
    '<button type="button" class="btn btn-small btn-outline-red btn-show-confirm-cancel" @click="toggleConfirm" v-show="! showConfirm">Cancel</button>' +
    '<div class="confirm-cancel" v-show="showConfirm">' +
    '<p>Cancelling this request will only apply to outstanding quantities only. Fulfilled amounts cannot be cancelled.</p>' +
    '<button type="button" class="btn btn-outline-grey btn-return" @click="toggleConfirm">Return</button>' +
    '<button type="button" class="btn btn-solid-red btn-cancel" @click="sendRequest(' + "'cancel'" + ')">Yes, cancel with {{ purchaseRequest.quantity }} quantities outstanding</button>' +
    '</div>' +
    '</div>' +
    '<div class="uncancel-pr"  v-if="purchaseRequest.state === ' + "'cancelled'" + '">' +
    '<button type="button" class="btn btn-solid-blue" @click="sendRequest(' + "'reopen'" + ')">Reopen Request</button>' +
    '</div>'+
    '</div>',
    data: function () {
        return {
            ajaxReady: true,
            showConfirm: false
        };
    },
    props: ['purchase-request'],
    computed: {},
    methods: {
        toggleConfirm: function() {
            this.showConfirm = !this.showConfirm;
        },
        sendRequest: function(action) {

            var method = 'DELETE';
            var url = '/purchase_requests/' + this.purchaseRequest.id;

            if(action === 'reopen') {
                method = 'GET';
                url += '/reopen';
            }

            var self = this;
            if(!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: url,
                method: method,
                success: function(data) {
                    location.reload();
                },
                error: function(response) {
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {},
    ready: function () {

    }
});
Vue.component('po-mark-received-popover', {
    name: 'purchaseOrderMarkReceivedPopover',
    template: '<div class="popover-container">' +
    '<button type="button" class="btn-popup btn-mark-received btn btn-small btn-solid-green" :disabled="! (purchaseOrder.status === ' + "'approved'" + ')" @click="togglePopover">Received</button>'+
    '<div class="popover-content popover right" v-show="showPopover">' +
    '<div class="arrow"></div>'+
    '<button type="button" class="btn btn-accept btn-outline-green btn-small" @click="markReceived(lineItem, ' + "'accepted'" + ')">Accept</button>'+
    '<button type="button" class="btn btn-return btn-outline-red btn-small"  @click="markReceived(lineItem, ' + "'returned'" + ')">Return</button>'+
    '</div>' +
    '</div>',
    data: function() {
        return {
            showPopover: false
        };
    },
    props: ['purchase-order', 'line-item'],
    computed: {
        
    },
    methods: {
        togglePopover: function() {
            this.showPopover = !this.showPopover;
        },
        markReceived: function(lineItem, status) {
            if(status !== 'accepted' && status !== 'returned') return;
            $.get('/purchase_orders/' + this.purchaseOrder.id + '/line_item/' + lineItem.id + '/received/' + status, function(data) {
                lineItem.status = data;
            });
        }
    },
    events: {
        
    },
    ready: function() {
        var self = this;
        $(document).on('click.hidePopovers', function (event) {
            if (!$(event.target).closest('.btn-popup').length && !$(event.target).closest('.popover-container').length && !$(event.target).is('.popover-container')) {
                self.showPopover = false;
            }
        })
    }
});
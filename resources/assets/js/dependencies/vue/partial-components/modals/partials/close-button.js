Vue.component('modal-close-button', {
    name: 'modalClose',
    template: '<button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>',
    methods: {
        hideModal: function() {
            vueEventBus.$emit('modal-close');
        }
    }
});
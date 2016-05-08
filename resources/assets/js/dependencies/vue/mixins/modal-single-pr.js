var modalSinglePR = {
    created: function () {
    },
    methods: {
        showSinglePR: function (purchaseRequest) {
            vueEventBus.$emit('modal-single-pr-show', purchaseRequest);
        }
    }
};
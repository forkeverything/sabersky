Vue.component('modal-select-bank-account', {
    name: 'modalSelectBankAccount',
    template: '<button type="button" v-show="! selected" class="btn btn-small button-select-account btn-outline-blue" @click="showModal">Select Bank Account</button>' +
    '<div class="modal-select-account modal-overlay" v-show="visible" @click="hideModal">' +
    '<div class="modal-body" @click.stop="">' +
    '<button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>' +
    '<h2>Select a Bank Account</h2>' +
    '<ul class="list-unstyled list-accounts" v-if="accounts.length > 0">' +
    '<li class="single-account clickable" v-for="account in accounts" @click="select(account)">' +
    '<bank-account :account="account"></bank-account>' +
    '</li>' +
    '</ul>' +
    '<em v-else>No Bank Accounts found. Add one to Vendor before selecting it here.</em>' +
    '</div>' +
    '</div>' +
    '<div class="single-account clickable selected" v-show="selected">' +
    '<div class="change-overlay" @click="remove">' +
    '<i class="fa fa-close"></i>' +
    '<h3>Remove</h3>' +
    '</div>' +
    '<bank-account :account="selected"></bank-account>' +
    '</div>',
    data: function () {
        return {
            visible: false
        };
    },
    props: ['selected', 'accounts'],
    methods: {
        showModal: function () {
            this.visible = true;
        },
        hideModal: function () {
            this.visible = false;
        },
        select: function (account) {
            this.selected = account;
            this.hideModal();
        },
        remove: function () {
            this.selected = '';
        }
    },
    events: {},
    ready: function () {

    }
});
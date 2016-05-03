Vue.component('modal-select-bank-account', {
    name: 'modalSelectBankAccount',
    template: '<button type="button" v-show="! selected" class="btn btn-small button-select-account btn-outline-blue" @click="showModal">Select Bank Account</button>' +
    '<div class="modal-select-account modal-overlay" v-show="visible" @click="hideModal">' +
    '<div class="modal-body" @click.stop="">' +
    '<button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>' +
    '<h3>Select a Bank Account</h3>' +
    '<ul class="list-unstyled list-accounts" v-if="accounts.length > 0">' +
    '<li class="single-account clickable" v-for="account in accounts" @click="select(account)">' +
    '<span class="account-name">{{ account.account_name }}</span>' +
    '<span class="account-number">{{ account.account_number }}</span>' +
    '<span class="bank-name">{{ account.bank_name }}</span>' +
    '<span class="bank-phone"><abbr title="Phone">P:</abbr> {{ account.bank_phone }}</span>' +
    '<span class="bank-address" v-if="account.bank_address">{{ account.bank_address }}</span>' +
    '<span class="swift" v-if="account.swift">SWIFT / IBAN: {{ account.swift }}</span>' +
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
    '<span class="account-name">{{ selected.account_name }}</span>' +
    '<span class="account-number">{{ selected.account_number }}</span>' +
    '<span class="bank-name">{{ selected.bank_name }}</span>' +
    '<span class="bank-phone"><abbr title="Phone">P:</abbr> {{ selected.bank_phone }}</span>' +
    '<span class="bank-address" v-if="selected.bank_address">{{ selected.bank_address }}</span>' +
    '<span class="swift" v-if="selected.swift">SWIFT / IBAN: {{ selected.swift }}</span>' +
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
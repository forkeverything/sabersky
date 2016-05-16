Vue.component('bank-account', {
    name: 'singleBankAccount',
    template: '<div class="bank_account" v-if="bankAccount">' +
    '<div class="info bank_name">' +
    '<label>Bank</label>' +
    '<span>{{ bankAccount.bank_name }}</span>' +
    '</div>' +
    '<div class="name-number">' +
    '<div class="account_name info">' +
    '<label>Account Name</label>' +
    '<span>{{ bankAccount.account_name }}</span>' +
    '</div>' +
    '<div class="number info">' +
    '<label>Account Number</label>' +
    '<span class="account_number">{{ bankAccount.account_number }}</span>' +
    '</div>' +
    '</div>' +
    '<div class="phone_swift">' +
    '<div class="bank_phone info">' +
    '<label>Phone</label>' +
    '<span v-if="bankAccount.bank_phone">{{ bankAccount.bank_phone }}</span><span v-else>-</span>' +
    '</div>' +
    '<div class="info swift">' +
    '<label>SWIFT / IBAN</label>' +
    '<span>' +
    '<span v-if="bankAccount.swift">{{ bankAccount.swift }}</span><span v-else>-</span>' +
    '</span>' +
    '</div>' +
    '</div>' +
    '<div class="info bank_address">' +
    '<label>Address</label>' +
    '<span>' +
    '<span v-if="bankAccount.bank_address">{{ bankAccount.bank_address }}</span><span v-else>-</span>' +
    '</span>' +
    '</div>' +
    '</div>',
    props: ['bank-account']
});
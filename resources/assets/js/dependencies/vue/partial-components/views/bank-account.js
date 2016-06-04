Vue.component('bank-account', {
    name: 'singleBankAccount',
    template: '<div class="bank-account card">' +
    '<p class="card-title">{{ account.bank_name }}</p>' +
    '<hr>' +
    '<h3>Account</h3>' +
    '<div class="account-name text-center">' +
    '{{ account.account_name }}' +
    '</div>' +
    '<div class="account-number text-center">' +
    '{{ account.account_number }}' +
    '</div>' +
    '<hr>' +
    '<h3>Bank</h3>' +
    '<div class="extra-info text-center">' +
    '<div class="bank-name"><strong>{{ account.bank_name }}</strong></div>' +
    '<div class="bank-phone">' +
    '<span class="bank-label">Phone Number: </span>' +
    '<span v-if="account.bank_phone">{{ account.bank_phone }}</span>' +
    '<span v-else>-</span>' +
    '</div>' +
    '<div class="bank-address">' +
    '<span class="bank-label">Branch Address: </span>' +
    '<span v-if="account.bank_address">{{ account.bank_address }}</span>' +
    '<span v-else>-</span>' +
    '</div>' +
    '<div class="swift">' +
    '<span class="bank-label">SWIFT / IBAN: </span>' +
    '<span v-if="account.swift swift">{{ account.swift }}</span>' +
    '<span v-else>-</span>' +
    '</div>' +
    '</div>' +
    '</div>',
    props: ['account']
});
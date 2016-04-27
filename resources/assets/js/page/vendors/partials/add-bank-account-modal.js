Vue.component('add-bank-account-modal', {
    name: 'add-bank-account-modal',
    template: '<button type="button"' +
    '               class="btn btn-add-modal btn-outline-blue"' +
    '               @click="showModal"' +
    '          >' +
    '           New Account' +
    '</button>' +
    '          <div class="modal-bank-account-add modal-form" v-show="visible" @click="hideModal">' +
    '               <form class="form-add-bank-account main-form" @click.stop="" @submit.prevent="addBankAccount">' +
    '                   <form-errors></form-errors>' +
    '                   <h4>Add New Bank Account</h4>'+
    '                   <div class="account_info">'+
    '                       <label>Account Information</label>'+
    '                       <div class="row">'+
    '                           <div class="col-xs-6">'+
    '                               <div class="shift-label-input no-validate">'+
    '                                   <input type="text" v-model="accountName" required>'+
    '                                   <label placeholder="Account Name" class="required"></label>'+
    '                               </div>'+
    '                           </div>'+
    '                           <div class="col-xs-6">'+
    '                               <div class="shift-label-input no-validate">'+
    '                                   <input type="text" v-model="accountNumber" required>'+
    '                                   <label placeholder="# Number" class="required"></label>'+
    '                               </div>'+
    '                           </div>'+
    '                       </div>'+
    '                   </div>'+
    '                   <div class="bank_info">'+
    '                       <label>Bank Details</label>'+
    '                       <div class="visible-xs">'+
    '                           <div class="shift-label-input no-validate">'+
    '                               <input type="text" v-model="bankName" required>'+
    '                               <label placeholder="Bank Name" class="required"></label>'+
    '                           </div>'+
    '                       </div>'+
    '                       <div class="row hidden-xs">'+
    '                           <div class="col-sm-4">'+
    '                               <div class="shift-label-input no-validate">'+
    '                                   <input type="text" v-model="bankName" required>'+
    '                                   <label placeholder="Bank Name" class="required"></label>'+
    '                               </div>'+
    '                           </div>'+
    '                           <div class="col-sm-4">'+
    '                               <div class="shift-label-input no-validate">'+
    '                                   <input type="text" ' +
    '                                       class="not-required"'+
    '                                       v-model="swift" ' +
    '                                       :class="{'+
    "                                           'filled': swift.length > 0"+
    '                                       }">'+
    '                                   <label placeholder="SWIFT / IBAN"></label>'+
    '                               </div>'+
    '                           </div>'+
    '                           <div class="col-sm-4">'+
    '                               <div class="shift-label-input no-validate">'+
    '                                   <input type="text" ' +
    '                                       class="not-required" ' +
    '                                       v-model="bankPhone" ' +
    '                                       :class="{'+
    "                                           'filled': bankPhone.length > 0"+
    '                                       }">'+
    '                                   <label placeholder="Phone Number"></label>'+
    '                               </div>'+
    '                           </div>'+
    '                  </div>'+
    '                  <div class="row visible-xs">'+
    '                       <div class="col-xs-6">'+
    '                           <div class="shift-label-input no-validate">'+
    '                               <input type="text" ' +
    '                                      class="not-required"'+
    '                                      v-model="swift" ' +
    '                                      :class="{' +
    "                                           'filled': swift.length > 0"+
    '                                       }">'+
    '                               <label placeholder="SWIFT / IBAN"></label>'+
    '                           </div>'+
    '                      </div>'+
    '                      <div class="col-xs-6">'+
    '                           <div class="shift-label-input no-validate">'+
    '                               <input type="text" ' +
    '                                      class="not-required" ' +
    '                                      v-model="bankPhone" ' +
    '                                      :class="{' +
    "                                           'filled': bankPhone.length > 0 "+
    '                                       }">'+
    '                                       <label placeholder="Phone Number"></label>' +
    '                           </div>'+
    '                       </div>'+
    '                </div>'+
    '                <div class="shift-label-input no-validate">'+
    '                       <input type="text" ' +
    '                              class="not-required" ' +
    '                              v-model="bankAddress" ' +
    '                               :class="{'+
    "                                   'filled': bankAddress.length > 0" +
    '                               }">'+
    '                       <label placeholder="Address"></label>'+
    '               </div>'+
    '           </div>'+
    '           <div class="align-end">'+
    '               <button type="submit" class="btn btn-solid-blue"><i class="fa fa-plus"></i> Bank Account</button>'+
    '           </div>'+
    '       </form>' +
    ' </div>',
    data: function() {
        return {
            ajaxReady: true,
            ajaxObject: {},
            visible: false,
            accountName: '',
            accountNumber: '',
            bankName: '',
            swift: '',
            bankPhone: '',
            bankAddress: ''
        };
    },
    props: ['vendor'],
    computed: {
        
    },
    methods: {
        showModal: function () {
            this.visible = true;
        },
        hideModal: function () {
            this.visible = false;
        },
        addBankAccount: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/vendors/' + self.vendor.id + '/bank_accounts',
                method: 'POST',
                data: {
                    "account_name": self.accountName,
                    "account_number": self.accountNumber,
                    "bank_name": self.bankName,
                    "swift": self.swift,
                    "bank_phone": self.bankPhone,
                    "bank_address": self.bankAddress
                },
                success: function (data) {
                    // Push to front
                    self.vendor.bank_accounts.push(data);
                    // Reset Fields
                    self.accountName = '';
                    self.accountNumber = '';
                    self.bankName = '';
                    self.swift = '';
                    self.bankPhone = '';
                    self.bankAddres = '';
                    // Flash
                    flashNotify('success', 'Added bank account to vendor');
                    self.visible = false;
                    self.ajaxReady = true;
                },
                error: function (response) {
                    flashNotify('error', 'Could not add bank account to vendor')
                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        },
    },
    events: {
        
    },
    ready: function() {
        
    }
});
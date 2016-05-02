Vue.component('add-address-modal', {
    name: 'addAddressModal',
    template: '<button type="button"' +
    '                  class="btn btn-add-address btn-outline-green"' +
    '                  @click="showModal"' +
    '                  >' +
    '                  <i class="fa fa-plus"></i> New Address' +
    '          </button>' +
    '          <div class="modal-overlay modal-address-add modal-form" v-show="visible" @click="hideModal">' +
    '               <form class="modal-body form-address-add main-form" v-show="loaded" @click.stop="" @submit.prevent="addAddress">' +
    '                   <button type="button" @click="hideModal" class="btn button-hide-modal"><i class="fa fa-close"></i></button>' +
    '                   <form-errors></form-errors>' +
    '                   <h3>Add Address</h3>' +
    '                   <div class="row">' +
    '                       <div class="col-sm-6">' +
    '                           <div class="shift-label-input">' +
    '                               <input type="text" ' +
    '                                      class="not-required" ' +
    '                                      v-model="contactPerson" ' +
    '                                      :class="{' +
    "                                           'filled': contactPerson }" +
    '                               ">' +
    '                               <label placeholder="Contact Person"></label>' +
    '                           </div>' +
    '                       </div>' +
    '                       <div class="col-sm-6">' +
    '                           <div class="shift-label-input">' +
    '                               <input type="text" required v-model="phone">' +
    '                               <label placeholder="Phone" class="required"></label>' +
    '                           </div>' +
    '                       </div>' +
    '                   </div>' +
    '                   <div class="shift-label-input no-validate">' +
    '                       <input type="text" v-model="address1" required>' +
    '                       <label class="required" placeholder="Address"></label>' +
    '                   </div>' +
    '                   <div class="shift-label-input no-validate">' +
    '                       <input class="not-required"' +
    '                              type="text"' +
    '                              v-model="address2"' +
    '                              :class="{' +
    "                                  'filled': address2.length > 0" +
    '                              }"' +
    '                       >' +
    '                       <label placeholder="Address 2"></label>' +
    '                   </div>' +
    '                   <div class="row">' +
    '                       <div class="col-sm-6">' +
    '                           <div class="shift-label-input no-validate">' +
    '                               <input type="text" v-model="city" required>' +
    '                               <label class="required" placeholder="City"></label>' +
    '                           </div>' +
    '                       </div>' +
    '                       <div class="col-sm-6">' +
    '                           <div class="shift-label-input no-validate">' +
    '                               <input type="text" v-model="zip" required>' +
    '                               <label class="required" placeholder="Zip"></label>' +
    '                           </div>' +
    '                       </div>' +
    '                   </div>' +
    '                   <div class="row">' +
    '                       <div class="col-sm-6">' +
    '                           <div class="form-group shift-select">' +
    '                               <label class="required">Country</label>' +
    '                               <country-selecter :name.sync="countryID"></country-selecter>' +
    '                           </div>' +
    '                       </div>' +
    '                       <div class="col-sm-6">' +
    '                           <div class="form-group shift-select">' +
    '                               <label class="required">State</label>' +
    '                               <state-selecter :name.sync="state""></state-selecter>'+
    '                           </div>' +
    '                       </div>' +
    '                   </div>' +
    '                   <div class="form-group align-end">' +
    '                       <button type="submit" class="btn btn-solid-green" :disabled="! canSaveAddress">Save Address</button>' +
    '                   </div>' +
    '               </form>' +
    '          </div>',
    data: function () {
        return {
            ajaxReady: true,
            ajaxObject: {},
            visible: false,
            loaded: false,
            contactPerson: '',
            phone: '',
            address1: '',
            address2: '',
            city: '',
            zip: '',
            countryID: '',
            state: ''
        };
    },
    props: ['owner-id', 'owner-type'],
    computed: {
        canSaveAddress: function () {
            return this.address1.length > 0 && this.city.length > 0 && this.countryID.length > 0 && this.zip.length > 0 && this.phone.length > 0;
        }
    },
    methods: {
        showModal: function () {
            this.visible = true;
        },
        hideModal: function () {
            this.visible = false;
        },
        addAddress: function () {
            var self = this;
            vueClearValidationErrors(self);
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/address',
                method: 'POST',
                data: {
                    "owner_id": self.ownerId,
                    "owner_type": self.ownerType,
                    "contact_person": self.contactPerson,
                    "phone": self.phone,
                    "address_1": self.address1,
                    "address_2": self.address2,
                    "city": self.city,
                    "zip": self.zip,
                    "country_id": self.countryID,
                    "state": self.state
                },
                success: function (data) {
                    // success
                    self.visible = false;
                    flashNotify('success', 'Added a new address');
                    self.$dispatch('address-added', data);
                    self.ajaxReady = true;

                    // reset fields
                    self.contactPerson = '';
                    self.phone = '';
                    self.address1 = '';
                    self.address2 = '';
                    self.city = '';
                    self.zip = '';
                    self.countryID = '';
                    self.state = '';

                },
                error: function (response) {
                    console.log(response);

                    vueValidation(response, self);
                    self.ajaxReady = true;
                }
            });
        }
    },
    events: {},
    ready: function () {
        var self = this;
        self.loaded = true;
    }
});
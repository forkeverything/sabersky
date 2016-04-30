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
    '                               <select class="address-country-selecter"><option></option></select>' +
    '                           </div>' +
    '                       </div>' +
    '                       <div class="col-sm-6">' +
    '                           <div class="form-group shift-select">' +
    '                               <label class="required">State</label>' +
    '                               <select class="address-state-selecter"><option></option></select>' +
    '                           </div>' +
    '                       </div>' +
    '                   </div>' +
    '                   <div class="shift-label-input">' +
    '                       <input type="text" v-model="phone" required>' +
    '                       <label class="required" placeholder="Phone Number"></label>' +
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
            address1: '',
            address2: '',
            city: '',
            state: '',
            countryID: '',
            zip: '',
            phone: ''
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
                    "address_1": self.address1,
                    "address_2": self.address2,
                    "city": self.city,
                    "state": self.state,
                    "country_id": self.countryID,
                    "zip": self.zip,
                    "phone": self.phone
                },
                success: function (data) {
                    // success
                    self.visible = false;
                    flashNotify('success', 'Added a new address');
                    self.$dispatch('address-added', data);
                    self.ajaxReady = true;

                    // reset fields
                    self.address1 = '';
                    self.address2 = '';
                    self.city = '';
                    self.state = '';
                    self.countryID = '';
                    self.zip = '';
                    self.phone = '';
                    
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
        var $select_country, select_country;
        var $select_state, select_state;

        // Init Country Selecter
        $select_country = $('.address-country-selecter').selectize({
            valueField: 'id',
            searchField: 'name',
            create: false,
            placeholder: 'Type to select a Country',
            render: {
                option: function (item, escape) {
                    return '<div class="single-country-option">' + escape(item.name) + '</div>'
                },
                item: function (item, escape) {
                    return '<div class="selected-country">' + escape(item.name) + '</div>'
                }
            },
            load: function (query, callback) {
                if (!query.length) return callback();
                $.ajax({
                    url: '/countries/search/' + encodeURIComponent(query),
                    type: 'GET',
                    error: function () {
                        callback();
                    },
                    success: function (res) {
                        callback(res);
                    }
                });
            },
            onChange: function (value) {
                if (!value.length) return;

                self.countryID = value;

                select_state.disable();
                select_state.clearOptions();
                select_state.load(function (callback) {
                    if (!_.isEmpty(self.ajaxObject) && self.ajaxObject.readyState != 4) self.ajaxObject.abort();
                    self.ajaxObject = $.ajax({
                        url: '/countries/' + value + '/states',
                        success: function (results) {
                            select_state.enable();
                            callback(results);
                        },
                        error: function () {
                            callback();
                        }
                    })
                });
            }
        });

        $select_state = $('.address-state-selecter').selectize({
            valueField: 'name',
            labelField: 'name',
            searchField: ['name'],
            placeholder: 'Select or add a state',
            create: true,
            onChange: function (value) {
                self.state = value;
            }
        });

        select_country = $select_country[0].selectize;
        select_state = $select_state[0].selectize;
        select_state.disable();

        self.loaded = true;
    }
});
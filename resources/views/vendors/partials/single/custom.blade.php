<vendor-custom inline-template>
    <div class="container" id="vendor-single-custom">
        <input type="hidden" value="{{ $vendor->id }}" v-model="vendorID">
        <div class="page-body">
            <form-errors></form-errors>
            <section class="description">
                @can('edit', $vendor)
                <h5 class="loading-header"
                    :class="{
                        'loading': savedDescription === 'saving',
                        'success': savedDescription === 'success',
                        'error': savedDescription === 'error'
                    }"
                >Description</h5>
                <div class="form-group">
                    <p v-if="description.length > 0" @click="startEditDescription" v-show="! editDescription"
                    >@{{ vendor.description }}</p>
                    <span v-else class="no-description" @click="startEditDescription" v-show="! editDescription">None -
                    click to write a description</span>
                    <textarea class="autosize description-editor form-control live-editor" v-model="description"
                              v-show="editDescription" @blur="saveDescription">@{{ vendor.description }}</textarea>
                </div>
                @else
                    <h5>Description</h5>
                    <p v-if="vendor.description.length > 0">
                        @{{ vendor.description }}
                    </p>
                    <span v-else class="no-description">None</span>
                    @endcan
            </section>
            <section class="addresses">
                <h5>Addresses</h5>
                @can('edit', $vendor)
                <add-address-modal :owner-id="{{ $vendor->id }}" :owner-type="'vendor'"></add-address-modal>
                @endcan


                <div class="addresses-collection" v-if="vendor.addresses && vendor.addresses.length > 0">
                    <div class="address-row">
                        <div class="single-address"
                             v-for="address in vendor.addresses"
                             :class="{
                                    'primary': address.primary
                                 }"
                        >
                            @can('edit', $vendor)
                            <div class="controls">
                                <a class="set-primary dotted clickable" v-if="! address.primary" @click="
                                setPrimary(address)">Set as primary</a>
                                <span v-else class="label-primary"><i class="fa fa-check"></i>Primary</span>
                                <a class="remove clickable" @click="removeAddress(address)"><i
                                        class="fa fa-close"></i></a>
                            </div>
                            @endcan
                            <div class="phone">
                                <label>Phone</label>
                                <span class="phone">@{{ address.phone }}</span>
                            </div>
                            <div class="address">
                                <label>Address</label>
                                <span class="address_1 block">@{{ address.address_1 }}</span>
                                <span class="address_2 block" v-if="address.address_2">@{{ address.address_2 }}</span>
                                <span class="city">@{{ address.city }}</span>,
                                <span class="state">@{{ address.state }}</span>
                                <span class="country block">@{{ address.country }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <em v-else>None</em>
            </section>
            <section class="bank_accounts">
                <h5>Bank Accounts</h5>

                <form @submit.prevent="addBankAccount">
                    <div class="account_info">
                        <label>Account Information</label>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text" v-model="bankAccountName" required>
                                    <label placeholder="Account Name" class="required"></label>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="shift-label-input">
                                    <input type="text" v-model="bankAccountNumber" required>
                                    <label placeholder="# Number" class="required"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bank_info">
                        <label>Bank Details</label>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="shift-label-input">
                                    <input type="text" v-model="bankName" required>
                                    <label placeholder="Bank Name" class="required"></label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="shift-label-input">
                                    <input type="text" class="not-required"
                                           v-model="swift" :class="{
                                    'filled': swift.length > 0
                                }">
                                    <label placeholder="SWIFT / IBAN"></label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="shift-label-input">
                                    <input type="text" class="not-required" v-model="bankPhone">
                                    <label placeholder="Phone Number"></label>
                                </div>
                            </div>
                        </div>
                        <div class="shift-label-input">
                            <input type="text" class="not-required" v-model="bankAddress">
                            <label placeholder="Address"></label>
                        </div>
                    </div>
                </form>
            </section>
        </div>
    </div>
</vendor-custom>
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
                            <div class="single-address" v-for="address in vendor.addresses">
                                <div class="content">
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
                </div>

                <em v-else>None</em>
            </section>
        </div>
    </div>
</vendor-custom>
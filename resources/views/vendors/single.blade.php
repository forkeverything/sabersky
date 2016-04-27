@extends('layouts.app')
@section('content')
        <vendor-single inline-template>
                <div class="container" id="vendor-single">
                        <input type="hidden" value="{{ $vendor->id }}" v-model="vendorID">
                        <div class="row">
                                <div class="col-sm-4">
                                        <div class="description page-body">
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
                                                        <span v-else class="no-description" @click="startEditDescription" v-show="! editDescription">
                                                        None -
                                                        click to write a description</span>
                    <textarea class="autosize description-editor form-control live-editor" v-model="description"
                              v-show="editDescription" @blur="saveDescription">@{{ vendor.description }}</textarea>
                                                </div>
                                                @else
                                                        <h5>Description</h5>
                                                        <p v-if="vendor.description && vendor.description.length > 0">
                                                                @{{ vendor.description }}
                                                        </p>
                                                        <span v-else class="no-description">None</span>
                                                        @endcan
                                        </div>
                                        <div class="link-company page-body">
                                                <div class="link-top">
                                                        <h5>Company Link</h5>
                                                        <span class="link-badge" :class="vendorLink">@{{ vendorLink }}</span>
                                                </div>
                                                <div class="linked-company-info" v-if="vendor.linked_company_id">
                                                        <span class="company-name">@{{ vendor.linked_company.name }}</span>
                                                        <p class="description">@{{ vendor.linked_company.description }}</p>
                                                </div>
                                            <vendor-single-link-company :vendor.sync="vendor"></vendor-single-link-company>
                                            @can('vendor_manage')
                                            <div class="link-bottom align-end" v-if="vendor.linked_company_id">
                                                <button type="button" class="btn btn-small btn-outline-grey" @click="unlinkCompany">Unlink Company</button>
                                            </div>
                                            @endcan
                                        </div>
                                </div>
                                <div class="col-sm-8">
                                        <div class="bank-accounts page-body">
                                                <div class="section-top">
                                                        <h5>Bank Accounts</h5>
                                                        <add-bank-account-modal :vendor.sync="vendor"></add-bank-account-modal>
                                                </div>
                                                <div class="bank-accounts-collection"
                                                     v-if="vendor.bank_accounts && vendor.bank_accounts.length > 0">

                                                        <div class="single-bank-account" v-for="account in vendor.bank_accounts">
                                                                <div class="header">
                                                                        <h5>@{{ account.bank_name }}</h5>
                                                                </div>
                                                                <div class="account-controls">
                                                                        <a class="dotted clickable link-set-account-primary" @click="bankSetPrimary(account)" v-if="! account.primary">Set primary</a>
                                                                        <span v-else class="label-primary"><i class="fa fa-check"></i>Primary</span>
                                                                        <a class="link-remove-account clickable" @click.prevent="deleteAccount(account)">remove</a>
                                                                </div>
                                                                <div class="account-body">
                                                                        <div class="account">
                                                                                <span class="heading">Account</span>
                                                                                <!-- account Table -->
                                                                                <table class="table table-account-info">
                                                                                        <tbody>
                                                                                        <tr>
                                                                                                <td>Name</td>
                                                                                                <td>@{{ account.account_name }}</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td>Number</td>
                                                                                                <td>@{{ account.account_number }}</td>
                                                                                        </tr>
                                                                                        </tbody>
                                                                                </table>
                                                                        </div>
                                                                        <div class="bank">
                                                                                <span class="heading">Bank</span>
                                                                                <div class="bank-name"><strong>@{{ account.bank_name }}</strong></div>
                                                                                <div class="extra-info">
                                                                                        <div class="bank-phone">
                                                                                                <span class="bank-label">Phone Number</span><span
                                                                                                        v-if="account.bank_phone">@{{ account.bank_phone }}</span><span
                                                                                                        v-else>-</span>
                                                                                        </div>
                                                                                        <div class="bank-address">
                                                                                                <span class="bank-label">Branch Address</span>
                                                                                                <span v-if="account.bank_address">@{{ account.bank_address }}</span><span
                                                                                                        v-else>-</span>
                                                                                        </div>
                                                                                        <div class="swift">
                                                                                                <span class="bank-label">SWIFT / IBAN</span>
                                                                                                <span v-if="account.swift">@{{ account.swift }}</span><span v-else>-</span>
                                                                                        </div>
                                                                                </div>
                                                                        </div>
                                                                </div>
                                                        </div>

                                                </div>

                                            <div v-else class="empty-stage">
                                                <i class="fa fa-bank"></i>
                                                <h3>No Accounts Found</h3>
                                                <p>Add a bank account to a vendor and for effortless payments of purchase orders</p>
                                            </div>
                                        </div>
                                </div>
                        </div>
                        <div class="addresses page-body">
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
                                addressSetPrimary(address)">Set primary</a>
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
                                    <span class="address_2 block"
                                          v-if="address.address_2">@{{ address.address_2 }}</span>
                                                                <span class="city">@{{ address.city }}</span>,
                                                                <span class="state">@{{ address.state }}</span>
                                                                <span class="country block">@{{ address.country }}</span>
                                                        </div>
                                                </div>
                                        </div>
                                </div>

                                <em v-else>No addresses registered to vendor</em>
                        </div>
                </div>
        </vendor-single>
@endsection

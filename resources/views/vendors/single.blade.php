@extends('layouts.app')
@section('content')
    <vendor-single inline-template :vendor="{{ $vendor }}" :user="user">
        <div class="container" id="vendor-single">
            <h1>{{ $vendor->name }}</h1>
            <section class="description">
                @can('edit', $vendor)
                    <h4 class="loading-header"
                        :class="{
                'loading': savedDescription === 'saving',
                'success': savedDescription === 'success',
                'error': savedDescription === 'error'
                }"
                    >Description</h4>
                    <div class="form-group">
                        <p v-if="description" @click="startEditDescription" v-show="! editDescription"
                        >@{{ vendor.description }}</p>
                        <span v-else class="no-description" @click="startEditDescription" v-show="
                ! editDescription"
                        >
                        None -
                        click to write a description</span>
                <textarea class="autosize description-editor form-control live-editor" v-model="description"
                          v-show="editDescription" @blur="saveDescription">@{{ vendor.description }}</textarea>
                    </div>
                @else
                    <h4>Description</h4>
                    <p v-if="vendor.description">
                        @{{ vendor.description }}
                    </p>
                    <span v-else class="no-description">None</span>
                @endcan
            </section>
            <section class="bank-accounts">

                <h4>Bank Accounts</h4>
                <add-bank-account-modal :vendor.sync="vendor"></add-bank-account-modal>
                <div class="bank-accounts-collection"
                     v-if="vendor.bank_accounts.length > 0"
                >

                    <div class="single-bank-account"
                         v-for="account in vendor.bank_accounts"
                         :class="{ 'primary': account.primary }"
                    >
                        <div class="controls">
                            <a class="dotted clickable link-set-account-primary" @click="bankSetPrimary(account)
                        " v-if="! account.primary">Set primary</a>
                            <span v-else class="label-primary"><i class="fa fa-check"></i>Primary</span>
                            <a class="remove clickable" @click.prevent="deleteAccount(account)"><i
                                        class="fa fa-close"></i></a>
                        </div>

                        <bank-account :account="account"></bank-account>
                    </div>

                </div>
                <div v-else class="empty-stage">
                    <i class="fa fa-bank"></i>
                    <h4>No Accounts Added</h4>
                    <p>Add a bank account here for it to be selectable when submitting purchase orders</p>
                </div>
            </section>

            <section class="addresses">
                <h4>Addresses</h4>
                @can('edit', $vendor)
                    <add-address-modal :owner-id="{{ $vendor->id }}" :owner-type="'App\Vendor'"></add-address-modal>
                @endcan
                <div class="addresses-collection" v-if="vendor.addresses.length > 0">
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

                            <address :address="address"></address>

                        </div>
                    </div>
                </div>
                <div v-else class="empty-stage">
                    <i class="fa fa-book"></i>
                    <h4>No Known Addresses</h4>
                    <p>Register vendor addresses to be attached to Purchase Orders</p>
                </div>
            </section>


            <section class="vendor-notes">
                <h4>Notes</h4>
                <notes subject="vendor" subject_id="{{ $vendor->id }}" :user="user"></notes>
            </section>

            @include('layouts.partials.activities_log', ['activities' => $vendor->activities])
        </div>
    </vendor-single>
@endsection

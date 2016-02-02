@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-orders-submit">
        <a href="{{ route('showAllPurchaseOrders') }}" class="back-link"><i class="fa  fa-arrow-left fa-btn"></i>Purchase
            Orders</a>
        <div class="page-header">
            <h1 class="page-title">Submit Purchase Order</h1>
        </div>
        <p class="page-intro">Purchase orders which have been submitted by the Procurement team.</p>
        @include('errors.list')
        @if(! $existingPO)
            <form action="{{ route('savePOStep1') }}" id="form-submit-purchase-order" method="POST">
                @elseif(! $existingPO->vendor_id)
                    <form action="{{ route('savePOStep2') }}" id="form-submit-purchase-order" method="POST">
                        @endif
                        {{ csrf_field() }}
                        @if(! $existingPO)
                            <section class="form-section-project">
                                <h5>Step 1: Select Project</h5>
                                <div class="form-group">
                                    <label for="field-project-id">Which Project is this Purchase Order for?</label>
                                    <select name="project_id" id="field-project-id" class="form-control">
                                        <option disabled selected>Select a project</option>
                                        @foreach(Auth::user()->projects as $project)
                                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </section>
                        @elseif(! $existingPO->vendor_id)
                            <section>
                                <h5>Step 2: Vendor Details</h5>
                                <div class="row button-select-vendor-type">
                                    <div class="col-sm-6">
                                        <button class="btn-outline-yellow"
                                                type="button"
                                        @click="selectVendor('existing')"
                                        :class="{'active': vendorType == 'existing'}"
                                        >
                                        Existing Vendor</button>
                                    </div>
                                    <div class="col-sm-6">
                                        <button class="btn-outline-yellow"
                                                type="button"
                                        @click="selectVendor('new')"
                                        :class="{'active': vendorType == 'new'}"
                                        >
                                        New Vendor</button>
                                    </div>
                                </div>
                                <div v-show="vendorType == 'existing'">
                                    <div class="form-group">
                                        <label for="field-vendor-id">Bought from this Vendor / Supplier before?</label>
                                        <select name="vendor_id" id="field-vendor-id" class="form-control"
                                                v-model="vendor_id">
                                            <option disabled selected>Choose an existing vendor</option>
                                            @foreach(Auth::user()->company->vendors as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}
                                                    ({{ $vendor->phone }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div v-show="vendorType == 'new'">
                                    <div class="row">
                                        <div class="form-group col-sm-6">
                                            <label for="field-new-vendor-name">Company Name</label>
                                            <input type="text" id="field-new-vendor-name" name="name"
                                                   value="{{ old('name') }}"
                                                   class="form-control" placeholder="PT. Example Corp" v-model="name">
                                        </div>
                                        <div class="form-group col-sm-6">
                                            <label for="field-new-vendor-phone">Phone</label>
                                            <input type="text" id="field-new-vendor-phone" name="phone"
                                                   value="{{ old('phone') }}"
                                                   class="form-control" placeholder="1234 5678" v-model="phone">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="field-new-vendor-address">Address</label>
                                        <input type="text" id="field-new-vendor-address" name="address"
                                               value="{{ old('address') }}"
                                               class="form-control" placeholder="123 Example st." v-model="address">
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-sm-4">
                                            <label for="new-vendor-bank-account-name">Bank Account Name</label>
                                            <input type="text" id="new-vendor-bank-account-name"
                                                   name="bank_account_name"
                                                   value="{{ old('bank_account_name') }}" class="form-control"
                                                   placeholder="John Smith" v-model="bank_account_name">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="new-vendor-bank-number">Bank Account Number</label>
                                            <input type="text" id="new-vendor-bank-number" name="bank_account_number"
                                                   value="{{ old('bank_account_number') }}" class="form-control"
                                                   placeholder="88855888" v-model="bank_account_number">
                                        </div>
                                        <div class="form-group col-sm-4">
                                            <label for="new-vendor-bank-name">Bank</label>
                                            <input type="text" id="new-vendor-bank-name" name="bank_name"
                                                   value="{{ old('bank_name') }}"
                                                   class="form-control" placeholder="Mandiri" v-model="bank_name">
                                        </div>
                                    </div>
                                </div>
                            </section>
                        @else
                            <section>
                                <h5>Step 3: Add Purchase Requests</h5>
                                <a href="{{ route('addLineItem') }}"><button class="btn-solid-blue button-add-line-item">Add Purchase Request</button></a>
                                @if($existingPO->lineItems()->first())
                                    @foreach($existingPO->lineItems() as $lineItem)
                                    {{ $lineItem }}
                                    @endforeach
                                @endif
                                <p class="text-center">No Purchase Request has been added to this order.</p>
                            </section>
                            @endif
                            @if(! $existingPO)
                                    <!-- Submit -->
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary form-control">Next Step 2: Vendor Details
                                </button>
                            </div>
                            @elseif(! $existingPO->vendor_id)
                                    <!-- Submit -->
                            <div class="form-group" v-show="readyStep3">
                                <button type="submit" class="btn btn-primary form-control">Next Step 3: Add Items
                                </button>
                            </div>
                        @endif
                    </form>
    </div>
@endsection
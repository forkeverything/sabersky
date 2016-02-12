@extends('layouts.app')
@section('content')
    <div class="container" id="purchase-requests-add">
        <a href="{{ route('showAllPurchaseRequests') }}" class="back-link no-print"><i
                    class="fa  fa-arrow-left fa-btn"></i>Back
            to
            Purchase Requests</a>
        <div class="page-header">
            <h1 class="page-title">Make Purchase Request</h1>
        </div>
        @include('errors.list')
        <form action="{{ route('savePurchaseRequest') }}" id="form-make-purchase-request" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="field-project-id">Which Project is this Purchase Request for?</label>
                <select name="project_id" id="field-project-id" class="form-control" v-model="projectId">
                    <option disabled selected value="">Select a project</option>
                    @foreach(Auth::user()->projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="item-selection">
                <div class="button-group form-group item_buttons" role="group" aria-label="choose_item_buttons">
                    <button class="btn btn-solid-blue"
                            type="button"
                    @click="changeExistingItem(true)"
                    >
                    Existing Item
                    </button>
                    <button class="btn btn-outline-blue"
                            type="button"
                    @click="changeExistingItem(false)"
                    >
                    New Item
                    </button>
                </div>
                <div class="existing_item"
                     v-show="existingItem"
                >
                    <h5>Select Existing Item</h5>
                    <div class="find-existing"
                         v-show="! selectedItem"
                    >
                        <div class="form-group">
                            <select
                                    id="field-item-id"
                                    class="form-control"
                                    v-model="existingItemName"
                            >
                                <option disabled selected value="">Choose an Item Name</option>
                                <template v-for="item in uniqueItemNames">
                                    <option>@{{ item.name }}</option>
                                </template>
                            </select>
                            <input type="hidden" name="item_id" v-model="selectedItem.id">
                        </div>
                        <div class="table-responsive spec-table-wrap"
                             v-show="existingItemName"
                        >
                            <!--  Table -->
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>Specification</th>
                                <tr>
                                </thead>
                                <tbody>
                                <template
                                        v-for="item in itemsWithName"
                                >
                                    <tr>
                                        <td class="clickable"
                                        @click="selectItem(item)"
                                        >@{{ item.specification }}</td>
                                    </tr>
                                </template>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="selected-existing"
                         v-show="selectedItem"
                    >
                        <p class="item-details">
                            <strong>@{{ selectedItem.name }}</strong><span @click="clearSelectedExisting" class="clickable btn-remove">&times;</span>
                            <br>
                            @{{ selectedItem.specification }}
                        </p>
                    </div>
                </div>
                <div class="pr_new_item"
                     v-show="!existingItem"
                >
                    <h5>Add New Item</h5>
                    <div class="form-group">
                        <label for="field-new-item-name">Name</label>
                        <input type="text" id="field-new-item-name" name="name" value="{{ old('name') }}"
                               class="form-control" placeholder="Steel Pipe">
                    </div>
                    <label for="field-new-item-specification">Detailed Specification</label>
                    <textarea name="specification" id="field-new-item-specification" rows="10" class="form-control"
                              placeholder="60cm Diameter, 2.4 inches Thick, Length 3m...">{{ old('specification') }}</textarea>
                </div>
            </div>
            <hr>
            <div class="form-group">
                <label for="field-quantity">How many Item(s) are needed</label>
                <input type="number" id="field-quantity" name="quantity" value="{{ old('quantity') }}"
                       class="form-control" placeholder="10">
            </div>
            <div class="form-group">
                <label for="field-date">Approximate date required by</label>
                <input type="date" class="form-control" name="due" id="field-date">
            </div>
            <div class="form-group">
                <label for="button-urgent">Do you need this item immediately?</label>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default urgent-button">
                        <input type="checkbox" autocomplete="off" name="urgent" id="button-urgent" value="1">URGENT
                    </label>
                </div>
            </div>
            <!-- Submit -->
            <div class="form-group">
                <button type="submit" class="btn btn-solid-green form-control">Make Request</button>
            </div>
        </form>
    </div>
@endsection

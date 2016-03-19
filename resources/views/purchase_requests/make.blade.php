@extends('layouts.app')
@section('content')
    <purchase-requests-make inline-template>
        <div class="container" id="purchase-requests-add">
            <a href="{{ route('showAllPurchaseRequests') }}" class="back-link no-print"><i
                        class="fa  fa-arrow-left fa-btn"></i>Back
                to
                Purchase Requests</a>
            <div class="page-header">
                <h1 class="page-title">Make Purchase Request</h1>
            </div>
            <div class="page-body">
                @include('errors.list')
                <form action="{{ route('savePurchaseRequest') }}" id="form-make-purchase-request" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="project-selection">
                        <h2>1. Select Project</h2>
                        <select v-selectize="" class="form-group" name="project_id">
                            <option></option>
                            @foreach(Auth::user()->projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <h2>2. Enter Item Details</h2>
                    <div class="item-selection">
                        <div class="button-group form-group item_buttons" role="group" aria-label="choose_item_buttons">
                            <button class="btn"
                                    type="button"
                                    :class="{
                                'btn-solid-blue': existingItem,
                                'btn-outline-blue': ! existingItem
                            }"
                            @click="changeExistingItem(true)"
                            >
                            Existing Item
                            </button>
                            <button class="btn btn-outline-blue"
                                    type="button"
                                    :class="{
                                'btn-solid-blue': ! existingItem,
                                'btn-outline-blue': existingItem
                            }"
                            @click="changeExistingItem(false)"
                            >
                            New Item
                            </button>
                        </div>
                        <div class="pr-existing-item"
                             v-show="existingItem"
                        >
                            <p class="text-muted">Search for any item that has been requested before by any team member from your company. You can also add extra photos to existing items here. If you would like to manage available items, you can do that from the items section.</p>
                            <div class="find-existing"
                                 v-show="! selectedItem"
                            >
                                <div class="form-group">
                                    <input type="hidden" name="item_id" v-model="selectedItem.id">
                                    <select id="select-existing-item-id" v-selectize="existingItemName" class="form-group">
                                        <option></option>
                                        @foreach(Auth::user()->company->items() as $item)
                                            <option value="{{ $item->name }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>

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
                                                >
                                                @{{ item.specification }}
                                                </td>
                                            </tr>
                                        </template>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="selected-existing"
                                 v-show="selectedItem"
                            >
                                <span @click="clearSelectedExisting" class="
                            clickable btn-remove">&times;</span>
                                <div class="form-group">
                                    <h2>@{{ selectedItem.name }}</h2>
                                    <p class="item-details">
                                        @{{ selectedItem.specification }}
                                    </p>
                                </div>
                                @include('layouts.partials.input_item_photos')
                            </div>
                        </div>
                        <div class="pr-new-item"
                             v-show="!existingItem"
                        >
                            <p class="text-muted">When adding a new item, be sure to select an item with the same name if it already exists. Always make sure the item you are creating doesn't already exist to avoid having duplicates of items with identical specifications. </p>
                            <div class="form-group">
                                <label for="field-new-item-name">Name</label>
                                <select name="name" id="select-new-item-name">
                                    <option></option>
                                    @foreach(Auth::user()->company->items() as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="field-new-item-specification">Detailed Specification</label>
                        <textarea name="specification" id="field-new-item-specification" rows="10" class="form-control"
                                  placeholder="60cm Diameter, 2.4 inches Thick, Length 3m...">{{ old('specification') }}
                        </textarea>
                            </div>
                            @include('layouts.partials.input_item_photos')
                        </div>
                    </div>

                    <h2>
                        3. Set Requirements
                    </h2>
                    <div class="table-responsive request-specifics">
                        <!--  Table -->
                        <table class="table table-bordered">
                            <tbody>
                            <tr>
                                <th>Quantity Required</th>
                                <td>
                                    <input type="number" id="field-quantity" name="quantity" value="{{ old('quantity') }}"
                                           placeholder="10">
                                </td>
                            </tr>
                            <tr>
                                <th>Date Needed By</th>
                                <td>
                                    <input type="text" name="due" class="datepicker" placeholder="Pick a date">
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    Urgent
                                </th>
                                <td>
                                    <input type="checkbox" name="urgent" value="1" id="checkbox-urgent"><label>High priority</label>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>


                    <!-- Submit -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-solid-green form-control">Make Request</button>
                    </div>
                </form>
            </div>
        </div>
    </purchase-requests-make>
@endsection

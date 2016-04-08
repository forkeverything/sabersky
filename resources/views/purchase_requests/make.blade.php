@extends('layouts.app')
@section('content')
    <purchase-requests-make inline-template>
        <div class="container" id="purchase-requests-make" v-show="pageReady">
            @include('errors.list')
            <form action="{{ route('savePurchaseRequest') }}" id="form-make-purchase-request" method="POST" enctype="multipart/form-data">
            <div class="page-body">
                {{ csrf_field() }}
                <div class="project-selection">
                    <h5>Project</h5>
                    <select v-selectize="" class="form-group" name="project_id">
                        <option></option>
                        @foreach(Auth::user()->projects as $project)
                            <option value="{{ $project->id }}" class="capitalize">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <h5>Item</h5>
                <div class="item-selection">
                    <div class="button-group form-group item_buttons" role="group" aria-label="choose_item_buttons">
                        <button class="btn"
                                type="button"
                                :class="{
                                'btn-solid-blue': existingItem,
                                'active': existingItem,
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
                                'active': ! existingItem,
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
                        <div class="find-existing"
                             v-show="! selectedItem"
                        >
                            <label for="#select-existing-item-id">Item Name</label>
                                <input type="hidden" name="item_id" v-model="selectedItem.id">
                                <select id="select-existing-item-id" v-selectize="existingItemName"
                                >
                                    <option></option>
                                    @foreach(Auth::user()->company->items() as $item)
                                        <option value="{{ $item->name }}">{{ $item->name }}</option>
                                    @endforeach
                                </select>
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
                                <h5>@{{ selectedItem.name }}</h5>
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
                            <label for="field-new-item-specification">Item Specifications</label>
                        <textarea name="specification" id="field-new-item-specification" rows="10" class="form-control">{{ old('specification') }}
                        </textarea>
                        </div>
                        @include('layouts.partials.input_item_photos')
                    </div>
                </div>

                <h5>
                    Requirements
                </h5>
                <div class="table-responsive request-specifics">
                    <!--  Table -->
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Quantity Required</th>
                            <td>
                                <input type="number" id="field-quantity" name="quantity"
                                       value="{{ old('quantity') }}"
                                       placeholder="eg. 8"
                                       min="0"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>Date Needed By</th>
                            <td>
                                <input type="text" name="due" class="datepicker" placeholder="Pick a date (dd/mm/yyyy)">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Require Immediately
                            </th>
                            <td>
                                <label><input type="checkbox" name="urgent" value="1" id="checkbox-urgent">Urgent</label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <section class="bottom children-right">
                <button type="submit" class="btn btn-solid-green">Make Request</button>
            </section>
            </form>
        </div>
    </purchase-requests-make>
@endsection

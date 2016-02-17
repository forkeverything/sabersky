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
        <form action="{{ route('savePurchaseRequest') }}" id="form-make-purchase-request" method="POST" enctype="multipart/form-data">
            {{ csrf_field() }}
            <div class="form-group">
                <label for="field-project-id">Which Project is this Purchase Request for?</label>
                <select v-selectize="" class="form-group" name="project_id">
                    <option></option>
                    @foreach(Auth::user()->projects as $project)
                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                    @endforeach
                </select>
            </div>
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
                <div class="existing_item"
                     v-show="existingItem"
                >
                    <h5>Select Existing Item</h5>
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
                        <div class="form-group">
                            <p class="item-details">
                                <strong>@{{ selectedItem.name }}</strong><span @click="clearSelectedExisting" class="
                            clickable btn-remove">&times;</span>
                                <br>
                                @{{ selectedItem.specification }}
                            </p>
                        </div>
                        <div class="form-group new-item-add-photo">
                            <label for="input-new-item-photos">Add Item Photos</label>
                            <input type="file" class="file input-item-photos" multiple="true" name="item_photos[]">
                        </div>
                    </div>
                </div>
                <div class="pr_new_item"
                     v-show="!existingItem"
                >
                    <h5>Add New Item</h5>
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
                    <div class="form-group new-item-add-photo">
                        <label for="input-new-item-photos">Add Item Photos</label>
                        <input type="file" class="file input-item-photos" multiple="true" name="item_photos[]">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="field-quantity">How many Item(s) are needed</label>
                <input type="number" id="field-quantity" name="quantity" value="{{ old('quantity') }}"
                       class="form-control" placeholder="10">
            </div>
            <div class="form-group">
                <label for="field-date">Date needed by</label>
                <div class="display-block">
                    <input type="text" name="due" class="datepicker" placeholder="Pick a date">
                </div>
            </div>
            <div class="form-group">
                <label for="checkbox-urgent">High priority item?</label>
                <div class="display-block">
                    <input type="checkbox" name="urgent" value="1" id="checkbox-urgent"> Urgent
                </div>
            </div>


            <!-- Submit -->
            <div class="form-group">
                <button type="submit" class="btn btn-solid-green form-control">Make Request</button>
            </div>
        </form>
    </div>
@endsection
@section('scripts.footer')
    <script src="{{ asset('/js/page/purchase-requests/make.js') }}"></script>
    <script>
        $('.input-item-photos').fileinput({
            'showUpload': false,
            'allowedFileExtensions': ['jpg', 'gif', 'png'],
            'showRemove': false,
            'showCaption': false,
            'previewSettings': {
                image: {width: "120px", height: "120px"}
            },
            'browseLabel': 'Browse',
            'browseIcon': '<i class="fa fa-folder-open"></i> &nbsp;',
            'browseClass': 'btn btn-outline-grey'
        });
    </script>
@endsection
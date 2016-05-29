@extends('layouts.app')
@section('content')
    <purchase-requests-make inline-template>
        <div class="container" id="purchase-requests-make" v-show="pageReady">
            <h1>
                Make Purchase Request
            </h1>
            <form-errors></form-errors>
            <form id="form-make-purchase-request"
                  @submit.prevent="submitMakePRForm"
            >
                <div class="project-selection part">
                    <h4>Project</h4>
                    <select v-selectize="projectID" name="project_id">
                        <option></option>
                        @foreach(Auth::user()->projects as $project)
                            <option value="{{ $project->id }}" class="capitalize">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="item-selection part">
                    <h4>Item</h4>
                    <add-item-modal :button-type="'blue'"></add-item-modal>
                    <select id="pr-item-selection" class="select-item">
                        <option></option>
                    </select>
                </div>

                <h4>
                    Requirements
                </h4>
                <div class="table-responsive request-specifics">
                    <!--  Table -->
                    <table class="table table-bordered">
                        <tbody>
                        <tr>
                            <th>Quantity Required</th>
                            <td>
                                <input type="number" id="field-quantity" name="quantity"
                                       value="{{ old('quantity') }}"
                                       placeholder="Quantity"
                                       min="0"
                                       v-model="quantity"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>Date Needed By</th>
                            <td>
                                <input type="text" name="due" class="datepicker" placeholder="Pick a date (dd/mm/yyyy)"
                                       v-model="due">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                Require Immediately
                            </th>
                            <td>
                                <label>
                                    <input type="checkbox"
                                           name="urgent"
                                           value="1"
                                           id="checkbox-urgent"
                                           v-model="urgent"
                                    >
                                    <i class="fa fa-warning badge-urgent"></i> Urgent
                                </label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bottom align-end">
                    <button type="button" class="btn btn-solid-green" @click="submitMakePRForm">Make Request</button>
                </div>
            </form>
        </div>
    </purchase-requests-make>
@endsection

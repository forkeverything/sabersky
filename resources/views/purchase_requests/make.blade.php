@extends('layouts.app')
@section('content')
    <purchase-requests-make inline-template>
        <div class="container" id="purchase-requests-make" v-show="pageReady">
            <form-errors></form-errors>
            <form id="form-make-purchase-request">
            <div class="page-body">
                {{ csrf_field() }}
                <div class="project-selection">
                    <h5>Project</h5>
                    <select v-selectize="projectID" class="form-group" name="project_id">
                        <option></option>
                        @foreach(Auth::user()->projects as $project)
                            <option value="{{ $project->id }}" class="capitalize">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>
                <h5>Item</h5>
                <div class="item-selection">
                    <select id="pr-item-selection" class="select-item">
                        <option></option>
                    </select>
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
                                       placeholder="Quantity"
                                       min="0"
                                       v-model="quantity"
                                >
                            </td>
                        </tr>
                        <tr>
                            <th>Date Needed By</th>
                            <td>
                                <input type="text" name="due" class="datepicker" placeholder="Pick a date (dd/mm/yyyy)" v-model="due | easyDateModel">
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
                                    Urgent
                                </label>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <section class="bottom children-right">
                <button type="button" class="btn btn-solid-green" @click="submitMakePRForm">Make Request</button>
            </section>
            </form>
        </div>
    </purchase-requests-make>
@endsection

@extends('settings.partials.layout')

@section('settings-header')
    <h1>Settings - Purchasing</h1>
    <p>
        Control precisely when a Purchase Order needs approval and who can clear each rule.When there are
        multiple rules that apply to a single Purchase Order, then all of them have to be cleared for the order
        to be approved. And if there are multiple roles that can clear a single rule, any of them have the
        ability to clear the rule. When a purchase order fails any rule, the whole order is rejected and it must
        be re-submitted.
    </p>
@endsection

@section('settings-content')
    <settings-rules inline-template :user.sync="user" :rules="{{ $rules }}" :rule-properties="{{ $ruleProperties }}">
        <div id="settings-rules">
            <div class="new-rule">
                <h4>
                    Create New Rule
                </h4>
                <!-- Rules Table -->
                <table class="table table-bordered rule-generator">
                    <tbody>
                    <tr>
                        <th>Property</th>
                        <td>
                            <select class="form-control themed-select"
                                    v-rule-property-select="selectedProperty"
                                    v-model="selectedProperty"
                                    title="Select one"
                            >
                                <option v-for="property in ruleProperties" v-selectoption
                                        :value="property">@{{ property.label }}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Trigger</th>
                        <td>
                            <select v-rule-trigger-select="selectedTrigger"
                                    v-model="selectedTrigger"
                                    class="form-control themed-select"
                                    title="Select one"
                            >
                                <option v-for="trigger in selectedProperty.triggers"
                                        value="@{{ trigger.id }}"
                                        v-selectoption
                                        :value="trigger"
                                >
                                    @{{ trigger.label }}</option>
                            </select>
                        </td>
                    <tr>
                    <tr v-show="selectedTrigger.has_currency">
                        <th>Currency</th>
                        <td>
                            <company-currency-selecter :currency-object.sync="currency"
                                                       :currencies="availableCurrencies"></company-currency-selecter>
                        </td>
                    </tr>
                    <tr>
                        <th>Limit</th>
                        <td>
                            <div class="input-group"
                                 v-if="selectedTrigger.limit_type === 'percentage'"
                            >
                                <number-input :model.sync="ruleLimit" :placeholder="'limit'"
                                              :class="['form-control', 'input-rule-limit']"
                                              :disabled="! ruleHasLimit"></number-input>
                                <span class="input-group-addon">%</span>
                            </div>

                            <div class="input-group"
                                 v-else
                            >
                                <span class="input-group-addon" v-cloak>@{{ currency.symbol }}</span>
                                <number-input :model.sync="ruleLimit" :placeholder="'limit'"
                                              :class="['form-control', 'input-rule-limit']"
                                              :disabled="! ruleHasLimit"></number-input>
                            </div>

                        </td>
                    </tr>
                    <tr>
                        <th>Approval by</th>
                        <td>

                            <select v-selectize="selectedRuleRoles" multiple>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->position }}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    </tbody>
                </table>
                <form-errors></form-errors>
                <div class="align-end">
                    <button class="btn btn-outline-blue"
                            type="button"
                            :disabled="! canSubmitRule"
                    @click="addRule"
                    >
                    Add Rule
                    </button>
                </div>
            </div>

            <div class="existing-rules">
                <h4>Active Rules</h4>
                <!-- Existing Rules Table -->
                <div class="table-responsive" v-if="hasRules">
                    <table class="table table-existing-rules table-hover">
                        <thead>
                        <tr>
                            <th>Property</th>
                            <th>Trigger</th>
                            <th>Currency</th>
                            <th>Limit</th>
                        <tr>
                        </thead>
                        <tbody>
                        <template v-for="rule in rules">
                            <tr class="clickable" @click="showRule(rule)">
                                <td>
                                    @{{ rule.property.label }}
                                </td>
                                <td>
                                    @{{ rule.trigger.label }}
                                </td>
                                <td>
                                    <span v-if="rule.trigger.has_currency">@{{ rule.currency.code }}</span>
                                    <em v-else>-</em>
                                </td>
                                <td>
                                    <span v-if="rule.trigger.has_limit">@{{ rule.limit | numberFormat }}<span v-if="rule.trigger.limit_type === 'percentage'">%</span></span>
                                    <em v-else>-</em>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
                <p class="text-muted"
                   v-else
                >
                    You do not have any Rules set up. Go ahead and create a new Rule above and it will show up here,
                    indicating that it is active.
                </p>
            </div>

            <div id="settings-rule-modal" class="modal-overlay" v-show="selectedRule" @click="hideModal">

                <div class="modal-body" @click.stop="">
                    <button type="button" @click="hideModal" class="btn button-hide-modal"><i
                            class="fa fa-close"></i></button>

                    <h2>@{{ selectedRule.property.label }} - @{{ selectedRule.trigger.label }} <span v-if="selectedRule.trigger.has_currency">@{{ selectedRule.currency.code }}</span> <span v-if="selectedRule.trigger.has_limit">@{{ selectedRule.limit | numberFormat }}</span><span v-if="selectedRule.trigger.limit_type === 'percentage'">%</span></h2>
                    <p class="description">A Purchase Order with this rule can be approved by any staff that has a role listed below.</p>
                    <h4>Approvable by</h4>
                    <ul class="list-unstyled list-roles" v-if="selectedRule.roles.length > 0">
                        <li v-for="role in selectedRule.roles">
                            @{{ role.position }}
                        </li>
                    </ul>
                    <em v-else>none</em>
                    <button type="button" class="btn btn-outline-red" @click="toggleConfirmDelete" v-show="! confirmDelete">Delete Rule</button>
                    <div class="confirm-delete" v-show="confirmDelete">
                        <button type="button" class="btn btn-outline-grey" @click="toggleConfirmDelete">Cancel</button>
                        <button type="button" class="btn btn-solid-red" @click="removeRule">Confirm Delete</button>
                        <p class="small">*Removing a rule is irreversible. Any Pending (Unapproved) Purchase Orders that is waiting for the Rule to be approved may automatically be approved for processing.</p>
                    </div>
                </div>

            </div>

        </div>
    </settings-rules>
@endsection

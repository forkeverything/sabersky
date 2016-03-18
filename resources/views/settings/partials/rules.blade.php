<div id="settings-rules" v-show="settingsView === 'rules'">
    <h2>Purchase Order Rules</h2>
    <p>
        Control precisely when a Purchase Order needs approval and who can clear each rule.When there are
        multiple rules that apply to a single Purchase Order, then all of them have to be cleared for the order
        to be approved. And if there are multiple roles that can clear a single rule, any of them have the
        ability to clear the rule. When a purchase order fails any rule, the whole order is rejected and it must
        be re-submitted.
    </p>
    <div class="new-rule">
        <h3>
            Create New Rule
        </h3>
        <!-- Rules Table -->
        <table class="table table-bordered rule-generator">
            <tbody>
            <tr>
                <th>Property</th>
                <td>
                    <select class="form-control"
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
                            class="form-control"
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
            <tr>
                <th>Limit</th>
                <td>
                    <input class="form-control input-rule-limit"
                           v-model="ruleLimit | numberModel"
                           placeholder="Enter a value"
                           :disabled="! ruleHasLimit"
                    >
                </td>
            </tr>
            <tr>
                <th>Approval by (Roles)</th>
                <td>
                    <select class="form-control"
                            v-selectpicker
                            multiple
                            v-model="selectedRuleRoles"
                            title="Select one or more"
                    >
                        <option v-for="role in roles" v-selectoption
                                :value="role">@{{ role.position | capitalize }}</option>
                    </select>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="row">
            <div class="col-md-4 col-md-offset-8">
                <button class="btn btn-outline-blue"
                        type="button"
                        :disabled="! canSubmitRule"
                @click="addRule"
                >
                Add Rule
                </button>
            </div>
        </div>
    </div>

    <div class="existing-rules">
        <h3>Active Rules</h3>
        <!-- Existing Rules Table -->
        <div class="table-responsive">
            <table class="table table-existing-rules table-striped">
                <thead>
                <tr>
                    <th>Property</th>
                    <th>Trigger</th>
                    <th>Limit</th>
                    <th>Approval by (Roles)</th>
                <tr>
                </thead>
                <tbody>
                <template v-for="(property, rules) in rules">
                        <template v-for="rule in rules">
                            <tr class="clickable">
                                <td v-if="$index === 0">
                                    @{{ property  }}
                                </td>
                                <td v-else></td>
                                <td class="property">
                                    @{{ rule.trigger.label }}
                                    <span class="button-remove" @click="setRemoveRule(rule)" data-toggle="modal" data-target
                                    ="#modal-confirm"><i class="fa fa-close"></i></span>
                                </td>
                                <td v-if="rule.limit">
                                    @{{ rule.limit | numberFormat }}
                                </td>
                                <td v-else>
                                    -
                                </td>
                                <td>
                                    <ul class="list-unstyled">
                                        <li v-for="role in rule.roles" class="role-position">@{{ role.position }}</li>
                                    </ul>
                                </td>
                            </tr>
                        </template>
                </template>
                {{--<template v-for="rule in rules">--}}
                    {{--<tr>--}}
                        {{--<td>@{{ rule.property.label }}</td>--}}
                        {{--<td>@{{ rule.trigger.label }}</td>--}}
                        {{--<td v-if="rule.limit.length > 0">@{{ rule.limit | numberFormat }}</td>--}}
                        {{--<td v-else><em>none</em></td>--}}
                        {{--<td>--}}
                            {{--<ul class="list-unstyled">--}}
                                {{--<li v-for="role in rule.roles" class="role-position">@{{ role.position }}</li>--}}
                            {{--</ul>--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                {{--</template>--}}
                </tbody>
            </table>
        </div>
    </div>
</div>
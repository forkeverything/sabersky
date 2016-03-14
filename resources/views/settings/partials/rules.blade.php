<div id="settings-rules" v-show="settingsView === 'rules'">
    <h2>Purchase Order Rules</h2>
    <p>
        Control precisely when a Purchase Order needs approval and who can clear each rule.When there are
        multiple rules that apply to a single Purchase Order, then all of them have to be cleared for the order
        to be approved. And if there are multiple roles that can clear a single rule, any of them have the
        ability to clear the rule. When a purchase order fails any rule, the whole order is rejected and it must
        be re-submitted.
    </p>
    <h5>
        Create New Rule
    </h5>
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
            >
                Add Rule
            </button>
        </div>
    </div>

    <hr>
    
    <h5>Registered Rules</h5>
</div>
 <div id="settings-rules" v-show="settingsView === 'rules'">
        <div class="approval_requirements">
            <h5>Purchase Order Rules</h5>
            <p>
                Control precisely when a Purchase Order needs approval and who can clear each rule.When there are
                multiple rules that apply to a single Purchase Order, then all of them have to be cleared for the order
                to be approved. And if there are multiple roles that can clear a single rule, any of them have the
                ability to clear the rule. When a purchase order fails any rule, the whole order is rejected and it must
                be re-created.
            </p>
        </div>

        <div class="rule-generator">

            <select v-selectpicker="selectedProperty">
                @foreach($properties as $property)
                    <option value="{{ $property->name }}">{{ $property->label }}</option>
                @endforeach
            </select>

        </div>
    </div>
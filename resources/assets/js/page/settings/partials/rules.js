Vue.component('settings-rules', {
    name: 'settingsRules',
    el: function () {
        return '#settings-rules'
    },
    data: function () {
        return {
            ajaxReady: true,
            selectedProperty: false,
            selectedTrigger: false,
            selectedRuleRoles: [],
            ruleLimit: '',
            currency: '',
            ruleToRemove: false,
            selectedRule: '',
            confirmDelete: false
        };
    },
    props: [
        'user', 'rules', 'rule-properties'
    ],
    computed: {
        ruleHasLimit: function () {
            return (this.selectedTrigger && this.selectedTrigger.has_limit);
        },
        canSubmitRule: function () {

            var valid = true;

            if (!this.selectedProperty) valid = false;

            if (!this.selectedTrigger) valid = false;

            if (!this.selectedRuleRoles || !this.selectedRuleRoles.length > 0) valid = false;

            if (this.ruleHasLimit && !this.ruleLimit > 0) valid = false;

            if (this.selectedTrigger.has_currency && !this.currency.id) valid = false;

            return valid;

        },
        hasRules: function () {
            return !_.isEmpty(this.rules);
        },
        sortedRules: function() {
            return _.sortBy(this.rules, function(rule) { return rule.rule_property_id; });
        }
    },
    methods: {
        setTriggers: function () {
            this.selectedTrigger = '';
        },
        addRule: function () {
            var self = this;
            vueClearValidationErrors(self);
            var postData = {
                rule_property_id: self.selectedProperty.id,
                rule_trigger_id: self.selectedTrigger.id,
                has_limit: self.selectedTrigger.has_limit,
                limit: self.ruleLimit,
                has_currency: self.selectedTrigger.has_currency,
                currency_id: self.currency.id,
                roles: self.selectedRuleRoles
            };
            $.ajax({
                url: '/api/rules',
                method: 'POST',
                data: postData,
                success: function (data) {
                    self.rules.push(data);
                    flashNotify('success', 'Added new rule');
                    self.resetRuleValues();
                },
                error: function (response) {
                    console.log(response);
                    vueValidation(response, self);
                    self.resetRuleValues();
                    flashNotify('error', 'Could not add rule');
                }
            });
        },
        resetRuleValues: function () {
            this.ruleLimit = '';
            this.selectedRuleRoles = [];
        },
        setRemoveRule: function (rule) {
            this.ruleToRemove = rule;
            this.$broadcast('new-modal', {
                title: 'Confirm Remove Rule',
                body: "Removing a rule is irreversible. Any Pending (Unapproved) Purchase Orders that is waiting for the Rule to be approved may automatically be approved for processing.",
                buttonClass: 'btn-danger',
                buttonText: 'remove',
                callbackEventName: 'remove-rule'
            });
        },
        removeRule: function () {
            var self = this;
            if (!self.ajaxReady) return;
            self.ajaxReady = false;
            $.ajax({
                url: '/api/rules/' + self.selectedRule.id,
                method: 'DELETE',
                success: function (data) {
                    // success
                    self.rules = _.reject(self.rules, self.selectedRule);
                    self.selectedRule = '';
                    self.confirmDelete = false;
                    flashNotify('info', 'Removed rule');
                    self.ajaxReady = true;
                },
                error: function (response) {
                    console.log('Request Error!');
                    console.log(response);
                    self.ajaxReady = true;
                }
            });
        },
        showRule: function(rule) {
            this.selectedRule = rule;
        },
        hideModal: function() {
            this.selectedRule = '';
            self.confirmDelete = false;
        },
        toggleConfirmDelete: function() {
            this.confirmDelete = ! this.confirmDelete;
        }
    },
    events: {
        'remove-rule': function () {
            this.removeRule();
        }
    },
    mixins: [userCompany],
    ready: function () {
    }
});
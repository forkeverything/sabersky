<?php


namespace App\Utilities;

use App\Role;
use App\Rule;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Helper Class to help us make a new Rule object. Will handles thing's like
 * Validation for Rule: properties, triggers, roles. Extracted methods to
 * a separate class because too many inside methods to store in model.
 */
class RuleMaker
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * User who is making the rule.
     * @var User
     */
    protected $user;

    /**
     * Defined set of properties and triggers
     * that a rule can have.
     *
     * @var
     */
    protected $ruleProperties;

    /**
     * User supplied variables
     * @var
     */
    protected $propertyId;
    protected $triggerId;
    protected $selectedRoles;


    /**
     * Build our RuleMaker, we need a request, as well as the user
     * trying to make the rule.
     *
     * @param Request $request
     * @param User $user
     */
    public function __construct(Request $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
        $this->ruleProperties = getRuleProperties();

        // Init empty array to hold our Role models
        $this->selectedRoles = [];
        foreach($request->input('roles') as $roleObject) {
            $role = Role::find($roleObject['id']);
            array_push($this->selectedRoles, $role);
        }
    }

    public function make()
    {
        // Is property id valid?
        // Is trigger valid?
        // Are the roles valid?
        return $this->validateProperty()
            ->validateTrigger()
            ->validateRoles()
            ->duplicateChecker()
            ->saveDB();
        //  --  Create a new Rule & save it
        // return Rule back to the client
    }

    protected function validateProperty()
    {
        // Given id for the property
        $this->propertyId = $this->request->input('rule_property_id');
        // Get array of all valid property ids
        $propertyIds = $this->ruleProperties->pluck('id')->all();
        // Is the given property id a part of the array?
        if(in_array($this->propertyId, $propertyIds)) return $this;
        // Nothing returned - invalid property
        abort(400, 'Invalid Rule Property');
    }

    protected function validateTrigger()
    {
        // Grab the given trigger id
        $this->triggerId = $this->request->input('rule_trigger_id');
        // Grab all the available triggers for the given property
        $availableTriggerIds = collect($this->ruleProperties->where('id', (int)$this->propertyId)->first()->triggers)->pluck('id')->all();
        // If given trigger id in array = valid!
        if(in_array($this->triggerId, $availableTriggerIds)) return $this;
        abort(400, 'Invalid Rule Trigger');
    }

    protected function validateRoles()
    {
        foreach($this->selectedRoles as $selectedRole) {
            // if company roles does NOT contain role - abort out
            if(! $this->user->company->roles->contains($selectedRole)) abort(400, 'Role does not belong to User company');
        }
        // Alls well, no aborts
        return $this;
    }

    protected function duplicateChecker()
    {
        foreach($this->user->company->rules as $rule) {
            if($rule->rule_property_id == $this->propertyId && $rule->rule_trigger_id == $this->triggerId) abort(409, 'Rule already exists');
        }
        return $this;
    }

    protected function saveDB()
    {
        $rule = Rule::create([
            'limit' => $this->request->input('limit'),
            'rule_property_id' => $this->propertyId,
            'rule_trigger_id' => $this->triggerId,
            'company_id' => $this->user->company->id
        ]);
        
        // Attach roles
        $roleIds = collect($this->selectedRoles)->pluck('id')->all();
        $rule->roles()->attach($roleIds);
        
        return $rule;
    }

}

<?php


namespace App\Factories;

use App\Http\Requests\MakeRuleRequest;
use App\Role;
use App\Rule;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RuleFactory
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
     * Our created Rule model
     *
     * @var
     */
    protected $rule;

    /**
     * Static wrapper to 'make' a new Rule using this factory
     * @param MakeRuleRequest $request
     * @param User $user
     * @return mixed
     */
    public static function make(MakeRuleRequest $request, User $user)
    {
        $factory = new static($request, $user);
        $factory->duplicateChecker()
                ->createRule()
                ->attachRoles();

        return $factory->rule;
    }

    /**
     * Build our Factory, we need a request, as well as the user
     * trying to make the rule.
     *
     * @param MakeRuleRequest $request
     * @param User $user
     */
    public function __construct(MakeRuleRequest $request, User $user)
    {
        $this->request = $request;
        $this->user = $user;
    }


    /**
     * Check for duplicate Rule, we can catch the Exception and handle it
     * better here than an SQL error.
     *
     * TODO ::: handle error exception
     *
     * @return $this
     */
    protected function duplicateChecker()
    {
        foreach ($this->user->company->rules as $rule) {
            if ($rule->rule_property_id == $this->request->rule_property_id && $rule->rule_trigger_id == $this->request->rule_trigger_id && $rule->currency_id == $this->request->currency_id) abort(409, 'Rule already exists');
        }
        return $this;
    }

    /**
     * Create our Rule
     *
     * @return $this
     */
    protected function createRule()
    {

        $this->rule = Rule::create([
            'limit' => $this->request->limit,
            'currency_id' => $this->request->currency_id,
            'rule_property_id' => $this->request->rule_property_id,
            'rule_trigger_id' => $this->request->rule_trigger_id,
            'company_id' => $this->user->company->id
        ]);

        return $this;
    }

    /**
     * Grab the Role Ids and attach them to the rule.
     *
     * @return $this
     */
    protected function attachRoles()
    {
        $roleIds = array_pluck($this->request->roles, 'id');
        $this->rule->roles()->attach($roleIds);

        return $this;
    }

}

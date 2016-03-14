<?php


namespace App\Utilities;

use App\User;
use Illuminate\Http\Request;

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
     * @var User
     */
    protected $user;

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
    }

    public function make()
    {
        // Is property id valid?
        // Is trigger valid?
        // Are the roles valid?
        return $this->validateProperty()
            ->validateTrigger()
            ->validateRoles()
            ->saveDB();
        //  --  Create a new Rule & save it
        // return Rule back to the client
    }

    protected function validateProperty()
    {
        $propertyId = $this->request->input('rule_property_id');



        return $this;
    }

    protected function validateTrigger()
    {
        return $this;
    }

    protected function validateRoles()
    {
        return $this;
    }

    protected function saveDB()
    {
        return 'rule';
    }

}

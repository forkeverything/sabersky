<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRuleRequest;
use App\Http\Requests\MakeRuleRequest;
use App\Http\Requests\SaveRuleRequest;
use App\Rule;
use App\Utilities\RuleMaker;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        $this->middleware('settings.change');
    }

    /**
     * GET all the rules for the logged-in
     * User's company.
     *
     * @return mixed
     */
    public function getRules()
    {
        return Auth::user()->company->rules->groupBy('property.label');
    }

    /**
     * Handles GET request and fetches the list of
     * available rule properties & triggers.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPropertiesTriggers()
    {
        return getRuleProperties();
    }

    /**
     * Handles POST request to create a new rule
     * that determines when a Purchase Order
     * needs approval and who can give it.
     *
     * @param Request $request
     * @return static
     */
    public function postNewRule(MakeRuleRequest $request)
    {
        $rule = (new RuleMaker($request, Auth::user()))->make();
        return $rule->load('roles');
    }

    /**
     * Deletes a rule the rule found at
     * a given Rule ID.
     *
     * @param Rule $rule
     * @return mixed
     */
    public function delete(Rule $rule)
    {
        if(Auth::user()->company->rules->contains($rule)) {
            // Grab an array of all the PO the rule affects
            $affectedPOs = $rule->purchaseOrders;
            $rule->delete();
            // Re-check each PO for rules
            foreach($affectedPOs as $affectedPO) $affectedPO->tryAutoApprove();
            return response('Successfully removed Rule');
        }
        abort(403, 'Rule does not belong to user');
    }


}


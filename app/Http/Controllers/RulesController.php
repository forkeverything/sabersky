<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddRuleRequest;
use App\Http\Requests\SaveRuleRequest;
use App\Utilities\RuleMaker;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
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
     * Handles GET request and fetches the list of
     * available rule properties & triggers.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPropertiesTriggers()
    {
        $properties = collect(
            DB::table('rule_properties')
                ->select('*')
                ->get());

        // Initialize array
        foreach($properties as $property) {
            $property->triggers = [];
        }

        $triggers=  collect(
            DB::table('rule_triggers')
                ->select('*')
                ->get());


        foreach($triggers as $trigger) {
            array_push($properties[($trigger->rule_property_id - 1)]->triggers, $trigger);
        }


        return $properties;
    }

    public function postNewRule(Request $request)
    {

        // Assume all is good:
        // Pass request to Rule Class
        // Rule specific validation: (Request object?)
        // Is property id valid?
        // Is trigger valid?
        // Are the roles valid?
        //  --  Create a new Rule & save it
        // return Rule back to the client

        $rule = (new RuleMaker($request, Auth::user()))->make();
        return $rule;
    }


}


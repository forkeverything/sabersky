<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSettingsRequest;
use App\Permission;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function show()
    {
        if (Gate::allows('settings_change')) {
            $permissions = Permission::all();
            $roles = Auth::user()->company->roles;
            return view('settings.show', compact('permissions', 'roles'));
        }
        return redirect('/dashboard');
    }

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

}

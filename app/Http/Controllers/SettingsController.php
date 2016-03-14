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
            $properties = collect(
                DB::table('properties')
                    ->select('*')
                    ->get());
            $triggers = collect(
                DB::table('triggers')
                    ->select('*')
                    ->get());
            $roles = Auth::user()->company->roles;
            return view('settings.show', compact('permissions', 'roles', 'properties', 'triggers'));
        }
        return redirect('/dashboard');
    }

}

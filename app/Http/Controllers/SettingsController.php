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

    /**
     * GET req. to show the User's settings
     * page for the company.
     *
     * @return mixed
     */
    public function getShow()
    {
        if (Gate::allows('settings_change')) {
            $permissions = Permission::all(); // System-wide defined permissions, shared by all Users
            $roles = Auth::user()->company->roles;
            return view('settings.show', compact('permissions', 'roles'));
        }
        return redirect('/dashboard');
    }
}

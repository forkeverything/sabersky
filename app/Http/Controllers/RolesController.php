<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModifyRolesRequest;
use App\Permission;
use App\Role;
use Auth;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;

class RolesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        $this->middleware('settings.change', ['only' => 'postRemovePermission']);
    }

    public function getRoles()
    {
        return Auth::user()->company->roles->load('permissions');
    }

    public function postNewRole(Request $request)
    {
        return Auth::user()->company->roles()->create([
            'position' => $request->input('position')
        ])->load('permissions');

    }

    public function postRemovePermission(ModifyRolesRequest $request)
    {
        $role = Role::findOrFail($request->input('role')['id']);
        return $role->permissions()->detach($request->input('permission')['id']);
    }

    public function postGivePermission(ModifyRolesRequest $request){
        $role = Role::findOrFail($request->input('role')['id']);
        return $role->permissions()->attach($request->input('permission')['id']);
    }

    public function removeRole(ModifyRolesRequest $request)
    {
        $role = Role::findOrFail($request->input('role')['id']);
        $role->delete();

        return response("Succesfully removed role.", 201);
    }
}

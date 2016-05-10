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
        $this->middleware('settings.change', ['except' => 'apiGetRoles']);
        $this->middleware('api.only');
    }

    /**
     * Fetches the logged-in User's Company's
     * Roles with Permissions
     *
     * @return mixed
     */
    public function apiGetRoles()
    {
        return Auth::user()->company->roles->load('permissions');
    }

    /**
     * Saves a new role from a POST request
     *
     * @param Request $request
     * @return mixed
     */
    public function postNewRole(Request $request)
    {
        $newPosition = strtolower($request->input('position'));
        if(Auth::user()->company->roles->contains('position', $newPosition)) abort(409, 'That role already exists.');
        return Auth::user()->company->roles()->create([
            'position' => $newPosition
        ])->load('permissions');
    }

    /**
     * POST Request to remove a Role
     *
     * @param ModifyRolesRequest $request
     * @return mixed
     */
    public function postRemoveRole(ModifyRolesRequest $request)
    {
        $role = Role::findOrFail($request->input('role')['id']);
        if($role->position === 'admin') abort(403, 'Not allowed to delete admin!');
        if(count($role->users)) abort(406, "Cannot remove Role that still has active Users");
        $role->delete();
        return response("Succesfully removed role.", 201);
    }

    /**
     * POST req. to remove a permission
     * from a Role
     *
     * @param ModifyRolesRequest $request
     * @return mixed
     */
    public function postRemovePermission(ModifyRolesRequest $request)
    {
        $role = Role::findOrFail($request->input('role')['id']);
        if($role->position === 'admin') abort(403, 'Not allowed to modify Admin');
        return $role->permissions()->detach($request->input('permission')['id']);
    }

    /**
     * Handle Request to add a
     * Permission to a Role
     *
     * @param ModifyRolesRequest $request
     * @return mixed
     */
    public function postGivePermission(ModifyRolesRequest $request){
        $role = Role::findOrFail($request->input('role')['id']);
        return $role->permissions()->attach($request->input('permission')['id']);
    }

    /**
     * PUT Request to update a Role's
     * position
     *
     * @param Role $role
     * @param ModifyRolesRequest $request
     * @return $this
     */
    public function putUpdatePosition(Role $role, ModifyRolesRequest $request)
    {
        $newPosition = $request->input('newPosition');
        if($role->position === 'admin' || Auth::user()->company->roles->contains('position', $newPosition)) abort(403, 'Not allowed. Is Role position Admin or duplicate?');
        $role->position = $newPosition;
        $role->save();
        return $role->load('permissions');
    }
}

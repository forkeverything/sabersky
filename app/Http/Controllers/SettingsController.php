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

    public function apiShow(Request $request)
    {
        if (Gate::allows('settings_change') && $request->ajax()) {
            return (array)\DB::table('settings')->first();
        }
        abort(403, 'You shall not pass.');
    }

    public function save(SaveSettingsRequest $request)
    {
        DB::table('settings')->update([
            'po_high_max' => $request->input('po_high_max'),
            'po_med_max' => $request->input('po_med_max'),
            'item_md_max' => $request->input('item_md_max')
        ]);

        // flash settings saved
        return response('Succesfull updated settings!', 200);
    }

}

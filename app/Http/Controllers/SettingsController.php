<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveSettingsRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class SettingsController extends Controller
{
    public function show()
    {
        if (Gate::allows('settings_change')) {
            $settings = (object)\DB::table('settings')->first();
            return view('settings.show', compact('settings'));
        }
        return redirect('/dashboard');
    }

    public function save(SaveSettingsRequest $request)
    {
        DB::table('settings')->update([
            'po_high_max' => $request->input('po_high_max'),
            'po_med_max' => $request->input('po_med_max'),
            'item_md_max' => $request->input('item_md_max')
        ]);

        // flash settings saved
        return redirect()->back();
    }

}

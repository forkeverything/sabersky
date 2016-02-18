<?php

namespace App\Http\Controllers;

use App\Item;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ItemsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function all()
    {
        $itemNames = Auth::user()->company->items()->unique('name')->pluck('name');
        return view('items.all', compact('itemNames'));
    }

    public function apiAll()
    {
        return Auth::user()->company->items();
    }

    public function addPhoto(Request $request, Item $item)
    {
        if ($request->ajax()) {
            if(Gate::allows('edit', $item)) {
                $file = $request->file('item_photos')[0];
                $item->attachPhoto($file);
                return response()->json([
                    'status' => 'success',
                    'msg' => 'Added new photos to items.',
                ], 200);
            }
            return response()->json([
                'status' => 'error',
                'msg' => 'Item doesn\'t belong to you.',
            ], 402);
        } else {
            abort(403, 'Wrong way, go back!');
        }
    }
}

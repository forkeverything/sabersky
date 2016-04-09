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
    protected $items;

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        if ($user = Auth::user()) {
            $this->items = $user->company->items();
        }
    }

    /**
     * Show Item Catalog Page
     * @return mixed
     */
    public function getAll()
    {
        $breadcrumbs = [
            ['<i class="fa fa-legal"></i> Items', '#']
        ];
        return view('items.all', compact('breadcrumbs'));
    }


    public function apiAll()
    {
        return $this->items;
    }

    public function addPhoto(Request $request, Item $item)
    {
        if ($request->ajax()) {
            if (Gate::allows('edit', $item)) {
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

    public function single(Item $item)
    {

    }

    public function getName($name)
    {
        return $this->items->where('name', $name);
    }

}

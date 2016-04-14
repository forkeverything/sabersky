<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddItemRequest;
use App\Item;
use App\Repositories\CompanyItemsRepository;
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


    /**
     * Returns all of the User's Company's
     * Items
     *
     * @return mixed
     */
    public function apiGetAll(Request $request)
    {
        $brand = $request->query('brand');
        $project = $request->query('project');
        $sort = $request->query('sort');
        $order = $request->query('order');
        $perPage = $request->query('per_page');
        $search = $request->query('search');

        $items = CompanyItemsRepository::forCompany(Auth::user()->company)
                                       ->withBrand($brand)
                                       ->forProject($project)
                                       ->sortOn($sort, $order)
                                       ->searchSkuBrandName($search)
                                       ->with(['photos', 'purchaseRequests.project'])
                                       ->paginate($perPage);

        return $items;
    }


    /**
     * Retrieves all the Company's Item brands
     * @return mixed
     */
    public function apiGetAllBrands()
    {
        return Auth::user()->company->items()->select(['brand'])->distinct()->get();
    }

    /**
     * Performs a search for all the brands that
     * match the given query
     *
     * @param $query
     * @return mixed
     */
    public function apiGetSearchBrands($query)
    {
        if ($query) {
            $items = Item::where('company_id', Auth::user()->company->id);
            $items->where('brand', 'LIKE', '%' . $query . '%')
                  ->select(['brand']);
            return $items->distinct()->get();
        }

        return response("No search term given", 500);
    }

    /**
     * Tries to find a Single Item by either:
     * 1. SKU
     * 2. Brand & Name
     *
     * @param Request $request
     * @return mixed
     */
    public function apiGetSingleBy(Request $request)
    {
        if ($SKU = $request->input('sku')) {
            $item = Item::where('sku', $SKU)->first();
        } elseif ($brand = $request->input('brand') && $name = $request->input('name')) {
            $item = Item::where('brand', $brand)->where('name', $name)->first();
        }

        // If we have an item and it belongs to the same Company as User requesting it - return it
        if ($item && Gate::allows('edit', $item)) return $item;

        return [];
    }


    /**
     * Receives a Query and performs a DB search on:
     * sku, brand, and name - returns JSON
     * @param $query
     * @return mixed
     */
    public function getSearchItems($query)
    {
        if ($query) {
            $items = Item::where('company_id', Auth::user()->company->id);
            $items->where('sku', 'LIKE', '%' . $query . '%')
                  ->orWhere('brand', 'LIKE', '%' . $query . '%')
                  ->orWhere('name', 'LIKE', '%' . $query . '%')
                  ->with(['photos']);
            return $items->take(10)->get();
        }

        return response("No search term given", 500);
    }


    /**
     * Handle Form request to add a new Item
     * including photos
     *
     * @param AddItemRequest $request
     * @return static
     */
    public function postAddNew(AddItemRequest $request)
    {
        $item = Item::create([
            'sku' => $request->input('sku'),
            'brand' => $request->input('brand'),
            'name' => $request->input('name'),
            'specification' => $request->input('specification'),
            'company_id' => Auth::user()->company->id
        ]);
        if ($files = $request->file('item_photos')) $item->handleFiles($files);
        if ($item) return $item->load(['photos']);
        return response("Could not create item", 500);
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

    public function getSingle(Item $item)
    {
        $breadcrumbs = [
            ['<i class="fa fa-legal"></i> Items', '/items'],
            [$item->brand . ' - ' . $item->name, '#']
        ];
        if (Gate::allows('edit', $item)) {
            return view('items.single', compact('item', 'breadcrumbs'));
        }

        // Item does not belong to user - why don't we just redirect them back
        return redirect('/items');
    }


}

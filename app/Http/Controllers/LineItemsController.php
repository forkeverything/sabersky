<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class LineItemsController extends Controller
{
    public function putMarkPaid(Request $request)
    {
        $id = $request->input('line_item_id');
        $lineItem = LineItem::find($id);
        if(Gate::allows('po_payments') && Auth::user()->company_id == $lineItem->purchaseRequest->project->company_id){
            $lineItem->paid = true;
            $lineItem->save();
        }
        return redirect()->back();
    }

    public function putMarkDelivered(Request $request)
    {
        $id = $request->input('line_item_id');
        $lineItem = LineItem::find($id);
        if(Gate::allows('po_warehousing') && Auth::user()->company_id == $lineItem->purchaseRequest->project->company_id){
            $lineItem->delivered = true;
            $lineItem->save();
        }
        return redirect()->back();
    }
}

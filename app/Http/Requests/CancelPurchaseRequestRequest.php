<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\PurchaseRequest;
use Illuminate\Support\Facades\Gate;

class CancelPurchaseRequestRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $project = PurchaseRequest::find($this->input('purchase_request_id'))->project;
        return Gate::allows('pr_make') && Gate::allows('view', $project);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}

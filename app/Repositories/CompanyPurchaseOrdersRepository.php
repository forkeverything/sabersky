<?php


namespace App\Repositories;


use App\Company;
use App\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class CompanyPurchaseOrdersRepository extends apiRepository
{

    protected $sortableFields = [
        'number',
        'created_at',
        'vendor_name',
        'user_name',
        'total',
    ];

    protected function setQuery(Company $company)
    {
        return PurchaseOrder::where('purchase_orders.company_id', $company->id)
            ->join('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
            ->join('users', 'purchase_orders.user_id', '=', 'users.id')
            ->join('countries', 'purchase_orders.currency_id', '=' , 'countries.id')
            ->leftJoin('line_items', 'purchase_orders.id', '=', 'line_items.purchase_order_id')
            ->select(DB::raw('
                purchase_orders.*,
                vendors.name AS vendor_name,
                users.name AS user_name,
                countries.name AS currency_country_name,
                countries.currency AS currency_name,
                countries.currency_code,
                countries.currency_symbol,
                COUNT(line_items.purchase_order_id) AS num_line_items
            '))
            ->groupBy('purchase_orders.id');

    }
}
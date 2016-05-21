<?php


namespace App\Utilities;


use App\Company;
use App\Country;

class ReportGenerator
{

    protected $company;
    protected $currency;

    protected static $spendingCategories = [
        'projects', 'employees', 'vendors', 'items'
    ];

    public static function spendings(Company $company, Country $currency, $category)
    {
        if (!in_array($category, self::$spendingCategories)) return response("Report does not exist", 404);

        $generator = new static($company, $currency);

        return $generator->{'spendings' . ucfirst($category)}();
    }

    public function __construct(Company $company, Country $currency)
    {
        $this->company = $company;
        $this->currency = $currency;
    }

    public function spendingsProjects()
    {
        return $this->company->projects()
                             ->leftJoin('purchase_requests', 'projects.id', '=', 'purchase_requests.project_id')
                             ->leftJoin('line_items', 'purchase_requests.id', '=', 'line_items.purchase_request_id')
                             ->leftJoin('purchase_orders', 'line_items.purchase_order_id', '=', 'purchase_orders.id')
                             ->where('purchase_orders.currency_id', '=', $this->currency->id)
                             ->selectRaw('
                                projects.name as project,
                                SUM(IF(line_items.paid = 1,line_items.price * line_items.quantity,0)) as total_cost
                            ')
                             ->groupBy('project')
                             ->pluck('total_cost', 'project');
    }

    public function spendingsEmployees()
    {
        return $this->company->employees()
                             ->leftJoin('purchase_requests', 'users.id', '=', 'purchase_requests.user_id')
                             ->leftJoin('line_items', 'purchase_requests.id', '=', 'line_items.purchase_request_id')
                             ->leftJoin('purchase_orders', 'line_items.purchase_order_id', '=', 'purchase_orders.id')
                             ->where('purchase_orders.currency_id', '=', $this->currency->id)
                             ->selectRaw('
                                users.name as employee,
                                SUM(IF(line_items.paid = 1,line_items.price * line_items.quantity,0)) as total_cost
                            ')
                             ->groupBy('employee')
                             ->pluck('total_cost', 'employee');
    }

    public function spendingsVendors()
    {
        // TODO ::: Remove vendor name accessor when we change it so that you can't link an existing Vendor

        return $this->company->vendors()
            ->leftJoin('companies', 'vendors.linked_company_id', '=', 'companies.id')
            ->leftJoin('purchase_orders', 'vendors.id', '=', 'purchase_orders.vendor_id')
            ->leftJoin('line_items', 'purchase_orders.id', '=', 'line_items.purchase_order_id')
                             ->where('purchase_orders.currency_id', '=', $this->currency->id)
                             ->selectRaw('
                                IF(vendors.linked_company_id IS NOT NULL, companies.name, vendors.name) as vendor,
                                SUM(IF(line_items.paid = 1,line_items.price * line_items.quantity,0)) as total_cost
                            ')
                             ->groupBy('vendor')
                             ->pluck('total_cost', 'vendor');
    }

    public function spendingsItems()
    {
        return $this->company->items()
                             ->leftJoin('purchase_requests', 'items.id', '=', 'purchase_requests.item_id')
                             ->leftJoin('line_items', 'purchase_requests.id', '=', 'line_items.purchase_request_id')
                             ->leftJoin('purchase_orders', 'line_items.purchase_order_id', '=', 'purchase_orders.id')
                             ->where('purchase_orders.currency_id', '=', $this->currency->id)
                             ->selectRaw('
                                items.name as item,
                                SUM(IF(line_items.paid = 1,line_items.price * line_items.quantity,0)) as total_cost
                            ')
                             ->groupBy('item')
                             ->pluck('total_cost', 'item');
    }
}
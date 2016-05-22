<?php


namespace App\Utilities;


use App\Company;
use App\Country;
use App\PurchaseOrder;
use App\Utilities\Traits\DBIntegerDateFilters;

class ReportGenerator
{

    use DBIntegerDateFilters;
    
    protected $company;
    protected $currency;
    protected $query;

    protected static $spendingCategories = [
        'projects', 'employees', 'vendors', 'items'
    ];

    public static function spendings(Company $company, Country $currency)
    {
        $generator = new static($company, $currency);

        $generator->query = PurchaseOrder::where('purchase_orders.company_id', '=', $company->id)
                                         ->where('purchase_orders.currency_id', '=', $currency->id)
                                         ->join('line_items', 'line_items.purchase_order_id', '=', 'purchase_orders.id')
                                         ->join('purchase_requests', 'purchase_requests.id', '=', 'line_items.purchase_request_id');

        return $generator;
    }

    public function __construct(Company $company, Country $currency)
    {
        $this->company = $company;
        $this->currency = $currency;
    }

    public function getCategory($category)
    {
        if (!in_array($category, self::$spendingCategories)) return response("Report does not exist", 404);
        return $this->{'spendings' . ucfirst($category)}();
    }

    public function spendingsProjects()
    {
        return $this->query->rightJoin('projects', 'projects.id', '=', 'purchase_requests.project_id')
                           ->selectRaw('
                                projects.name as project,
                                SUM(IF(line_items.paid = 1,line_items.price * line_items.quantity,0)) as total_cost
                            ')
                           ->groupBy('project')
                           ->pluck('total_cost', 'project');
    }

    public function spendingsEmployees()
    {

        return $this->query->rightJoin('users', 'users.id', '=', 'purchase_requests.user_id')
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

        return $this->query->rightJoin('vendors', 'vendors.id', '=', 'purchase_orders.vendor_id')
                           ->leftJoin('companies', 'vendors.linked_company_id', '=', 'companies.id')
                           ->selectRaw('
                                IF(vendors.linked_company_id IS NOT NULL, companies.name, vendors.name) as vendor,
                                SUM(IF(line_items.paid = 1,line_items.price * line_items.quantity,0)) as total_cost
                            ')
                           ->groupBy('vendor')
                           ->pluck('total_cost', 'vendor');
    }

    public function spendingsItems()
    {

        return $this->query->rightJoin('items', 'purchase_requests.item_id', '=', 'items.id')
                           ->selectRaw('
                                items.name as item,
                                SUM(IF(line_items.paid = 1,line_items.price * line_items.quantity,0)) as total_cost
                            ')
                           ->groupBy('item')
                           ->pluck('total_cost', 'item');
        
    }
}
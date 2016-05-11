<?php


namespace App\Repositories;


use App\Company;
use App\PurchaseOrder;
use Illuminate\Support\Facades\DB;
use ReflectionMethod;

class CompanyPurchaseOrdersRepository extends apiRepository
{
    /**
     * Sortable fields for our results. First
     * value will be the default sort field
     *
     * @var array
     */
    protected $sortableFields = [
        'number',
        'created_at',
        'vendor_name',
        'user_name',
        'total',
        'num_line_items'
    ];

    /**
     * Search these fields when given
     * user performs a search.
     *
     * @var array
     */
    protected $searchableFields = [
        'number',
        'vendor_name',
        'user_name',
        'total'
    ];


    /**
     * Initiate our query
     *
     * @param Company $company
     * @return $this
     */
    protected function setQuery(Company $company)
    {
        return PurchaseOrder::where('purchase_orders.company_id', $company->id)
//            ->where('purchase_orders.id', 2)
                            ->join('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
                            ->join('users', 'purchase_orders.user_id', '=', 'users.id')
                            ->join('countries', 'purchase_orders.currency_id', '=', 'countries.id')
                            ->join('line_items', 'purchase_orders.id', '=', 'line_items.purchase_order_id')
                            ->leftJoin('purchase_order_additional_costs', 'purchase_orders.id', '=', 'purchase_order_additional_costs.purchase_order_id')
                            ->select(DB::raw('
                                purchase_orders.*,
                                vendors.name AS vendor_name,
                                users.name AS user_name,
                                countries.name AS currency_country_name,
                                countries.currency AS currency_name,
                                countries.currency_code,
                                countries.currency_symbol,
                                COUNT(DISTINCT line_items.id) AS num_line_items,
                                COUNT(*) / COUNT(DISTINCT line_items.id) AS num_additional_costs,
                                SUM(line_items.quantity * line_items.price) / IF(purchase_order_additional_costs.id, (COUNT(*) / COUNT(DISTINCT line_items.id)), 1) AS subtotal_query,
                                SUM(line_items.quantity * line_items.price) / IF(purchase_order_additional_costs.id, (COUNT(*) / COUNT(DISTINCT line_items.id)), 1) + 
                                    IF(purchase_order_additional_costs.id, SUM(IF(purchase_order_additional_costs.type = "%", line_items.quantity * line_items.price * purchase_order_additional_costs.amount / 100, 0)), 0) +
                                    IF(purchase_order_additional_costs.id, SUM(IF(purchase_order_additional_costs.type = "%", 0, purchase_order_additional_costs.amount)) / COUNT(DISTINCT line_items.id), 0)
                                    AS total_query 
                            '))
                            ->groupBy('purchase_orders.id');

        /**
         * Because we're joining tables 'line_items' and 'purchase_orders_additional_costs', we multiply the amount of records we get by
         * => amount of records retrieved will be duplicated by = n(line_items) * n(additional_costs)
         * => That means our sums (with additional costs) will be over by a factor of n(additional_costs) OR n(line_items)
         * => Since we will definitely have line_items but not necessarily additional costs - we'll work with line_items and calculate n(additional_costs) implicitly
         * => Assume line_items.id = unique key
         * => To calculate n(additional_costs) = count(*) / COUNT(DISTINCT line_items.id)
         * => Percentages - price * quantity * percent (cost) - Will not be over by any factor because our duplicate records will have 0 when multiplied and won't affect the sum
         * => Just in case someone sees this and wonders: 'why all the trouble?' - It's because, totals needed to be sortable, also the tables would be joined anyway if we used a Append & Accessor in the model
         */
    }

    /**
     * Status filter
     *
     * @param $status
     * @return $this
     */
    public function whereStatus($status)
    {
        $this->{'status'} = ($status === 'pending' || $status === 'approved' || $status === 'rejected' || $status === 'all') ? $status : 'pending';

        switch ($this->status) {
            case 'pending':
                $this->query->where('purchase_orders.status', 'pending');
                break;
            case 'approved':
                $this->query->where('purchase_orders.status', 'approved');
                break;
            case 'rejected':
                $this->query->where('purchase_orders.status', 'rejected');
                break;
            case 'all':
                break;
            default:
                $this->query->where('purchase_orders.status', 'pending');
        }

        return $this;
    }

    /**
     * When we want to find Orders that service at least 1 request for
     * a given Project.
     *
     * @param $projectID
     * @return $this
     */
    public function hasRequestForProject($projectID)
    {
        if (!$projectID) return $this;
        $this->{'project_id'} = $projectID;
        $this->query->whereExists(function ($query) use ($projectID) {
            $query->select(DB::raw(1))
                  ->from('purchase_requests')
                  ->where('project_id', $projectID)
                  ->join('line_items', 'purchase_requests.id', '=', 'line_items.purchase_request_id')
                  ->whereRaw('purchase_orders.id = line_items.purchase_order_id');
        });
        return $this;
    }

    /**
     * Only brings back Orders that have LineItems -> PurchaseRequest -> Item
     * with a given name.
     * 
     * TODO ::: Find faster way w/o joins
     * 
     * @param null $itemBrand
     * @param null $itemName
     * @return $this
     */
    public function filterByItem($brand = null, $name = null, $sku = null)
    {
        $ref = new ReflectionMethod($this, 'filterByItem');
        foreach ($ref->getParameters() as $param) {
            $column = $argName = $param->name;
            if($term = $$argName){
                $this->{'item_' . $column} = $term;
                $this->query->whereExists(function ($query) use ($column, $term) {
                    $query->select(DB::raw(1))
                          ->from('purchase_requests')
                          ->join('items', 'purchase_requests.item_id', '=', 'items.id')
                          ->join('line_items', 'purchase_requests.id', '=', 'line_items.purchase_request_id')
                          ->where('items.' . $column, $term)
                          ->whereRaw('purchase_orders.id = line_items.purchase_order_id');
                });
            }
        }
        return $this;
    }

}
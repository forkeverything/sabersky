<?php


namespace App\Repositories;


use App\Company;
use App\PurchaseOrder;
use Illuminate\Support\Facades\DB;

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
                            ->join('vendors', 'purchase_orders.vendor_id', '=', 'vendors.id')
                            ->join('users', 'purchase_orders.user_id', '=', 'users.id')
                            ->join('countries', 'purchase_orders.currency_id', '=', 'countries.id')
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
        if(! $projectID) return $this;
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

}
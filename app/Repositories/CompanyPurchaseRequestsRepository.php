<?php


namespace App\Repositories;


use App\Company;
use App\PurchaseRequest;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * A Repo that helps us retrieve the
 * Purchase Requests for a Company, with
 * relevant fields and sort / filter
 *
 * Class CompanyPurchaseRequests
 * @package App\Utilities
 */
class CompanyPurchaseRequestsRepository extends apiRepository
{

    /**
     * Current active filter
     *
     * @var
     */
    protected $filter;

    /**
     * Whether we are requesting urgent only
     * @var
     */
    protected $urgent;

    /**
     * The sortable PR fields
     *
     * @var array
     */
    protected $sortableFields = [
        'item_name',
        'due',
        'created_at',
        'quantity',
        'project_name',
        'requester_name'
    ];
    
    /**
     * Takes a Company and finds it's Purchase Requests
     * as well as relevant fields:
     * - PR (id, due, created_at, quantity)
     * - Item (name, id)
     * - Project (name, id)
     * - User as Requester (name, id)
     * @param Company $company
     * @return mixed
     */
    protected function setQuery(Company $company)
    {
        return PurchaseRequest::whereExists(function ($query) use ($company){
            $query->select(DB::raw(1))
                  ->from('projects')
                  ->where('company_id', '=', $company->id)
                  ->whereRaw('purchase_requests.project_id = projects.id');
        })
                              ->join('projects', 'purchase_requests.project_id', '=', 'projects.id')
                              ->join('items', 'purchase_requests.item_id', '=', 'items.id')
                              ->join('users', 'purchase_requests.user_id', '=', 'users.id')
                              ->select(DB::raw('
                                purchase_requests.id,
                                purchase_requests.due,
                                purchase_requests.state,
                                purchase_requests.created_at,
                                purchase_requests.quantity,
                                items.name as item_name,
                                items.specification as item_specification,
                                items.id as item_id,
                                projects.name as project_name,
                                projects.id as project_id,
                                users.name as requester_name,
                                users.id as requester_id
                               '));
    }

    /**
     * Filter Purchase Requests by
     * given Filter (string)
     *
     * @param null $filter
     * @return $this
     */
    public function filterBy($filter = null)
    {
        // Set filter property
        $this->filter = ($filter === 'open' || $filter === 'cancelled' || $filter === 'complete' || $filter === 'all' ) ? $filter: 'open';

        // Filter our results
        switch ($this->filter) {
            case 'open':
                $this->query->where('state', 'open')->where('quantity', '>', 0);
                break;
            case 'cancelled':
                $this->query->where('state', 'cancelled')->where('quantity', '>', 0);
                break;
            case 'complete':
                $this->query->where('quantity', 0);
                break;
            case 'all':
                break;
            default:
                $this->query->where('state', 'open')->where('quantity', '>', 0);
        }


        return $this;
    }

    /**
     * Whether we only want 'urgent' PR's
     *
     * @param int $urgent
     * @return $this
     */
    public function onlyUrgent($urgent = 0)
    {
        $this->urgent = ($urgent == 1) ?: 0;
        if($this->urgent) $this->query->where('urgent', 1);
        return $this;
    }

    
}
<?php


namespace App\Repositories;


use App\Company;
use App\Project;
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
class UserPurchaseRequestsRepository extends apiRepository
{

    /**
     * The sortable PR fields
     *
     * @var array
     */
    protected $sortableFields = [
        'number',
        'item_name',
        'due',
        'created_at',
        'quantity',
        'project_name',
        'requester_name'
    ];

    /**
     * Searchable fields for a PR
     * @var array
     */
    protected $searchableFields = [
        'number',
        'purchase_requests.item_id.items.brand',
        'purchase_requests.item_id.items.name',
        'purchase_requests.user_id.users.name'
    ];

    /**
     * Finds relevant Purchase Requests for the projects that the
     * User is a part of. We join with relevant tables and then
     * select the columns for info, matching & sorting(name)
     *
     * @param User $user
     * @return mixed
     */
    protected function setQuery(User $user)
    {
        return PurchaseRequest::whereIn('project_id', $user->projects->pluck('id'))
                              ->join('projects', 'purchase_requests.project_id', '=', 'projects.id')
                              ->join('items', 'purchase_requests.item_id', '=', 'items.id')
                              ->join('users', 'purchase_requests.user_id', '=', 'users.id')
                              ->select(DB::raw('
                                purchase_requests.*,
                                items.name as item_name,
                                items.id as item_id,
                                projects.name as project_name,
                                projects.id as project_id,
                                users.name as requester_name,
                                users.id as requester_id
                               '));

        /*
        Old Query - fetches all PR for a given Company
        return PurchaseRequest::whereExists(function ($query) use ($company){
            $query->select(DB::raw(1))
                  ->from('projects')
                  ->where('company_id', '=', $company->id)
                  ->whereRaw('purchase_requests.project_id = projects.id');
        })
         */
    }

    /**
     * Fetch Purchase Requests that
     * have the given State (string)
     *
     * @param null $state
     * @return $this
     */
    public function whereState($state)
    {
        // Set filter property
        $this->{'state'} = ($state === 'open' || $state === 'cancelled' || $state === 'complete' || $state === 'all') ? $state : 'open';

        // Filter our results
        switch ($this->state) {
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
     * Retrieving PRs for only a single given Project
     *
     * @param null $projectID
     * @return $this
     */
    public function forProject($projectID)
    {
        if ($projectID) {
            $project = Project::find($projectID);
            $this->{'project'} = $project;
            $this->query->where('project_id', $projectID);
        }
        return $this;
    }

    /**
     * Single function that can perform a filter for a PR's Item using
     * either Item Name, Brand or Both (makes a unique combination)
     *
     * @param null $itemBrand
     * @param null $itemName
     * @return $this
     */
    public function filterByItem($itemBrand = null, $itemName = null)
    {
        foreach (func_get_args() as $index => $term) {

            switch ($index) {
                case 0:
                    if ($term) $this->{'item_brand'} = $term;
                    $column = 'brand';
                    break;
                case 1:
                    if ($term) $this->{'item_name'} = $term;
                    $column = 'name';
                    break;
                default:
                    break;
            }

            if ($term) {
                $this->query->whereExists(function ($query) use ($term, $column) {
                    $query->select(DB::raw(1))
                          ->from('items')
                          ->where($column, $term)
                          ->whereRaw('purchase_requests.item_id = items.id');
                });
            }
        }
        return $this;
    }

    /**
     * Filters by the user_id field for Purchase Requests
     *
     * @param $userID
     * @return $this
     */
    public function byUser($userID)
    {
        if ($userID) {
            $user = User::find($userID);
            $this->{'user'} = User::find($userID);
            $this->query->where('user_id', $userID);
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
        $this->{'urgent'} = ($urgent == 1) ?: 0;
        if ($this->urgent) $this->query->where('urgent', 1);
        return $this;
    }

}
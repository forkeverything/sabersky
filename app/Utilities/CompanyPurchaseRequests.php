<?php


namespace App\Utilities;


use App\Company;
use App\PurchaseRequest;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * A Utility Class that helps us retrieve the
 * Purchase Requests for a Company, with
 * relevant fields and sort / filter
 *
 * Class CompanyPurchaseRequests
 * @package App\Utilities
 */
class CompanyPurchaseRequests
{
    /**
     * The relationship between given User and
     * Purchase Requests. This is what we
     * can apply our queries too.
     *
     * @var
     */
    private $relation;

    /**
     * Current active filter
     *
     * @var
     */
    protected $filter;

    /**
     * Field we're sorting on
     *
     * @var
     */
    protected $sort;

    /**
     * The order we're sorting by
     * 'asc' or 'desc'
     *
     * @var
     */
    protected $order;

    /**
     * Whether we are requesting urgent only
     * @var
     */
    protected $urgent;

    /**
     * To hold the paginated results
     * from our query
     *
     * @var
     */
    protected $paginated;

    /**
     * Static wrapper so we dont have to 'new' it
     * up all the time
     *
     * @param Company $company
     * @return static
     */
    public static function forCompany(Company $company)
    {
        return new static($company);
    }

    /**
     * Build our instance, we need a Company to fetch
     * it's Purchase Requests
     *
     * CompanyPurchaseRequests constructor.
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        // Run our query soon as we build an instance
        $this->relation = $this->setRelation($company);
    }

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
    protected function setRelation(Company $company)
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
        $this->filter = $filter ?: 'open';

        // Filter our results
        switch ($filter) {
            case 'open':
                $this->relation->where('state', 'open');
                break;
            case 'cancelled':
                $this->relation->where('state', 'cancelled');
                break;
            case 'complete':
                $this->relation->where('quantity', 0);
                break;
            case 'all':
                break;
            default:
                $this->relation->where('state', 'open');
        }


        return $this;
    }


    /**
     * Apply a sort to the Purchase Requests
     *
     * @param null $sort
     * @param null $order
     * @return $this
     */
    public function sortOn($sort = null, $order = null)
    {
        $this->order = ($order === 'desc') ? 'desc' : 'asc';
        $validSort = ($sort === 'due' || $sort === 'created_at' || $sort === 'quantity' || $sort === 'item_name' || $sort === 'project_name' || $sort === 'requester_name');
        $this->sort =  $validSort ? $sort : 'item_name';


        $this->relation->orderBy($this->sort, $this->order);


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
        if($this->urgent) $this->relation->where('urgent', 1);
        return $this;
    }

    /**
     * Finally: Fetch Results and paginate it
     * by set number of items per Page
     * @param $itemsPerPage
     * @return $this
     */
    public function paginate($itemsPerPage = 15)
    {
        // Set paginated property to hold our paginated results
        $paginatedObject = $this->paginated = $this->relation->paginate($itemsPerPage);
        // add our custom properties
        $this->addProperties($paginatedObject);

        return $this->paginated;
    }

    /**
     * Wrapper (untested) just in case we don't
     * want to paginate and just retrieve it
     * in one go
     *
     * @return mixed
     */
    public function get()
    {
        $data = $this->relation->get();
        $this->addProperties($data);
        return $data;
    }

    /**
     * Attaches this object's properties
     * to the object of results we're
     * returning
     *
     * @param $object
     */
    protected function addProperties($object)
    {
        if($object instanceof LengthAwarePaginator || $object instanceof  Collection) {
            $object['filter'] = $this->filter;
            $object['sort'] = $this->sort;
            $object['order'] = $this->order;
            $object['urgent'] = $this->urgent;
        }
    }




}
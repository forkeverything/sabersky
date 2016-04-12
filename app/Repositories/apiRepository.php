<?php


namespace App\Repositories;


use App\Company;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

abstract class apiRepository
{

    /**
     * Paginated Object that holds
     * paginated details as well
     * as the paginated data
     *
     * @var
     */
    protected $paginated;

    /**
     * Relationship Instance to run our queries
     * against
     *
     * @var
     */
    protected $query;

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
     * Model fields that are sortable. The
     * first given field will be the
     * default sort
     * 
     * @var
     */
    protected $sortableFields = [];
    

    /**
     * Static wrapper so we dont have to 'new' it.
     * Can only retrieve result for a single
     * specific company at any time
     *
     * @param Company $company
     * @return static
     */
    public static function forCompany(Company $company)
    {
        return new static($company);
    }

    /**
     * Company Items constructor
     *
     * @param Company $company
     */
    public function __construct(Company $company)
    {
        // Run initial query
        $this->query = $this->setQuery($company);
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
        $this->sort =  in_array($sort, $this->sortableFields) ? $sort : $this->sortableFields[0];
        $this->query->orderBy($this->sort, $this->order);
        return $this;
    }

    /**
     * Wrapper that lets us eager-load our
     * DB results
     *
     * @return $this
     */
    public function with()
    {
        $arg = func_get_args()[0];
        if(is_string($arg) || is_array($arg)) $this->query->with($arg);
        return $this;
    }


    /**
     * Attaches this object's properties
     * to the object of results we're
     * returning
     *
     * @param $object
     */
    protected function addPropertiesToResults($object)
    {
        // Wheether our results are paginated or just a collection (ie. using get)
        if($object instanceof LengthAwarePaginator || $object instanceof  Collection) {
            // Transfer object properties onto it
            foreach (get_object_vars($this) as $key => $value) {
                if (!($value instanceof LengthAwarePaginator) && ! ($value instanceof Builder)) {
                    $object[$key] = $value;
                }
            }
        }
    }


    /**
     * Finally: Fetch Results and paginate it
     * by set number of items per Page
     * (untested)
     * @param $itemsPerPage
     * @return $this
     */
    public function paginate($itemsPerPage = 8)
    {
        $itemsPerPage = ($itemsPerPage == 8 || $itemsPerPage == 16 || $itemsPerPage == 32) ? $itemsPerPage : 8;
        // Set paginated property to hold our paginated results
        $paginatedObject = $this->paginated = $this->query->paginate($itemsPerPage);
        // add our custom properties
        $this->addPropertiesToResults($paginatedObject);
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
        $data = $this->query->get();
        $this->addPropertiesToResults($data);
        return $data;
    }


}
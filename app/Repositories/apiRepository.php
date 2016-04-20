<?php


namespace App\Repositories;


use App\Company;
use App\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use ReflectionClass;
use ReflectionProperty;

abstract class apiRepository
{
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
     * Paginated Object that holds
     * paginated details as well
     * as the paginated data
     *
     * @var
     */
    protected $paginated;
    

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
     * Similar to forCompany except in this case, we will be
     * retrieving data for the given User. Usually for User
     * specific data ie. projects based etc.
     *
     * @param User $user
     * @return static
     */
    public static function forUser(User $user)
    {
        return new static($user);
    }

    /**
     * Company Items constructor
     */
    public function __construct()
    {
        // Run initial query
        $this->query = $this->setQuery(func_get_arg(0));
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


    /**
     * A filter function for a column who's type is Integer. The
     * limits are all inclusive (ie. ... or equal to). We also
     * set the property by combining column name & '_filter'
     *
     * @param $column
     * @param $range
     * @return $this
     */
    public function filterIntegerField($column, $range = null)
    {
        // Create property dynamically with {} !
        $this->{$column.'_integer_filter'} = $rangeArray = $range ? explode(' ', $range) : null;
        $min = count($rangeArray) > 0 ? $rangeArray[0] : null;
        $max =  count($rangeArray) > 1 ? $rangeArray[1] : null;

        if($min && $max) {
            // Yes Min - No Max
            $this->query->whereBetween($column, $rangeArray);
        } else if($min && ! $max) {
            // Yes Min - Yes Max
            $this->query->where($column, '>=', $min);
        } else if(! $min && $max) {
            // No Min - Yes Max
            $this->query->where($column, '<=', $max);
        }

        // No Min, No max, no nothing
        return $this;
    }


}
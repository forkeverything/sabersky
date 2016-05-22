<?php


namespace App\Repositories;


use App\Company;
use App\User;
use App\Utilities\Traits\DBIntegerDateFilters;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use ReflectionClass;
use ReflectionProperty;

abstract class apiRepository
{

    use DBIntegerDateFilters;
    
    /**
     * Relationship Instance to run our queries
     * against
     *
     * @var
     */
    protected $query;

    /**
     * Model fields that are sortable. The
     * first given field will be the
     * default sort
     *
     * @var
     */
    protected $sortableFields = [];

    /**
     * Model fields that we can perform searches on, Accepts
     *  a string to search relationship table fields:
     * 'parent_table_name.child_table_name.child_table_column'
     * @var array
     */
    protected $searchableFields = [];

    protected $queryParameters;


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
        $this->{'order'} = ($order === 'desc') ? 'desc' : 'asc';
        $this->{'sort'} = in_array($sort, $this->sortableFields) ? $sort : $this->sortableFields[0];
        $this->query->orderBy($this->sort, $this->order);
        return $this;
    }

    /**
     * Wrapper for method on Query Builder that lets
     * us eager load our database relationships.
     *
     * @return $this
     */
    public function with()
    {
        $arg = func_get_args()[0];
        if (is_string($arg) || is_array($arg)) $this->query->with($arg);
        return $this;
    }

    /**
     * Wrapper - Used to only select certain
     * fields
     * 
     * @param $fields
     * @return $this
     */
    public function select($fields)
    {
        $this->query->select($fields);
        return $this;
    }


    /**
     * Wrapper - for having() on Query Builder. Just in case I forget, having can be used
     * on aggregates (SUM, COUNT, etc...) - WHERE can't be used on those.
     *
     * @param $column
     * @param $operator
     * @param $value
     * @return $this
     */
    public function having($column, $operator, $value)
    {
        $this->query->having($column, $operator, $value);
        return $this;
    }


    /**
     * Attaches this object's properties
     * to the results object that is
     * returned to Client
     *
     * @param $object
     */
    protected function addPropertiesToResults($object)
    {
        // Whether our results are paginated or just a collection (ie. using get())
        if ($object instanceof LengthAwarePaginator || $object instanceof Collection) {
            // Transfer object properties onto it
            foreach (get_object_vars($this) as $key => $value) {
                if (!($value instanceof LengthAwarePaginator) && !($value instanceof Builder)) {
                    if ($key !== 'sortableFields' && $key !== 'searchableFields' && $key !== 'queryParameters') $this->queryParameters[$key] = $value;
                }
            }
            $object['query_parameters'] = $this->queryParameters;
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
        // Set paginated property to hold our paginated results
        $paginatedObject = $this->{'paginated'} = $this->query->paginate($itemsPerPage);
        // add our custom properties
        $this->addPropertiesToResults($paginatedObject);
        return $this->paginated;
    }

    /**
     * The usual paginator doesn't take into account aggregates, we need to make our
     * own paginator AFTER doing our SELECT(s) & JOIN(s).
     *
     * @param $perPage
     * @param $currentPage
     * @return LengthAwarePaginator
     */
    public function paginateWithAggregate($perPage, $currentPage)
    {
        $perPage = $perPage ?: 8;
        $currentPage = $currentPage ?: 1;

        $items = $this->query->get();
        $itemsArray = $items->toArray();
        $itemsForCurrentPage = array_splice($itemsArray, ($currentPage - 1) * $perPage, $perPage);

        // Create our Paginator instance - we use LengthAwarePaginator to see how many pages total; otherwise
        // we would be creating a simple pagination (prev/next only)
        $paginator = new LengthAwarePaginator($itemsForCurrentPage, $items->count(), $perPage);

        // Add our params / properties
        $this->addPropertiesToResults($paginator);
        return $paginator;

        /**
         * - We can't use $query->forPage() here because then we wouldn't know how many records match our select
         * - We can't run a separate query just to get the count because then our SELECT(s) & JOIN(s) wouldn't exist
         * - If we did use the same selects/joins, then we'd just be running 2 queries anyway, one to retrieve the whole set
         *   and once again to retrieve a subset using forPage()
         *
         * Reasoning: Since we're filtering (hopefully) the data set won't be THAT large.
         *
         * Considerations: Maybe if queries are slow then we need to settle for simple pagination (not length aware)
         * where we'll just have previous and next. Meaning we won't need to know the count.
         *
         * TODO ::: Find better way to be able to filter aggregates using HAVING and still be able to paginate
         * the results.
         */

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
     * Wrapper - Method on Query Builder that removes
     * duplicates from retrieved results set.
     *
     * @return $this
     */
    public function distinct()
    {
        $this->query->distinct();
        return $this;
    }

    /**
     * Wrapper - limit number of results for the
     * query
     *
     * @param $limit
     * @return $this
     */
    public function take($limit)
    {
        $this->query->take($limit);
        return $this;
    }

    /**
     * Just a get() wrapper for the Query Builder. This is
     * used for testing because we don't need to know the
     * Query Properties used (for client).
     *
     * @return mixed
     */
    public function getWithoutQueryProperties()
    {
        return $this->query->get();
    }


    /**
     * Search function that searches a target table's fields
     * as well as any directly related tables
     *
     * @param $term
     * @return $this
     */
    public function searchFor($term, $searchFieldsArray = null)
    {
        if ($term) {
            $this->{'search'} = $term;
            // If one-time search fields defined, use them
            if($searchFieldsArray) $this->searchableFields = $searchFieldsArray;
            // perform search
            $this->query->where(function ($query) use ($term) {
                foreach ($this->searchableFields as $index => $field) {
                    $fieldsArray = explode('.', $field);

                    if(count($fieldsArray) === 1 ) {
                        $this->searchQueryDirect($query, $fieldsArray[0], $term, $index);
                    } else {
                        $this->searchQueryRelated($query, $fieldsArray, $term, $index);
                    }
                }
                return $query;

            });
        }
        return $this;
    }

    /**
     * Performs a search query on a field directly related
     * to the Model
     *
     * @param Builder $query
     * @param $field
     * @param $term
     * @param $index
     * @return mixed
     */
    protected function searchQueryDirect(Builder $query, $field, $term, $index)
    {
        $funcName = $index === 0 ? 'where' : 'orWhere';
        return call_user_func([$query, $funcName], $field, 'LIKE', '%' . $term . '%');
    }

    /**
     * Performs search on field that is on a related table. As
     * defined in the $searchableFields[] on the model.
     * 
     * @param Builder $query
     * @param $fieldArray
     * @param $term
     * @param $index
     * @return mixed
     */
    protected function searchQueryRelated(Builder $query, $fieldArray, $term, $index)
    {
        $funcName = $index === 0 ? 'whereExists' : 'orWhereExists';
        $callback = function ($q) use ($fieldArray, $term) {
            $q->select(DB::raw(1))
              ->from(($fieldArray[2]))
              ->whereRaw($fieldArray[0] . '.' . $fieldArray[1] . '=' . $fieldArray[2] . '.id')
              ->where($fieldArray[3], 'LIKE', '%' . $term . '%');
        };
        return call_user_func([$query, $funcName], $callback);
    }

    /**
     * Filters by the user_id field for the model
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
    


}
<?php


namespace App\Repositories;


use App\Project;
use App\Repositories\apiRepository;
use App\Company;
use App\Item;
use Illuminate\Support\Facades\DB;

class CompanyItemsRepository extends apiRepository
{



    /**
     * The sortable Item fields
     *
     * @var array
     */
    protected $sortableFields = [
        'name',
        'sku'
    ];

    
    /**
     * Sets the original DB query for Items
     *
     * @param Company $company
     * @return mixed
     */
    protected function setQuery(Company $company)
    {

        return Item::where('company_id', $company->id);
    }

    /**
     * Selects only Items with a given brand
     *
     * @param null $brand
     * @return $this
     */
    public function withBrand($brand = null)
    {
        if ($brand) {
            $this->{'brand'} = $brand;
            $this->query->where('brand', $brand);
        }
        return $this;
    }


    /**
     * Filters Item by it's Name value
     * @param null $name
     * @return $this
     */
    public function withName($name = null)
    {
        if ($name) {
            $this->{'name'} = $name;
            $this->query->where('name', $name);
        }
        return $this;
    }

    /**
     * Returns Items that belong to a Project with
     * the given Project ID
     *
     * @param null $projectID
     * @return $this
     */
    public function forProject($projectID = null)
    {
        // If we have a Project ID and it is an Integer
        if ($projectID && is_int((int)$projectID)) {
            $project = Project::find($projectID);
            $this->{'project'} = $project;
            $this->query->whereExists(function ($query) use ($projectID) {
                $query->select(DB::raw(1))
                      ->from('purchase_requests')
                      ->where('project_id', '=', $projectID)
                      ->whereRaw('items.id = purchase_requests.item_id');
            });
        }

        return $this;
    }
    


}
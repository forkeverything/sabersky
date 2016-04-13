<?php


namespace App\Repositories;


use App\Repositories\apiRepository;
use App\Company;
use App\Item;
use Illuminate\Support\Facades\DB;

class CompanyItemsRepository extends apiRepository
{

    /**
     * Brand filter - showing only
     * items with this brand
     * @var
     */
    protected $brand;

    /**
     * Project Filter- showing only
     * items that have this project
     * @var
     */
    protected $projectID;

    /**
     * The search term to be compared
     * to find Item SKU, Brand or Name
     * @var
     */
    protected $search;


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
        $this->brand = $brand;
        if ($brand) $this->query->where('brand', $brand);
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
        $this->projectID = is_int((int)$projectID) ? $projectID : null;

        if ($this->projectID) $this->query->whereExists(function ($query) use ($projectID) {
            $query->select(DB::raw(1))
                  ->from('purchase_requests')
                  ->where('project_id', '=', $projectID)
                  ->whereRaw('items.id = purchase_requests.item_id');
        });

        return $this;
    }

    /**
     * Search Item fields: SKU, Brand, Name
     *
     * @param null $search
     * @return $this
     */
    public function searchSkuBrandName($search = null)
    {
        $this->search = $search;
        if ($search) $this->query->where('sku', 'LIKE', '%' . $search . '%')
                                 ->orWhere('brand', 'LIKE', '%' . $search . '%')
                                 ->orWhere('items.name', 'LIKE', '%' . $search . '%');
        return $this;
    }



}
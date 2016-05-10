<?php


namespace App\Repositories;


use App\Company;
use App\Vendor;

class CompanyVendorsRepository extends apiRepository
{

    /**
     * Init our query - We're only interested in Vendors
     * that belong to the given Companyz
     * 
     * @param Company $company
     * @return mixed
     */
    protected function setQuery(Company $company)
    {
        return Vendor::where('base_company_id', $company->id);
    }

    /**
     * Searchable fields for a Vendor
     * @var array
     */
    protected $searchableFields = [
        'name'
    ];
    
    
}
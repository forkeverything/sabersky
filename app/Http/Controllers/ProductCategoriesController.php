<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use App\ProductSubcategory;
use Illuminate\Http\Request;

use App\Http\Requests;

class ProductCategoriesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
    }

    /**
     * Returns a list of ALL Product Categories
     * 
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCategories()
    {
        return ProductCategory::all();
    }

    /**
     * Retrieves all Subcategories for a given parent category
     *
     * @param ProductCategory $productCategory
     * @return mixed
     */
    public function getSubcategories(ProductCategory $productCategory)
    {
        return $productCategory->subcategories()->get(['id', 'label']);
    }

    /**
     * Performs a search for a Subcategory
     *
     * @param $term
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function getSearchSubcategories($term)
    {
        if ($term) {
            return ProductSubcategory::where('label', 'LIKE', '%' . $term . '%')->get(['id', 'label']);
        }
        return response("No search search term provided to find Product Subcategory", 500);
    }

}

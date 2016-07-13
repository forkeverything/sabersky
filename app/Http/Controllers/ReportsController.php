<?php

namespace App\Http\Controllers;

use App\Company;
use App\Country;
use App\Utilities\ReportGenerator;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('company');
        $this->middleware('reports.view');
    }

    /**
     * Show the Reports Menu
     *
     * @return mixed
     */
    public function getMenu()
    {
        $breadcrumbs = [
            ['<i class="fa fa-cogs"></i> Reports', '#']
        ];
        return view('reports.menu', compact('breadcrumbs'));
    }

    /**
     * Get Spendings Report for a specific category
     *
     * @param $category
     * @return mixed
     */
    public function getSpendingsReport($category)
    {

        $breadcrumbs = [
            ['<i class="fa fa-cogs"></i> Reports', '/reports'],
            ['Spendings', '#'],
            [ucfirst($category), '#']
        ];
        return view('reports.spendings.' . strtolower($category), compact('breadcrumbs'));
    }

    
    /**
     * @param $category
     * @param Country $currency
     * @return mixed
     */
    public function getSpendingsData($category, Country $currency)
    {
        return ReportGenerator::spendings(Auth::user()->company, $currency)
                              ->filterDateField('purchase_orders.created_at', request('date'))
                              ->getCategory($category);
    }
}

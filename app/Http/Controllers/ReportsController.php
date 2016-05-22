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
    }

    public function getSpendings()
    {
        $breadcrumbs = [
            ['<i class="fa fa-cogs"></i> Reports', '#'],
            ['Spendings', '#']
        ];
        return view('reports.spendings', compact('breadcrumbs'));
    }

    public function getSpendingsData($category, Country $currency)
    {
        return ReportGenerator::spendings(Auth::user()->company, $currency)
                              ->filterDateField('purchase_orders.created_at', request('date'))
                              ->getCategory($category);
    }
}

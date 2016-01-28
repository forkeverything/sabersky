<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function showDashboard()
    {
        if(! Auth::user()->company){
            return redirect('company');
        }

        switch (Auth::user()->roles()->first()->position) {
            case 'director':
                return view('dashboards.director');
                break;
            case 'planner':
                return view('dashboards.planner');
                break;
            case 'manager':
                return view('dashboards.manager');
                break;
            case 'buyer':
                return view('dashboards.buyer');
                break;
            case 'cashier':
                return view('dashboards.cashier');
                break;
            case 'technician':
                return view('dashboards.technician');
                break;
            default:
                return 'no role. please contact system administrator to assign role manually.';
        }
    }
}

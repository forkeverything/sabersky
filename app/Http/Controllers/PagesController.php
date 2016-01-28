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

        switch (Auth::user()->role->position) {
            case 'director':
                return view('dashboard.director');
                break;
            case 'planner':
                return view('dashboard.planner');
                break;
            case 'manager':
                return view('dashboard.manager');
                break;
            case 'buyer':
                return view('dashboard.buyer');
                break;
            case 'cashier':
                return view('dashboard.cashier');
                break;
            case 'technician':
                return view('dashboard.technician');
                break;
            default:
                return 'no role. please contact system administrator to assign role manually.';
        }
    }
}

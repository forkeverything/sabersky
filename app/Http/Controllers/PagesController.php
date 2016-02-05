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
        $this->middleware('company');
    }

    public function showDashboard()
    {
        return view('dashboard');
    }

    public function showDesk()
    {
        $projects = Auth::user()->projects;
        return view('desk');
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function __construct()
    {
        $signedIn = Auth::check();
        view()->share('signedIn', $signedIn);
        if($signedIn) {
            $companySettings = Auth::user()->company->settings;
            view()->share('companyCurrencySymbol', $companySettings->currency->symbol);
            view()->share('companyCurrencyDecimalPoints', $companySettings->currency_decimal_points);
        }
    }
}

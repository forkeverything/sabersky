<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class RulesController extends Controller
{

    /**
     * Deprecated - provide static data serverside.
     */
//    public function getPropertiesTriggers()
//    {
//        $properties = collect(
//            DB::table('properties')
//                ->select('*')
//                ->get());
//        $triggers = collect(
//            DB::table('triggers')
//                ->select('*')
//                ->get());
//       return [
//            'properties' => $properties,
//            'triggers' => $triggers
//        ];
//    }
}

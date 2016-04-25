<?php

namespace App\Http\Controllers;

use App\Country;
use DougSisk\CountryState\CountryState;
use Illuminate\Http\Request;

use App\Http\Requests;

class CountriesController extends Controller
{
    /**
     * Returns a list of all the Countries in the DB
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAll()
    {
        return Country::all()->pluck('name', 'id');
    }

    /**
     * Search for a Country based on it's Name and returns
     * an array of objects with the Country ID and name
     *
     * @param $query
     * @return mixed
     */
    public function getSearchCountry($query)
    {
        if ($query) {
            return Country::where('name', 'LIKE', '%' . $query . '%')->get(['id', 'name']);
        }
        return response("No search query provided to find Country", 500);
    }

    /**
     * Takes a Country's ID and finds it's record then looks
     * up the Country's states and returns them.
     *
     * @param Country $country
     * @return mixed
     */
    public function getStates(Country $country)
    {
        $countryState = new CountryState();
        $states = $countryState->getStates($country->iso_3166_2);
        $statesArray = [];
        foreach ($states as $state) {
            array_push($statesArray, [
                "name" => $state
            ]);
        }

        return $statesArray;
    }
}

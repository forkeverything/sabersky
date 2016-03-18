<?php

use Vinkla\Hashids\Facades\Hashids;

function flash($message = null)
{
    $flash = app(App\Utilities\Flash::class);

    /*
     * If no arguments provided, return an
     * instance of the flash object.
     */
    if (func_num_args() == 0) {
        return $flash;
    }

    return $flash->info($message);
}

/**
 * Encodes an id using Hashids package.
 *
 * @param $id
 * @return mixed
 */
function encode($id){
    return Hashids::encode($id);
}

/**
 * Decodes id using Hashids package.
 * 
 * @param $id
 * @return mixed
 */
function decode($id){
    return Hashids::decode($id);
}

/**
 * Returns a collection of system rules' properties & triggers
 * used to determine approval requirements for any purchase
 * order made through the procurement management system.
 *
 * @return \Illuminate\Support\Collection
 */
function getRuleProperties()
{
    $properties = collect(
        DB::table('rule_properties')
            ->select('*')
            ->get());

    // Create triggers property with an empty array
    foreach ($properties as $property) {
        $property->triggers = [];
    }

    $triggers = collect(
        DB::table('rule_triggers')
            ->select('*')
            ->get());


    foreach ($triggers as $trigger) {
        $parentProperty = $properties->where('id', $trigger->rule_property_id)->first();
        array_push($parentProperty->triggers, $trigger);
    }


    return $properties;
}
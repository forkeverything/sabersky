<?php
use Vinkla\Hashids\Facades\Hashids;

/**
 * Helper function that returns an instance of the
 * Flash class used to store flash messages in
 * the session for the client's next req.
 * 
 * @param null $message
 * @return \Illuminate\Foundation\Application|mixed
 */
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

/**
 * Checks whether given String is a Date
 * with the format: YYYY-MM-DD
 * 
 * @param $string
 * @return int
 */
function isDate($string)
{
    return !! preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $string);
}

/**
 * takes a snake_case_string and turns it into a CamelCaseString
 * , with first letter capitalized.
 * @param $str
 * @return mixed
 */
function str_snake_to_camel($str)
{
    return str_replace(' ', '', ucwords(str_replace('_', ' ', $str)));
}

/**
 * Takes any 'string' and convert it to snake_case
 *
 * UNTESTED
 * 
 * @param $string
 * @return string
 */
function str_to_snake($string) {
    return strtolower(preg_replace(['/([a-z\d])([A-Z])/', '/([^_])([A-Z][a-z])/'], '$1_$2', $string));
}


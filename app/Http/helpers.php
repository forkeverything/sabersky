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
 * @param $id
 * @return mixed
 */
function decode($id){
    return Hashids::decode($id);
}


<?php

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

function add_photo_path(Flyer $flyer)
{
    return route('store_photo_path', [$flyer->zip, $flyer->street]);
}

/**
 * Path to a given flyer
 * @param Flyer $flyer
 * @return string
 */
function flyer_path(Flyer $flyer)
{
    return $flyer->zip . '/' . str_replace(' ', '-', $flyer->street);
}


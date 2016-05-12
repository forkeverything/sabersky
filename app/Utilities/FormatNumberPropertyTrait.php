<?php


namespace App\Utilities;


trait FormatNumberPropertyTrait
{
    /**
     * Some easy formatting so that the sequential
     * Purchase Request Number of a Company has
     * at least 3 digits.
     *
     * @param $value
     * @return string
     */
    public function getNumberAttribute($value)
    {
        switch (strlen($value)) {
            case 1:
                return '00' . $value;
                break;
            case 2:
                return '0' . $value;
                break;
            default:
                return $value;
        }
    }
}
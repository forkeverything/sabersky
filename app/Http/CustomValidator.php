<?php

use Illuminate\Validation\Validator as Validator;

class CustomValidator extends Validator {
    protected function validateLineItemQuantity($attribute, $value, $parameters)
    {
        
    }
}


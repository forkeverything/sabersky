<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'address',
        'bank_account_name',
        'bank_account_number',
        'bank_name',
        'company_id'
    ];
}

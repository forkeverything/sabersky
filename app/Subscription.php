<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends \Laravel\Cashier\Subscription
{
    /**
     * Over-write User relationship to use Company
     */
    public function user()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Company
 *
 */
class Company extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function employees()
    {
        return $this->hasMany(User::class);
    }
}

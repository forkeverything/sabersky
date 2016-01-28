<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name',
        'location',
        'description',
        'operational',
        'company_id'
    ];
}

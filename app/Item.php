<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'specification',
        'project_id'
    ];

    public function getNameAttribute($property)
    {
        return ucfirst($property);
    }
}

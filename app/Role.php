<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    protected $fillable = [
            'position'
    ];

    // A role has many users
    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }
}

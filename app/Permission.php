<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Permission
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $label
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 */
class Permission extends Model
{

    protected $fillable = [
        'name',
        'label'
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}

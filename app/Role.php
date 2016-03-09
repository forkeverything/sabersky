<?php

namespace App;

use App\Company;
use App\Permission;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Role
 *
 * @property integer $id
 * @property string $position
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Permission[] $permissions
 * @property integer $company_id
 */
class Role extends Model
{

    protected $fillable = [
        'position',
        'company_id'
    ];


    public $timestamps = false;

    // A role has many users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    public function givePermissionTo(Permission $permission)
    {
        return $this->permissions()->save($permission);
    }

    /**
     * Assigns an admin role it's permissions.
     *
     * @return $this
     */
    public function giveAdminPermissions()
    {
        if($this->position === 'admin') {
            foreach (Permission::all() as $permission) {
                $this->givePermissionTo($permission);
            }
            return $this;
        }
        abort(405, 'Role given was not admin, cannot assign all permissions.');
    }

    /**
     * A role belongs to a company
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function setPositionAttribute($position) {
        $this->attributes['position'] = strtolower($position);
    }


}

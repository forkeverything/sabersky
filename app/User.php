<?php

namespace App;

use App\Company;
use App\Role;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * App\User
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $company_id
 * @property integer $role_id
 * @property-read \App\Role $role
 * @property-read \App\Company $company
 * @property string $invite_key
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Project[] $projects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PurchaseOrder[] $purchaseOrders
 */
class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'invite_key',
        'company_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Every user has a role / position.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * A user (employee) belongs to a single
     * company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Assign a role to a user.
     *
     * @param $role
     */
    public function assignRole($role)
    {
        $this->role()->save(
            Role::wherePosition($role)->firstOrFail()
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    /**
     * Gets a user object from a the provided
     * 'invite_key'
     *
     * @param $inviteKey
     * @return mixed
     */
    public static function fetchFromInviteKey($inviteKey)
    {
        return self::whereInviteKey($inviteKey)->first();
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function setRole($role) {
        $this->role_id = $role->id;
        $this->save();
        return $this;
    }

}

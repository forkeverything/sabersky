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
 * @method static \Illuminate\Database\Query\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereInviteKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\User whereRoleId($value)
 * @mixin \Eloquent
 */
class User extends Authenticatable
{
    /**
     * Mass-assignable fields for
     * a User
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'bio',
        'password',
        'role_id',
        'invite_key',
        'active',
        'company_id',
        'last_login'
    ];

    /**
     * Automatically append these dynamic attributes
     *
     * @var array
     */
    protected $appends = [
        'num_requests',
        'num_orders'
    ];


    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    /**
     * Always eager-load these relationships
     *
     * @var array
     */
    protected $with = [
        'photo'
    ];

    /**
     * If User has an invite_key we assume
     * that they have NOT accepted the
     * invitation and is 'pending'
     * @return string
     */
    public function isPending()
    {
        return $this->invite_key;
    }

    /**
     * Attribute - Number of PRs
     *
     * @return mixed
     */
    public function getNumRequestsAttribute()
    {
        return $this->purchaseRequests->count();
    }

    /**
     * Attribtue - Number of POs
     * @return int
     */
    public function getNumOrdersAttribute()
    {
        return $this->purchaseOrders->count();
    }

    /**
     * A User can make many requests
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    /**
     * A User has a single photo (profile)
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne
     */
    public function photo()
    {
        return $this->morphOne(Photo::class, 'model');
    }

    /**
     * Toggles User active / deactive
     *
     * @return bool
     */
    public function toggleActive()
    {
        $this->active = ! $this->active;
        return $this->save();
    }

    /**
     * Makes a new User from name(string),
     * email(string) & password(string)
     *
     * @param $name
     * @param $email
     * @param $password
     * @return static
     */
    public static function make($name, $email, $password = null, $roleId = null, $makeInviteKey = false)
    {
        $inviteKey = ($makeInviteKey) ? str_random(13) : null;
        $password = $password ? bcrypt($password) : null;
        return static::create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role_id' => $roleId,
            'invite_key' => $inviteKey
        ]);
    }

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

    /**
     * A User can create many POs
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Wrapper that sets a User's
     * Role to the one given.
     *
     * @param $role
     * @return $this
     */
    public function setRole(Role $role) {
        $this->role_id = $role->id;
        $this->save();
        return $this;
    }

    /**
     * Takes a string and sets it
     * as the new Password
     *
     * @param $newPassword
     * @return $this
     */
    public function setPassword($newPassword)
    {
        if(! is_string($newPassword)) abort(500, "Password must be string");
        $this->password = bcrypt($newPassword);
        $this->save();
        return $this;
    }

    /**
     * Clears the invite-key field.
     *
     * @return $this
     */
    public function clearInviteKey()
    {
        $this->invite_key = null;
        $this->save();
        return $this;
    }

    /**
     * Check if a User has the given role
     *
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return $this->role->position === $role;
    }

    /**
     * Records an activity for a related Model by this User. This is a wrapper
     * that defers the the method on the trait. But by calling it through User
     * we get access to the User's ID.
     *
     * @param $name
     * @param $related
     * @return mixed
     * @throws \Exception
     */
    public function recordActivity($name, $related)
    {
        // Make sure the model we want to record has recordActivity() from RecordsActivity traits
        if (! method_exists($related, 'recordActivity')) {
            throw new \Exception('Trying to record activity for an invalid model');
        }
        
        return $related->recordActivity($name, $this);
    }




}

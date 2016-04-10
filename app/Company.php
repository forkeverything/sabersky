<?php

namespace App;

use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\StartProjectRequest;
use App\Role;
use App\User;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Company
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $description
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $employees
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Project[] $projects
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vendor[] $vendors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property string $currency
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Rule[] $rules
 */
class Company extends Model
{

    /**
     * Mass-Fillable fields for a company
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'currency'
    ];


    /**
     * Takes a name ('string') and creates
     * a new Company in DB
     *
     * @param $name
     * @return static
     */
    public static function register($name)
    {
        return static::create([
            'name' => $name
        ]);
    }

    /**
     * Company has many Employees (Users).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Adds an Employee (User) to a
     * company.
     *
     * @param \App\User $user
     * @return $this
     */
    public function addEmployee(User $user)
    {
        $this->employees()->save($user);
        return $this;
    }


    /**
     * A company can have many Projects.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function projects()
    {
        return $this->hasMany(Project::class);
    }

    /**
     * Company has many Purchase Requests THROUGH the projects
     * that it has.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function purchaseRequests()
    {
        return $this->hasManyThrough(PurchaseRequest::class, Project::class);
    }

    /**
     * A company has many items that it has purchased.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
//        $itemsArray = [];
//        foreach ($this->projects as $project) {
//            array_push($itemsArray, $project->items->load('projects', 'photos')->all());
//        }
//        $itemsCollection = collect(array_flatten($itemsArray));
//
//        return $itemsCollection;
        
        // Established Relationship (allows us to create Items w/o PR)
        return $this->hasMany(Item::class);

    }

    public function getVendorsAttribute()
    {
        $vendorsArray = [];
        foreach ($this->purchaseOrders as $purchaseOrder) {
            array_push($vendorsArray, $purchaseOrder->vendor);
        }
        $vendorCollection = collect($vendorsArray)->unique('id')->reject(function ($value, $key) {
            return empty($value);
        });
        return $vendorCollection;
    }

    public function purchaseOrders()
    {
        return $this->hasManyThrough(PurchaseOrder::class, Project::class);
    }

    public function company()
    {
        return $this->projects()->first()->company;
    }

    /**
     * A Company has many Roles that it's
     * staff can take
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles() {
        return $this->hasMany(Role::class);
    }

    /**
     * Returns all Company's roles, removing
     * Admin
     * 
     * @return static
     */
    public function getRolesNotAdmin()
    {
        return $this->roles->reject(function($role) {
            return $role->position === 'admin';
        });
    }

    /**
     * Creates a admin role for a company
     * (if one doesn't already exist).
     *
     * @return Model
     */
    public function createAdmin() {
        if(! $this->roles->contains('position', 'admin')) {
            return $this->roles()->create([
                'position' => 'admin',
            ]);
        }
        abort(403, 'Already have an admin');
    }

    /**
     * A company can have many rules for it's
     * purchase orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rules()
    {
        return $this->hasMany(Rule::class);
    }

    /**
     * Creates a new project for the company. The user
     * is the logged user who created the project.
     *
     * @param StartProjectRequest $request
     * @param $user
     * @return $this
     */
    public function startProject(StartProjectRequest $request, $user)
    {
        $project = $this->projects()->create($request->all());
        $user->projects()->save($project);  // Add project to user projects
        return $this;
    }

    /**
     * Retrieves Public Profile for a company.
     * Can be called using id or name.
     * @return mixed
     */
    public static function fetchPublicProfile()
    {
        $parameter = func_get_args()[0];

        // public attributes
        $attributes = [
            'name',
            'description'
        ];

        $company = is_numeric($parameter) ? static::select($attributes)->find($parameter) : static::whereName($parameter)->first($attributes);
        return $company;
    }

}

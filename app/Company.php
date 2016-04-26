<?php

namespace App;

use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\StartProjectRequest;
use App\Role;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    protected $appends = [
        'connection'
    ];

    public function getConnectionAttribute()
    {
        if (Auth::check()) {
            $userCompany = Auth::user()->company;

            $verified = DB::table('vendors')->select(DB::raw(1))
                ->where('base_company_id', $userCompany->id)
                ->where('linked_company_id', $this->id)
                ->where('verified', 1)
                ->get();

            if($verified) return 'verified';

            $pending = DB::table('vendors')->select(DB::raw(1))
                         ->where('base_company_id', $userCompany->id)
                         ->where('linked_company_id', $this->id)
                         ->where('verified', 0)
                         ->get();

            if($pending) return 'pending';

        }
        return 'Can\'t determine connection without logged User Company';
    }


    /**
     * A Company can only have one address
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->morphOne(Address::class, 'owner');
    }


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
     * Company has one record from the statistics
     * table which hold many different stats
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function statistics()
    {
        return $this->hasOne(CompanyStatistics::class);
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
        // Established Relationship (allows us to create Items w/o PR)
        return $this->hasMany(Item::class);

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

    /**
     * A Company can have many Vendor models.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function vendors()
    {
        return $this->hasMany(Vendor::class, 'base_company_id');
    }


    /**
     * Companies that have linked their Vendor model to this
     * Company. We will also only retrieve the requests
     * that have been verified.
     *
     * @return $this
     */
    public function customers()
    {
        return $this->belongsToMany(Company::class, 'vendors', 'linked_company_id', 'base_company_id')
            ->wherePivot('verified' , '=', 1)
            ->withPivot('verified');
    }

    /**
     * Returns the Companies that this Company's Vendor models
     * are linked to.
     *
     * @return $this
     */
    public function linkedAsVendorCompanies()
    {
        return $this->belongsToMany(Company::class, 'vendors', 'base_company_id', 'linked_company_id')
                    ->wherePivot('verified' , '=', 1)
                    ->withPivot('verified');
    }

    /**
     * Connects are both: Companies that our Vendors are linked
     * to, as well as the Companies that have us linked to a
     * Vendor model.
     *
     * @return mixed
     */
    public function getConnectsAttribute()
    {
        /*
        Accessor to see if the relationship has been loaded and loads
        it if it hasn't. Usually this would be a Eloquent relation
        but instead we are including our inverted relationship.
         */

        // If we haven't loaded our connects - load it up
        if( ! array_key_exists('connects', $this->relations)) $this->loadConnects();
        // And return it
        return $this->getRelation("connects");
    }

    /**
     * Sets a dynamic relation 'connects' to the Company model
     */
    protected function loadConnects()
    {
        // only if we have NOT loaded it yet...
        if (!array_key_exists('connects', $this->relations)) {

            // Call the function that merges two way many-to-many relations
            $connects = $this->mergeConnects();

            // Set the relation to be retrieved by getRelation()
            $this->setRelation('connects', $connects);
        }
    }

    /**
     * This function just merges the 2 collections together using the
     * merge() method on the collections. We merge because we need
     * to retrieve all connects regardless of who initiated it.
     *
     * @return mixed
     */
    protected function mergeConnects()
    {
        return $this->customers->merge($this->linkedAsVendorCompanies);
    }




}

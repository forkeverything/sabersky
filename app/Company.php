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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Rule[] $rules
 * @property-read mixed $connection
 * @property-read \App\Address $address
 * @property-read \App\CompanyStatistics $statistics
 * @property-read \App\CompanySettings $settings
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Item[] $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PurchaseOrder[] $purchaseOrders
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vendor[] $vendors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vendor[] $customerVendors
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Company[] $customerCompanies
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Company[] $supplierCompanies
 * @property-read mixed $connects
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Company whereDescription($value)
 * @mixin \Eloquent
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
        'description'
    ];

    protected $appends = [
        'connection',
        'currencies'
    ];

    /**
     * Checks the connection status for logged-user's Company
     * and this Company model
     *
     * @return string
     */
    public function getConnectionAttribute()
    {
        if (Auth::check()) {
            $userCompany = Auth::user()->company;

            $vendor = DB::table('vendors')->select(DB::raw(1))
                        ->where('base_company_id', $userCompany->id)
                        ->where('linked_company_id', $this->id)
                        ->select(['verified'])
                        ->first();

            if ($vendor) return $vendor->verified ? 'verified' : 'pending';

            return 'No connection to this company';
        }
        return 'Can\'t determine connection without logged User Company';
    }


    /**
     * A Company can only have one address
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function address()
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
     * Each Company has their own company-wide settings
     * that employees share
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne(CompanySettings::class);
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

    /**
     * A Company can have many Purchase Orders
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * A Company has many Roles that it's
     * staff can take
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roles()
    {
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
        return $this->roles->reject(function ($role) {
            return $role->position === 'admin';
        });
    }

    /**
     * Creates a admin role for a company
     * (if one doesn't already exist).
     *
     * @return Model
     */
    public function createAdmin()
    {
        if (!$this->roles->contains('position', 'admin')) {
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
     * Returns alls the Rules that exist for this Company
     *
     * @return Rule[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getRules()
    {
        return $this->rules;
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
     * Retrieves all the Vendor models that have this Company
     * linked to it
     *
     * @param int $verifiedOnly
     * @return mixed
     */
    public function customerVendors($verifiedOnly = 1)
    {
        return $this->hasMany(Vendor::class, 'linked_company_id')
                    ->where('verified', $verifiedOnly);
    }


    /**
     * Companies that have linked their Vendor model to this
     * Company. We will also only retrieve the requests
     * that have been verified.
     *
     * @return $this
     */
    public function customerCompanies($verifiedOnly = 1)
    {
        return $this->belongsToMany(Company::class, 'vendors', 'linked_company_id', 'base_company_id')
                    ->wherePivot('verified', '=', $verifiedOnly)
                    ->withPivot('verified');
    }

    /**
     * Returns the Companies that this Company's Vendor models
     * are linked to.
     *
     * @return $this
     */
    public function supplierCompanies($verifiedOnly = 1)
    {
        return $this->belongsToMany(Company::class, 'vendors', 'base_company_id', 'linked_company_id')
                    ->wherePivot('verified', '=', $verifiedOnly)
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
        if (!array_key_exists('connects', $this->relations)) $this->loadConnects();
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
        return $this->customerCompanies->merge($this->supplierCompanies);
    }

    public function getCurrenciesAttribute()
    {
        $companyCurrencies = $this->settings->currencies;

        $purchaseOrderCurrencies = Country::currencyOnly()->join('purchase_orders', 'countries.id', '=', 'purchase_orders.currency_id')
            ->where('purchase_orders.company_id', '=', $this->id)
            ->groupBy('countries.id')
            ->get();



        return $companyCurrencies->merge($purchaseOrderCurrencies);
    }




}

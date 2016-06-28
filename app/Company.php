<?php

namespace App;

use App\Http\Requests\RegisterCompanyRequest;
use App\Http\Requests\StartProjectRequest;
use App\Role;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Billable;
use App\Subscription;

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

    use Billable;
    
    /**
     * Mass-Fillable fields for a company
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Append these dynamic properties
     * @var array
     */
    protected $appends = [
        'currencies'
    ];

    /**
     * Over-write Laravel Billable trait - so we can use company_id instead of user_id
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'company_id')->orderBy('created_at', 'desc');
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
     * Accessor: Only retrieve employees that have an active account
     * 
     * @return mixed
     */
    public function getActiveStaffAttribute()
    {
        if (!array_key_exists('activeStaff', $this->relations)) $this->setRelation('activeStaff', $this->employees()->where('active', '1')->get());
        return $this->getRelation("activeStaff");
    }

    /**
     * Accessor: To only retrieve Company's only subscription (main)
     *
     * @return mixed
     */
    public function getSubscriptionAttribute()
    {
        if (!array_key_exists('subscription', $this->relations)) $this->setRelation('subscription', $this->subscription('main'));
        return $this->getRelation("subscription");
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
     * Returns all Company's roles, excluding Admin
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
        return $this->hasMany(Vendor::class);
    }
    

    /**
     * All the possible currencies a Company has.
     */
    public function getCurrenciesAttribute()
    {
        // If we're only fetching public Company info (profile) w/o settings
        if(! $this->settings) return;

        // Currencies added to list of currencies in Company settings page
        $companyCurrencies = $this->settings->currencies;

        // Currencies from P/O(s) that have been previously issued
        $purchaseOrderCurrencies = Country::currencyOnly()->join('purchase_orders', 'countries.id', '=', 'purchase_orders.currency_id')
            ->where('purchase_orders.company_id', '=', $this->id)
            ->groupBy('countries.id')
            ->get();

        // Merge both lists of currencies
        return $companyCurrencies->merge($purchaseOrderCurrencies);
    }




}

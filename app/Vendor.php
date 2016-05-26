<?php

namespace App;

use App\Address;
use App\Company;
use App\Http\Requests\AddNewVendorRequest;
use App\Utilities\Traits\HasNotes;
use App\Utilities\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;


/**
 * App\Vendor
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $description
 * @property integer $base_company_id
 * @property boolean $verified
 * @property integer $linked_company_id
 * @property-read Company $baseCompany
 * @property-read Company $linkedCompany
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BankAccount[] $allBankAccounts
 * @property-read mixed $bank_accounts
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PurchaseOrder[] $purchaseOrders
 * @property-read mixed $number_p_o
 * @property-read mixed $average_p_o
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Address[] $addresses
 * @method static \Illuminate\Database\Query\Builder|\App\Vendor whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Vendor whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Vendor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Vendor whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Vendor whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Vendor whereBaseCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Vendor whereVerified($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Vendor whereLinkedCompanyId($value)
 * @mixin \Eloquent
 */
class Vendor extends Model
{

    use HasNotes, RecordsActivity;

    protected $fillable = [
        'name',
        'description',
        'base_company_id',
        'verified',
        'linked_company_id'
    ];

    protected $appends = [
        'number_po',
        'average_po',
        'bank_accounts'
    ];


    /**
     * Mutator to save linked_company_id field as NULL
     * instead of an empty string.
     *
     * @param $value
     */
    public function setLinkedCompanyIdAttribute($value)
    {
        if (empty($value)) {
            $this->attributes['linked_company_id'] = NULL;
        } else {
            $this->attributes['linked_company_id'] = $value;
        }
    }


    /**
     * A Vendor is always owned as a record of a base
     * Company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function baseCompany()
    {
        return $this->belongsTo(Company::class, 'base_company_id');
    }

    /**
     * A Vendor can also be optionally linked to another Company
     * in the system.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function linkedCompany()
    {
        return $this->belongsTo(Company::class, 'linked_company_id');
    }

    /**
     * Lots of Purchase Orders can be made to the same
     * Vendor.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    /**
     * Vendor can have many Bank Accounts. This returns all of
     * them - including the inactive ones (the ones that
     * have been removed but have POs linked to them)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function allBankAccounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    /**
     * A Vendor can have many records of addresses
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'owner');
    }

    /**
     * Add a new Vendor from a request to a Company by a User. The user is only
     * used to record the activity and has no direct relation to Vendor model
     *
     * @param AddNewVendorRequest $request
     * @param User $user
     * @return static
     * @throws \Exception
     */
    public static function add(AddNewVendorRequest $request, User $user)
    {
        // Create our model
        $vendor = static::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'base_company_id' => $user->company_id
        ]);

        // record activity
        $user->recordActivity('added', $vendor);

        return $vendor;
    }

    /**
     * Takes a base Company (usually owned by the logged User) and
     * a link Company and creates a new Vendor while linking the
     * Link Company in one process.
     *
     * @param Company $linkCompany
     * @param User $user
     * @return static
     * @throws \Exception
     */
    public static function createAndLinkFromCompany(User $user, Company $linkCompany)
    {
        $vendor = static::create([
            'name' => $linkCompany->name,
            'base_company_id' => $user->company_id,
            'linked_company_id' => $linkCompany->id
        ]);

        $vendor->copyCompanyAddress($linkCompany);

        $user->recordActivity('added', $vendor);

        return $vendor;
    }

    /**
     * Takes a Company and copies it's address over to this Vendor
     *
     * @param \App\Company $company
     * @return $this
     */
    public function copyCompanyAddress(Company $company)
    {
        if($companyAddress = $company->address) {
            $vendorAddress = $companyAddress->replicate();
            $this->addAddress($vendorAddress);
        }

        return $this;
    }

    /**
     * Adds an Address model to this Vendor
     *
     * @param \App\Address $address
     * @return $this
     */
    public function addAddress(Address $address)
    {
        $this->addresses()->save($address);
        return $this;
    }

    /**
     * Verify the Vendor Link to Company
     * Model
     *
     * @return bool
     */
    public function verify()
    {
        $this->verified = 1;
        return $this->save();
    }


    /**
     * When we only want to retrieve Active Bank accounts. Set an accessor
     * which will set a relation dynamically. This way it's cleaner in
     * a way so we're not calling a method to return a collection.
     *
     * @return mixed
     */
    public function getBankAccountsAttribute()
    {
        // Have we already loaded it for this instance?
        if (!array_key_exists('bank_accounts', $this->relations)) {
            // No? load it!
            $activeAccounts = $this->allBankAccounts()->where('active', '1')->get();
            $this->setRelation('bank_accounts', $activeAccounts);
        }
        // Return whatever should have been loaded as the relation
        return $this->getRelation('bank_accounts');
    }


    /**
     * Number of Purchase Orders made to this Vendor
     *
     * @return mixed
     */
    public function getNumberPOAttribute()
    {
        return $this->purchaseOrders()->count();
    }

    /**
     * Calculate the average Price / PO for this
     * Vendor
     *
     * @return float|int
     */
    public function getAveragePOAttribute()
    {
        $numPO = $this->getNumberPOAttribute();
        $sumPO = 0;
        foreach ($this->purchaseOrders as $purchaseOrder) {
            $sumPO += $purchaseOrder->total;
        }
        return ($numPO) ? $sumPO / $numPO : 0;
    }



}

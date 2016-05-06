<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\PurchaseOrder
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $status
 * @property integer $vendor_id
 * @property integer $bank_account_id
 * @property integer $user_id
 * @property integer $address_id
 * @property-read \App\Company $company
 * @property-read \App\Vendor $vendor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LineItem[] $lineItems
 * @property-read \App\Project $project
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Rule[] $rules
 * @property-read \App\Address $vendorAddress
 * @property-read \App\BankAccount $vendorBankAccount
 * @property-read \App\Address $billingAddress
 * @property-read \App\Address $shippingAddress
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PurchaseOrderAdditionalCost[] $additionalCosts
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereVendorId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereBankAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereAddressId($value)
 * @mixin \Eloquent
 */
class PurchaseOrder extends Model
{
    protected $fillable = [
        'status',
        'vendor_id',
        'vendor_address_id',
        'vendor_bank_account_id',
        'currency_id',
        'billing_address_id',
        'shipping_address_id',
        'user_id',
        'company_id'
    ];

    protected $appends = [
        'total'
    ];


    /**
     * Dynamically generated Total Attribute which takes into
     * account each Line Item as well as any Additional Costs
     * that have been added to the Order
     *
     * @return int|string
     */
    public function getTotalAttribute()
    {
        $subtotal = (int)0;
        foreach ($this->lineItems as $lineItem) {
            $subtotal += ($lineItem->quantity * $lineItem->price);
        }
        $total = $subtotal;
        foreach ($this->additionalCosts as $additionalCost) {
            if ($additionalCost->type == '%') {
                $total += ($subtotal * ($additionalCost->amount / 100));
            } else {
                $total += $additionalCost->amount;
            }
        }
        return $total;
    }

    /**
     * A Purchase Order belongs to a single Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * A PO is made to a single Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    /**
     * A PO can contain many Line Items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lineItems()
    {
        return $this->hasMany(LineItem::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Will update the quantities for any PRs that are
     * fulfilled by the Line Items contained within
     * this PO
     *
     * @return $this
     */
    public function updatePurchaseRequests()
    {
        foreach ($this->lineItems as $lineItem) {
            $lineItem->purchaseRequest->update([
                'quantity' => $lineItem->purchaseRequest->quantity - $lineItem->quantity
            ]);
        }
        return $this;
    }

    /**
     * Mark this PO as approvied
     * @return $this
     */
    public function markApproved()
    {
        $this->status = 'approved';
        $this->save();
        return $this;
    }

    /**
     * Mark PO as rejected
     * @return $this
     */
    public function markRejected()
    {
        $this->status = 'rejected';
        $this->save();
        return $this;
    }


    /**
     * A purchase order can have many rules which apply.
     * If any rules are flagged - it must be approved
     * by a team member who's role is pre-approved.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules()
    {
        return $this->belongsToMany(Rule::class);
    }

    /**
     * Quick 'string' checker wrapper for PO status
     *
     * @param $status
     * @return bool
     */
    public function hasStatus($status)
    {
        return $this->status === $status;
    }

    /**
     * Wrapper function that delegates out rule assignment
     * to the Rule Model.
     *
     * @return $this
     */
    public function attachRules()
    {
        // Get a list of all the company's rules
        $companyRules = $this->getCompanyRules();
        // For each rule - check each property
        foreach ($companyRules as $rule) {
            $rule->processPurchaseOrder($this);
        }
        // All done attaching rules...
        return $this;
    }

    /**
     * Approves a PO if it does not have any
     * active rules that apply.
     *
     * @return $this
     */
    public function tryAutoApprove()
    {
        // If PO is 'pending' and DOES NOT have any attached rules
        if ($this->hasStatus('pending') && count($this->rules) === 0) {
            $this->markApproved();
        }
        return $this;
    }

    /**
     * Returns all the rules for the project's
     * company.
     *
     * @return Rule[]|\Illuminate\Database\Eloquent\Collection
     */
    protected function getCompanyRules()
    {
        return $this->company->rules;
    }

    /**
     * A PO Could be made out to an address that belongs to
     * either a Vendor or a Company linked to Vendor
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendorAddress()
    {
        return $this->belongsTo(Address::class, 'vendor_address_id');
    }

    /**
     * PO made out to a single Vendor's Bank Account
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vendorBankAccount()
    {
        return $this->belongsTo(BankAccount::class, 'vendor_bank_account_id');
    }

    /**
     * PO Made out to a single Billing Address
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function billingAddress()
    {
        // If we're latching onto an Address that belongs to a Company, then just fetch it
        if ($this->billing_address_id) return $this->belongsTo(Address::class, 'billing_address_id');
        // Otherwise we're creating a new Address for this PO, we should set this PO as the owner
        return $this->morphOne(Address::class, 'owner');
    }

    /**
     * Optionally, PO can be made out to a Shipping Address that differs
     * from it's Billing Address
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingAddress()
    {
        if ($this->shipping_address_id) return $this->belongsTo(Address::class, 'shipping_address_id');
        return $this->morphOne(Address::class, 'owner');
    }

    /**
     * Attaches Address models as Billing and Shipping Addresses respectively
     *
     * @param Address $billingAddress
     * @param Address $shippingAddress
     * @return $this
     */
    public function attachBillingAndShippingAddresses(Address $billingAddress, Address $shippingAddress)
    {
        if ($billingAddress->owner_id) {
            $this->billing_address_id = $billingAddress->id;
        } else {
            $this->billingAddress()->save($billingAddress);
        }

        if ($shippingAddress->owner_id) {
            $this->shipping_address_id = $shippingAddress->id;
        } else {
            $this->shippingAddress()->save($shippingAddress);
        }

        $this->save();
        return $this;
    }

    /**
     * A PO can have many other Additional Costs (or Discounts), such
     * as tax, discounts, shipping etc.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function additionalCosts()
    {
        return $this->hasMany(PurchaseOrderAdditionalCost::class);
    }

}

<?php

namespace App;

use App\Utilities\FormatNumberPropertyTrait;
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
    use FormatNumberPropertyTrait;

    
    protected $fillable = [
        'status',
        'number',
        'vendor_id',
        'vendor_address_id',
        'vendor_bank_account_id',
        'currency_id',
        'billing_address_id',
        'shipping_address_id',
        'subtotal',
        'total',
        'user_id',
        'company_id'
    ];

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
     * Calculates the subtotal and then saves it as the 'subtotal'
     * field in the database
     *
     * @return int
     */
    public function setSubtotal()
    {
        $subtotal = 0;
        foreach ($this->lineItems as $lineItem) {
            $subtotal += ($lineItem->quantity * $lineItem->price);
        }
        $this->subtotal = $subtotal;
        $this->save();
        return $this;
    }

    /**
     * Calculate the 'total' field which takes into account each
     * Line Item as well as any Additional Costs that have
     * been added to the Order.
     *
     * @return int|string
     */
    public function setTotal()
    {
        if(! $this->subtotal) $this->setSubtotal();
        $total = $this->subtotal;
        foreach ($this->additionalCosts as $additionalCost) {
            if ($additionalCost->type == '%') {
                $total += ($this->subtotal * ($additionalCost->amount / 100));
            } else {
                $total += $additionalCost->amount;
            }
        }

        $this->total = $total;
        $this->save();

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
     * Wrapper function so we only need to call one method whenever we create a
     * Purchase Order and need to call the individual methods afterwards.
     *
     * @param $billingAddress
     * @param $shippingAddress
     * @return $this
     */
    public function callCreateMethods($billingAddress, $shippingAddress)
    {
        $this->setTotal()
             ->attachBillingAndShippingAddresses($billingAddress, $shippingAddress)
             ->updatePurchaseRequests()
             ->attachRules()
             ->tryAutoApprove();

        return $this;
    }

    /**
     * Attaches Address models as Billing and Shipping Addresses respectively. We will always attach
     * billing_address_id and shipping_address_id - so it allows them to both reference different
     * Address models. If an Address has no owner, we will also attach this PO as the owner.
     *
     * @param Address $billingAddress
     * @param Address $shippingAddress
     * @return $this
     */
    public function attachBillingAndShippingAddresses(Address $billingAddress, Address $shippingAddress)
    {

        // Attach IDs
        $this->billing_address_id = $billingAddress->id;
        $this->shipping_address_id = $shippingAddress->id;

        // Save as parent IF the address does not already belong to another parent (ie. Company)
        if (! $billingAddress->owner_id) $billingAddress->setOwner('purchase_order', $this->id);
        if (! $shippingAddress->owner_id) $shippingAddress->setOwner('purchase_order', $this->id);

        $this->save();
        return $this;
    }

    /**
     * Wrapper function that delegates out rule assignment
     * to the Rule Model.
     *
     * @return $this
     */
    public function attachRules($company = null, $rules = null)
    {
        $company = $company ?: $this->company;
        // Get a list of all the company's rules - or if testing, use the mock object provided
        $companyRules = $rules ?: $company->getRules();
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
        return $this->belongsTo(Address::class, 'billing_address_id');
    }

    /**
     * Optionally, PO can be made out to a Shipping Address that differs
     * from it's Billing Address
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function shippingAddress()
    {
        return $this->belongsTo(Address::class, 'shipping_address_id');
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

    /**
     * Checks if this PO is over a given limit.
     *
     * Tested within RuleTest
     *
     * @param $limit
     * @return bool
     */
    public function totalExceeds($limit)
    {
        return $this->total > $limit;
    }

    /**
     * Checks whether this PO's vendor is 'new', ie. is there a previous PO
     * that is also to the same Vendor that is approved?
     *
     * Tested within RuleTest
     * 
     * @return bool
     */
    public function newVendor()
    {
        return count($this->vendor->purchaseOrders()->where('status', 'approved')->get()) < 1;
    }

}

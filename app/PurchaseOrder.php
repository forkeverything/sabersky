<?php

namespace App;

use App\Country;
use App\Events\PurchaseOrderUpdated;
use App\Events\PurchaseRequestUpdated;
use App\Utilities\FormatNumberPropertyTrait;
use App\Utilities\Traits\HasNotes;
use App\Utilities\Traits\LineItemsActivities;
use App\Utilities\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;


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
 * @property integer $number
 * @property integer $vendor_address_id
 * @property integer $vendor_bank_account_id
 * @property integer $currency_id
 * @property integer $billing_address_id
 * @property integer $shipping_address_id
 * @property float $subtotal
 * @property float $total
 * @property integer $company_id
 * @property-read mixed $pending
 * @property-read mixed $approved
 * @property-read mixed $rejected
 * @property-read \App\Country $currencyCountry
 * @property-read mixed $currency
 * @property-read mixed $currency_country_name
 * @property-read mixed $currency_name
 * @property-read mixed $currency_code
 * @property-read mixed $currency_symbol
 * @property-read mixed $billing_address_same_as_company
 * @property-read mixed $shipping_address_same_as_billing
 * @property-read mixed $items
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Note[] $notes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activities
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $modelActivities
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereVendorAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereVendorBankAccountId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereCurrencyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereBillingAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereShippingAddressId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereSubtotal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereTotal($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseOrder whereCompanyId($value)
 */
class PurchaseOrder extends Model
{
    use FormatNumberPropertyTrait, HasNotes, RecordsActivity, LineItemsActivities;

    /**
     * Mass-Assignable fields for an Order
     *
     * @var array
     */
    protected $fillable = [
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
     * Dynamically determine these and append to every Order
     *
     * @var array
     */
    protected $appends = [
        'items',
        'currency',
        'currency_country_name',
        'currency_name',
        'currency_code',
        'currency_symbol',
        'billing_address_same_as_company',
        'shipping_address_same_as_billing'
    ];

    /**
     * Default attribute values
     *
     * @var array
     */
    protected $attributes = [
        'status' => 'pending'
    ];

    /**
     * Model events to record as Activity
     *
     * @var array
     */
    protected static $recordEvents = [
        'created'
    ];

    /**
     * Accessor - Check if PO has status: pending
     *
     * @return bool
     */
    public function getPendingAttribute()
    {
        return $this->hasStatus('pending');
    }

    /**
     * Accessor - Check if PO has status: approved
     *
     * @return bool
     */
    public function getApprovedAttribute()
    {
        return $this->hasStatus('approved');
    }

    /**
     * Accessor - Check if PO has status: successs
     *
     * @return bool
     */
    public function getRejectedAttribute()
    {
        return $this->hasStatus('rejected');
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

    /**
     * The User that submitted the Order
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The country model we'll use to retrieve currency information
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currencyCountry()
    {
        return $this->belongsTo(Country::class, 'currency_id');
    }

    /**
     * Every Order has a  single currency (Country)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getCurrencyAttribute()
    {
        return Country::currencyOnly()->find($this->currency_id);
    }


    /**
     * Currency Accessor (appended)
     *
     * @return mixed
     */
    public function getCurrencyCountryNameAttribute()
    {
        return $this->currency->country_name;
    }

    /**
     * Currency Accessor (appended)
     *
     * @return mixed
     */
    public function getCurrencyNameAttribute()
    {
        return $this->currency->name;
    }

    /**
     * Currency Accessor (appended)
     *
     * @return mixed
     */
    public function getCurrencyCodeAttribute()
    {
        return $this->currency->code;
    }

    /**
     * Currency Accessor (appended)
     *
     * @return mixed
     */
    public function getCurrencySymbolAttribute()
    {
        return $this->currency->symbol;
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
            $lineItemQuantity = $lineItem->quantity;
            if ($this->hasStatus('rejected')) $lineItemQuantity = -$lineItemQuantity;
            $lineItem->purchaseRequest->update([
                'quantity' => $lineItem->purchaseRequest->quantity - $lineItemQuantity
            ]);
            Event::fire(new PurchaseRequestUpdated($lineItem->purchaseRequest));
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
        if (!$this->subtotal) $this->setSubtotal();
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
     * @param User $user
     * @return $this
     * @throws \Exception
     */
    public function markApproved(User $user)
    {
        $this->status = 'approved';
        $this->save();
        // record activity approved
        $user->recordActivity($this->status, $this);
        return $this;
    }

    /**
     * Mark PO as rejected
     * @param User $user
     * @return $this
     * @throws \Exception
     */
    public function markRejected(User $user)
    {
        $this->status = 'rejected';
        $this->save();
        // record activity - rejected
        $user->recordActivity($this->status, $this);
        // reject all line items
        $this->recordLineItemRejectedActivity($user);
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
        return $this->belongsToMany(Rule::class)
                    ->withPivot('approved'); // get the approval status of the rule
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
     * Attaches Address models as Billing and Shipping Addresses respectively. We will always attach
     * billing_address_id and shipping_address_id - so it allows them to both reference different
     * Address models. If address is specific to this PO, it will be attached as the owner.
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
        if (!$billingAddress->owner_id) $billingAddress->setOwner('App\PurchaseOrder', $this->id);
        if (!$shippingAddress->owner_id) $shippingAddress->setOwner('App\PurchaseOrder', $this->id);

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
     * Updates the Order's status if necessary
     *
     * @param User $user
     * @return $this|void
     */
    public function updateStatus(User $user)
    {
        $previousStatus = $this->status;
        // Can't un-approve a status - a rejected Order can be approved but not vice-versa.
        if ($this->hasStatus('approved')) return;

        // If this order has ANY rule rejected - then it is rejected.
        if ($this->hasRejectedRule()) {
            $this->markRejected($user)
                 ->updatePurchaseRequests();
        } else {
            $this->markPending();
        }

        // If we don't have any rules or all rules are approved - then we can consider Order approved
        if ((count($this->rules) === 0 || $this->attachedRulesAllApproved())) $this->markApproved($user);
        
        if($previousStatus !== $this->status) Event::fire(new PurchaseOrderUpdated($this));

        return $this;
    }

    /**
     * Set Order status to pending...
     *
     * @return $this
     */
    public function markPending()
    {
        $this->status = 'pending';
        $this->save();
        return $this;
    }

    /**
     * Quick checker to see if all the attached rules to this PO is
     * marked approved
     *
     * @return bool
     */
    public function attachedRulesAllApproved()
    {
        $numApprovedRules = $this->rules->pluck('pivot')->where('approved', 1)->count();
        $numTotalRules = $this->rules->count();
        return $numApprovedRules === $numTotalRules;
    }

    /**
     * Check whether Order has a rule that is 'rejected'
     *
     * @return mixed
     */
    public function hasRejectedRule()
    {
        return !!$this->rules()->wherePivot('approved', '=', 0)->first();
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
     * Checks if this PO is over a given limit, for a
     * given Currency
     *
     * Tested within RuleTest
     *
     * @param $limit
     * @param $currencyID
     * @return bool
     */
    public function totalExceeds($limit, $currencyID)
    {
        if ($this->currency_id == $currencyID) return $this->total > $limit;
        return false;
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

    /**
     * Check if Order billing address same as Company's address
     *
     * @return bool
     */
    public function getBillingAddressSameAsCompanyAttribute()
    {
        if($this->billingAddress) return $this->billingAddress->id === $this->company->address->id;
        return false;
    }

    /**
     * Check if Order shipping address same as billing
     * @return bool
     */
    public function getShippingAddressSameAsBillingAttribute()
    {
        if($this->shippingAddress) return $this->shippingAddress->id === $this->billingAddress->id;
        return false;
    }

    /**
     * Mutator to get the Items that were ordered from a Purchase Order
     *
     * @return mixed
     */
    public function getItemsAttribute()
    {
        if (!array_key_exists('items', $this->relations)) $this->loadItems();
        return $this->getRelation('items');
    }

    /**
     * Loads Items for the PO and sets it as a relation, to be returned
     * by the mutator function
     */
    protected function loadItems()
    {
        $items = Item::where('company_id', $this->company_id)
                     ->join('purchase_requests', 'items.id', '=', 'purchase_requests.item_id')
                     ->join('line_items', 'purchase_requests.id', '=', 'line_items.purchase_request_id')
                     ->whereIn('purchase_requests.id', $this->lineItems->pluck('purchase_request_id'))
                     ->where('line_items.purchase_order_id', '=', $this->id)
                     ->select(\DB::raw('
                                items.*,
                                SUM(line_items.quantity) as order_quantity,
                                line_items.price as order_unit_price,
                                SUM(line_items.quantity) * line_items.price as order_total
                               '))
                     ->groupBy('items.id')
                     ->get();

        $this->setRelation('items', $items);
    }

    /**
     * Handles a Rule attached to this Order. We need to know the User
     * trying to make the change so we can check their Role.
     *
     * @param $action
     * @param Rule $rule
     * @param User $user
     * @return mixed
     */
    public function handleRule($action, Rule $rule, User $user)
    {
        // Is User's Role defined within collection Roles?
        if (!$rule->allowsUser($user)) abort(403, "User not authorized to approve that rule");

        if ($action === 'approve') $this->rules->where('id', $rule->id)->first()->setPurchaseOrderApproved(1);
        if ($action === 'reject') $this->rules->where('id', $rule->id)->first()->setPurchaseOrderApproved(0);

        $this->updateStatus($user);

        return $this->rules->where('id', $rule->id)->first()->pivot->save();
    }

    /**
     * Records all the ordered Line Item's activities as cancelled by
     * the given User
     *
     * @param User $user
     * @throws \Exception
     */
    public function recordLineItemRejectedActivity(User $user)
    {
        foreach ($this->lineItems as $lineItem) {
            $lineItem->recordRejectedBy($user);
        }
    }



}

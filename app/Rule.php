<?php

namespace App;

use App\Company;
use App\Country;
use App\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * App\Rule
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $limit
 * @property integer $rule_property_id
 * @property integer $rule_trigger_id
 * @property integer $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PurchaseOrder[] $purchaseOrders
 * @property-read \App\Company $company
 * @property-read mixed $property
 * @property-read mixed $trigger
 * @method static \Illuminate\Database\Query\Builder|\App\Rule whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rule whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rule whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rule whereLimit($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rule whereRulePropertyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rule whereRuleTriggerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Rule whereCompanyId($value)
 * @mixin \Eloquent
 */
class Rule extends Model
{


    /**
     * Fillable fields for a rule
     * @var array
     */
    protected $fillable = [
        'limit',
        'currency_id',
        'rule_property_id',
        'rule_trigger_id',
        'company_id'
    ];

    /**
     * Automatically append these properties. We didn't create model's for these
     * because they are only an extension of Rules and also we don't foresee
     * Users being able to create / update these.
     *
     * @var array
     */
    protected $appends = [
        'property',
        'trigger',
        'currency'
    ];

    /**
     * Always eager load these relationships
     *
     * @var array
     */
    protected $with = [
        'roles'
    ];

    /**
     * Fetch the Country model and return it as currency
     *
     * @return array
     */
    public function getCurrencyAttribute()
    {
        if($this->trigger->has_currency) return Country::currencyOnly()->find($this->currency_id);
    }

    /**
     * Rule can belong to many roles (m2m)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Rule can belong to many PO's (m2m)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function purchaseOrders()
    {
        return $this->belongsToMany(PurchaseOrder::class)
                    ->withPivot('approved'); // get the approval status of the rule
    }

    /**
     * All rules can only belong to one single company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Fetches the property that the rule
     * applies to
     *
     * @return mixed
     */
    public function getPropertyAttribute()
    {
        return DB::table('rule_properties')
                 ->select('*')
                 ->where('id', $this->rule_property_id)
                 ->first();
    }

    /**
     * Gets the trigger for a given
     * rule.
     *
     * @return mixed
     */
    public function getTriggerAttribute()
    {
        return DB::table('rule_triggers')
                 ->select('*')
                 ->where('id', $this->rule_trigger_id)
                 ->first();
    }

    /**
     * Process a Purchase Order by applying the relevant
     * checks depending on what type of Rule this
     * model is.
     *
     * @param PurchaseOrder $purchaseOrder
     */
    public function processPurchaseOrder(PurchaseOrder $purchaseOrder, $property = null)
    {
        $property = $property ?: $this->property->name;
        $func = 'check' . str_snake_to_camel($property);
        $this->$func($purchaseOrder);
    }

    /**
     * Returns this Rule's Trigger's name or check if this Rule's Trigger
     * has a given name.
     *
     * @return mixed
     */
    public function getTriggerName($trigger = null)
    {
        if ($trigger) return $this->trigger->name == $trigger;
        return $this->trigger->name;
    }

    /**
     * Attaches this Rule to a Purchase Order
     *
     * @param PurchaseOrder $purchaseOrder
     */
    protected function attachToPO(PurchaseOrder $purchaseOrder)
    {
        return $this->purchaseOrders()->attach($purchaseOrder);
    }

    /**
     * Check the property 'order_total' for any triggers that might
     * have been tripped.
     *
     * @param PurchaseOrder $purchaseOrder
     */
    public function checkOrderTotal(PurchaseOrder $purchaseOrder)
    {
        $func = 'checkOrderTotal' . str_snake_to_camel($this->getTriggerName());
        $this->$func($purchaseOrder);
    }

    /**
     * Check
     * property: order_total
     * trigger: exceeds
     * @param PurchaseOrder $purchaseOrder
     */
    protected function checkOrderTotalExceeds(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->totalExceeds($this->limit, $this->currency_id)) $this->attachToPO($purchaseOrder);
    }

    /**
     * Checks 'vendor' property triggers.
     *
     * @param PurchaseOrder $purchaseOrder
     */
    public function checkVendor(PurchaseOrder $purchaseOrder)
    {
        $func = 'checkVendor' . str_snake_to_camel($this->getTriggerName());
        $this->$func($purchaseOrder);
    }

    /**
     * Check
     * property: vendor
     * trigger: new
     * @param PurchaseOrder $purchaseOrder
     */
    protected function checkVendorNew(PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->newVendor()) $this->attachToPO($purchaseOrder);
    }

    /**
     * Check for 'single_item' property triggers.
     *
     * @param PurchaseOrder $purchaseOrder
     */
    public function checkSingleItem(PurchaseOrder $purchaseOrder)
    {
        foreach ($purchaseOrder->lineItems as $lineItem) {
            $func = 'checkSingleItem' . str_snake_to_camel($this->getTriggerName());
            $this->$func($purchaseOrder, $lineItem);
        }
    }

    /**
     * Check
     * property: single_item
     * trigger: exceeds
     *
     * @param PurchaseOrder $purchaseOrder
     * @param LineItem $lineItem
     */
    protected function checkSingleItemExceeds(PurchaseOrder $purchaseOrder, LineItem $lineItem)
    {
        // Do any items exceed the single item limit?
        if ($lineItem->totalExceeds($this->limit)) $this->attachToPO($purchaseOrder);
    }

    /**
     * Check
     * property: single_item
     * trigger: new
     *
     * @param PurchaseOrder $purchaseOrder
     * @param LineItem $lineItem
     */
    protected function checkSingleItemNew(PurchaseOrder $purchaseOrder, LineItem $lineItem)
    {
        // If the item being ordered has only been ordered once (current PO), then it's new
        if ($lineItem->purchaseRequest->item->new) $this->attachToPO($purchaseOrder);
    }

    /**
     * Check
     * property: single_item
     * trigger: percentage_over_mean
     *
     * @param PurchaseOrder $purchaseOrder
     * @param LineItem $lineItem
     */
    protected function checkSingleItemPercentageOverMean(PurchaseOrder $purchaseOrder, LineItem $lineItem)
    {
        if ($lineItem->itemPriceIsOverMeanBy($this->limit)) $this->attachToPO($purchaseOrder);
    }

    /**
     * Grabs the given User's role and attaches it to the
     * Rule
     *
     * @param User $user
     * @return mixed
     */
    public function attachUserRole(User $user)
    {
        return $this->roles()->attach($user->role->id);
    }

    /**
     * Checks whether the given User is allowed to approve / reject
     * the Rule
     *
     * @param User $user
     * @return bool
     */
    public function allowsUser(User $user)
    {
        return $this->roles->contains($user->role);
    }

    /**
     * Mark the pivot table (for Purchase Order)'s
     * approved column
     * 
     * @param $val
     * @return mixed
     */
    public function setPurchaseOrderApproved($val)
    {
        // If we've already approved / rejected - then don't allow anymore changes
        if($val !== 1 && $val !== 0 || $this->pivot->approved !== null) return;
        $this->pivot->approved = $val;
        return $this->pivot->save();
    }

}

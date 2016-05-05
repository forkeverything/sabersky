<?php

namespace App;

use App\Company;
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
        'trigger'
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
        return $this->belongsToMany(PurchaseOrder::class);
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
    public function processPurchaseOrder(PurchaseOrder $purchaseOrder)
    {
        switch ($this->property->name) {
            case 'order_total':
                $this->checkOrderTotal($purchaseOrder);
                break;
            case 'vendor':
                $this->checkVendor($purchaseOrder);
                break;
            case 'single_item':
                $this->checkSingleItems($purchaseOrder);
                break;
            default:
                abort(500, 'That property does not exist');
        }
    }

    /**
     * Check the property 'order_total' for any triggers that might
     * have been tripped.
     *
     * @param PurchaseOrder $purchaseOrder
     */
    protected function checkOrderTotal(PurchaseOrder $purchaseOrder)
    {
        switch ($this->trigger->name) {
            case 'exceeds':
                if ($purchaseOrder->total > $this->limit) $this->attachToPO($purchaseOrder);
                break;
            default:
                abort(500, 'That trigger does not exist for Order Total');
                break;
        }
    }

    /**
     * Checks 'vendor' property triggers.
     *
     * @param PurchaseOrder $purchaseOrder
     */
    protected function checkVendor(PurchaseOrder $purchaseOrder)
    {
        switch ($this->trigger->name) {
            case 'new':
                // If we the Vendor does not have any prior 'approved' POs, we can assume the Vendor is 'new'
                if (! $purchaseOrder->vendor->purchaseOrders()->where('status', 'approved')->first()) $this->attachToPO($purchaseOrder);
                break;
            default:
                abort(500, 'That trigger does not exist for Vendor');
                break;
        }
    }

    /**
     * Check for 'single_item' property triggers.
     *
     * @param PurchaseOrder $purchaseOrder
     */
    protected function checkSingleItems(PurchaseOrder $purchaseOrder)
    {
        foreach ($purchaseOrder->lineItems as $lineItem) {
            switch ($this->trigger->name) {
                case 'exceeds':
                    // Do any items exceed the single item limit?
                    if (($lineItem->price * $lineItem->quantity) > $this->limit) $this->attachToPO($purchaseOrder);
                    break;
                case 'new':
                    // If the item being ordered has only been ordered once (current PO), then it's new
                    if ($lineItem->purchaseRequest->item->new) $this->attachToPO($purchaseOrder);
                    break;
                case 'percentage_over_mean':
                    $mean = $lineItem->purchaseRequest->item->mean;
                    $price = $lineItem->price;
                    $meanDiff = ($price - $mean) / $mean;
                    if ($meanDiff > $this->limit) $this->attachToPO($purchaseOrder);
                    break;
                default:
                    abort(500, 'That trigger does not exist for Single Items');
                    break;
            }
        }
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

}

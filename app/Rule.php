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

    protected $appends = [
        'property',
        'trigger'
    ];

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

    protected function checkOrderTotal(PurchaseOrder $purchaseOrder)
    {
        switch ($this->trigger->name) {
            case 'exceeds':
                if ($purchaseOrder->total > $this->limit) $this->attachPO($purchaseOrder);
                break;
            default:
                abort(500, 'That trigger does not exist for Order Total');
                break;
        }
    }

    protected function checkVendor(PurchaseOrder $purchaseOrder)
    {
        switch ($this->trigger->name) {
            case 'new':
                // If the PO's vendor only has 1 previous PO - assume new vendor
                if (count($purchaseOrder->vendor->purchaseOrders) === 1) $this->attachPO($purchaseOrder);
                break;
            default:
                abort(500, 'That trigger does not exist for Vendor');
                break;
        }
    }

    protected function checkSingleItems(PurchaseOrder $purchaseOrder)
    {
        foreach ($purchaseOrder->lineItems as $lineItem) {
            switch ($this->trigger->name) {
                case 'exceeds':
                    if (($lineItem->price * $lineItem->quantity) > $this->limit) $this->attachPO($purchaseOrder);
                    break;
                case 'new':
                    // If the item being ordered has only been requested once (now), then it's new
                    if (count($lineItem->purchaseRequest->item->purchaseRequests) === 1) $this->attachPO($purchaseOrder);
                    break;
                case 'percentage_over_mean':
                    $mean = $lineItem->purchaseRequest->item->mean;
                    $price = $lineItem->price;
                    $meanDiff = ($price - $mean) / $mean;
                    if ($meanDiff > $this->limit) $this->attachPO($purchaseOrder);
                    break;
                default:
                    abort(500, 'That trigger does not exist for Single Items');
                    break;
            }
        }
    }

    protected function attachPO(PurchaseOrder $purchaseOrder)
    {
        return $this->purchaseOrders()->attach($purchaseOrder);
    }

}

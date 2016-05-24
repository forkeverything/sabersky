<?php

namespace App;

use App\Http\Requests\MakePurchaseRequestRequest;
use App\Utilities\FormatNumberPropertyTrait;
use App\Utilities\Traits\HasNotes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * App\PurchaseRequest
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $quantity
 * @property \Carbon\Carbon $due
 * @property string $state
 * @property boolean $urgent
 * @property integer $item_id
 * @property integer $project_id
 * @property integer $user_id
 * @property-read \App\Item $item
 * @property-read \App\Project $project
 * @property-read \App\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LineItem[] $lineItems
 * @property integer $number
 * @property-read mixed $fulfilled_quantity
 * @property-read mixed $initial_quantity
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereNumber($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereQuantity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereDue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereUrgent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereUserId($value)
 * @mixin \Eloquent
 */
class PurchaseRequest extends Model
{

    use FormatNumberPropertyTrait, HasNotes;

    /**
     * Fillable (mass-assignable) DB Fields
     * for a Purchase Request entry.
     *
     * @var array
     */
    protected $fillable = [
        'number',
        'quantity',
        'due',
        'state',
        'urgent',
        'project_id',
        'item_id',
        'user_id'
    ];

    /**
     * Properties that should converted to
     * Carbon date instances.
     *
     * @var array
     */
    protected $dates = [
        'due'
    ];

    
    /**
     * Every PR has belongs to a single Item
     * which it is requesting.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * PR belongs to only one Project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * PR made by a single User.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }


    /**
     * A PR can have multiple Line Items where it is
     * currently being fulfilled.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lineItems()
    {
        return $this->hasMany(LineItem::class);
    }


    /**
     * Mutator that formats the due Date
     * Carbon Instance.
     *
     * @param $value
     */
    public function setDueAttribute($value)
    {
        $this->attributes['due'] = Carbon::createFromFormat('d/m/Y', $value);
    }

    /**
     * This allows us to attach a pseudo-state of 'fulfilled'
     * to Purchase Requests that have an outstanding
     * quantity of 0
     *
     * @param $value
     * @return string
     */
    public function getStateAttribute($value)
    {
        if($this->quantity === 0) return 'fulfilled';
        return $value;
    }

    /**
     * Quick checker to see if a PR
     * has the given state (string)
     *
     * @param $state
     * @return bool
     */
    public function hasState($state)
    {
        return $state === $this->state;
    }


    /**
     * Creates a new PR Instance from the Form request,
     * Item requested, and the User (logged-in) that
     * made the request.
     *
     * @param MakePurchaseRequestRequest $request
     * @param Item $item
     * @param User $user
     * @return static
     */
    public static function make(MakePurchaseRequestRequest $request, User $user)
    {
        return static::create(
            array_merge($request->all(), [
                'user_id' => $user->id
            ])
        );
    }

    /**
     * Change PR state to 'cancelled'
     * and persist in DB.
     *
     * @return $this
     */
    public function cancel()
    {
        $this->state = 'cancelled';
        $this->save();
        return $this;
    }

    /**
     * Calculates how many quantities of this PR's Item has
     * already been fulfilled (by 'approved' POs).
     *
     * @return int
     */
    public function getFulfilledQuantityAttribute()
    {
        $fulfilledQuantities = 0;
        $lineItems = $this->lineItems;
        foreach ($lineItems as $lineItem) {
            if ($lineItem->purchaseOrder->hasStatus('rejected')) break;
            $fulfilledQuantities += $lineItem->quantity;
        }
        return $fulfilledQuantities;
    }

    /**
     * Calculates how many quantities were originally requested in
     * this PR. Remember we 'add back' quantities when a PO has
     * been rejected.
     *
     * @return int
     */
    public function getInitialQuantityAttribute()
    {
        return $this->quantity + $this->fulfilledQuantity;
    }



}

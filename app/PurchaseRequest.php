<?php

namespace App;

use App\Http\Requests\MakePurchaseRequestRequest;
use App\Utilities\FormatNumberPropertyTrait;
use App\Utilities\Traits\HasNotes;
use App\Utilities\Traits\LineItemsActivities;
use App\Utilities\Traits\RecordsActivity;
use Carbon\Carbon;
use Exception;
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
 * @method static \Illuminate\Dat\abase\Query\Builder|\App\PurchaseRequest whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereUrgent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereUserId($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Note[] $notes
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $activities
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Activity[] $modelActivities
 */
class PurchaseRequest extends Model
{

    use FormatNumberPropertyTrait, HasNotes, RecordsActivity, LineItemsActivities;

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
     * Model events to record as Activity
     *
     * @var array
     */
    protected static $recordEvents = [
        'created'
    ];

    /**
     * Always append these dynamic properties
     *
     * @var array
     */
    protected $appends = [
        'initial_quantity',
        'fulfilled_quantity'
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
     * Calculates how many quantities of this PR's Item has
     * already been fulfilled (by 'approved' POs).
     *
     * @return int
     */
    public function getFulfilledQuantityAttribute()
    {
        $fulfilledQuantities = \DB::table('line_items')
            ->join('purchase_orders', 'line_items.purchase_order_id', '=', 'purchase_orders.id')
            ->select('line_items.quantity')
            ->where('purchase_request_id', $this->id)
            ->where('purchase_orders.status', '!=', 'rejected')
            ->pluck('quantity');
        return array_sum($fulfilledQuantities);
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
     * @return $this
     * @throws Exception
     */
    public function cancel()
    {
        if(! $this->state === 'open') abort(400, "Cannot cancel request unless it's open");
        $this->state = 'cancelled';
        $this->save();
        return $this;
    }

    /**
     * Re-open a previously cancelled PR
     *
     * @return $this
     * @throws Exception
     */
    public function reopen()
    {
        if(! $this->state === 'cancelled') abort(400, "Cannot reopen request unless it's cancelled");
        $this->state = 'open';
        $this->save();
        return $this;
    }








}

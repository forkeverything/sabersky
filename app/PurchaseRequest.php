<?php

namespace App;

use App\Http\Requests\MakePurchaseRequestRequest;
use App\Utilities\FormatNumberPropertyTrait;
use App\Utilities\Traits\HasNotes;
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
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereState($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereUrgent($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereItemId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereProjectId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\PurchaseRequest whereUserId($value)
 * @mixin \Eloquent
 */
class PurchaseRequest extends Model
{

    use FormatNumberPropertyTrait, HasNotes, RecordsActivity;

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
     * @return $this
     * @throws Exception
     */
    public function cancel()
    {
        if(! $this->state === 'open') throw new Exception("Cannot cancel PR unless it's open", 500);
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
        if(! $this->state === 'cancelled') throw new Exception("Cannot reopen PR unless it's cancelled", 500);
        $this->state = 'open';
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
            if (! $lineItem->purchaseOrder->hasStatus('rejected')) $fulfilledQuantities += $lineItem->quantity;
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

    /**
     * Over-write activities() so we can pull in relevant
     * Line Item activities too
     *
     * @return mixed
     */
    public function getActivitiesAttribute()
    {
        $activites = $this->getAllActivities();
        $this->setRelation('activities', $activites);
        return $this->getRelation('activities');
    }

    /**
     * Get all activities - including Line Item ones so we know when quantities
     * were fulfilled and by whom.
     *
     * @return mixed
     */
    public function getAllActivities()
    {
        $PRActivities = $this->purchaseRequestActivities;
        $LIActivities = $this->lineItemsActivities();
        return $PRActivities->merge($LIActivities);
    }

    /**
     * Renamed relationship to activities to get this PR's activities
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function purchaseRequestActivities()
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    /**
     * Get all the relevent Line Item activities
     *
     * @return \Illuminate\Support\Collection
     */
    public function lineItemsActivities()
    {
        $activities = [];
        foreach ($this->lineItems as $lineItem) {
            if($added = $lineItem->activities->where('name', 'added_line_item')->first())array_push($activities,  $added);
            if($rejected = $lineItem->activities->where('name', 'rejected_line_item')->first())array_push($activities, $rejected);
        }
        return collect($activities)->sortBy('created_at');
    }



}

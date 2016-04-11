<?php

namespace App;

use App\Http\Requests\MakePurchaseRequestRequest;
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
 */
class PurchaseRequest extends Model
{
    /**
     * Fillable (mass-assignable) DB Fields
     * for a Purchase Request entry.
     *
     * @var array
     */
    protected $fillable = [
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


}

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
 * @property boolean $approved
 * @property boolean $submitted
 * @property integer $project_id
 * @property integer $vendor_id
 * @property integer $user_id
 * @property-read \App\Vendor $vendor
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\LineItem[] $lineItems
 * @property-read \App\Project $project
 * @property-read \App\User $user
 * @property-read mixed $total
 * @property string $status
 * @property boolean $over_high
 * @property boolean $over_med
 * @property boolean $item_over_md
 * @property boolean $new_vendor
 * @property boolean $new_item
 */
class PurchaseOrder extends Model
{
    protected $fillable = [
        'approved',
        'submitted',
        'total',
        'over_high',
        'over_med',
        'item_over_md',
        'new_vendor',
        'new_item',
        'user_id',
        'project_id',
        'vendor_id'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

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
     * Processes the submission of a Purchase Order
     *
     * @return $this
     */
    public function processSubmission()
    {
        // update purchase requests
        $this->updatePurchaseRequests();

        // Mark Submitted
        $this->submitted = true;
        $this->save();
        return $this;
    }

    protected function updatePurchaseRequests()
    {
        foreach ($this->lineItems as $lineItem) {
            $lineItem->purchaseRequest->update([
                'quantity' => $lineItem->purchaseRequest->quantity - $lineItem->quantity
            ]);
        }
    }

    public function markApproved()
    {
        $this->status = 'approved';
        $this->save();
        return $this;
    }

    public function markRejected()
    {
        $this->status = 'rejected';
        $this->save();
        return $this;
    }

    public function rules()
    {
        return $this->belongsToMany(Rule::class);
    }

}

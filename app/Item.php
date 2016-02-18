<?php

namespace App;

use App\Project;
use App\Utilities\BuildPhoto;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * App\Item
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $specification
 * @property integer $project_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Project[] $projects
 * @property-read mixed $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PurchaseRequest[] $purchaseRequests
 * @property-read mixed $new
 * @property-read mixed $mean
 */
class Item extends Model
{
    protected $fillable = [
        'name',
        'specification',
        'project_id'
    ];

    protected $appends = [
        'new',
        'mean'
    ];

    public function getNameAttribute($property)
    {
        return ucfirst($property);
    }

    public function projects()
    {
        return $this->belongsToMany(Project::class);
    }

    public function company()
    {
        return $this->projects()->first()->company;
    }

    public function getCompanyAttribute()
    {
        return $this->projects()->first()->company;
    }

    /**
     * An Item can have many photos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function photos()
    {
        return $this->morphMany(Photo::class, 'model');
    }

    /**
     * An item can be requested multiple times.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function lineItems()
    {
        return $this->hasManyThrough(LineItem::class, PurchaseRequest::class);
    }

    public function getNewAttribute()
    {
        return $this->approvedLineItems()->count() < 1;
    }

    public function getMeanAttribute()
    {
        $numOrdered = array_sum($this->approvedLineItems()->pluck('quantity')->toArray());

        if ($numOrdered) {
            $sumOrderedValue = 0;
            foreach ($this->approvedLineItems()->pluck('quantity', 'price') as $quantity => $price) {
                $sumOrderedValue += ($quantity * $price);
            }
            return $sumOrderedValue / $numOrdered;
        }
        return null;
    }

    protected function approvedLineItems()
    {
        return $this->lineItems()->join('purchase_orders', 'line_items.purchase_order_id', '=', 'purchase_orders.id')
            ->where('purchase_orders.submitted', 1)
            ->where('purchase_orders.status', 'approved')
            ->get(['line_items.*']);
    }

    public function attachPhoto(UploadedFile $file)
    {
        // Build up the photo
        $photo = (new BuildPhoto($file))->item($this);
        // attach it to model
        return $this->photos()->save($photo);
    }
}

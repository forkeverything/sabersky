<?php

namespace App;

use App\Http\Requests\MakePurchaseRequestRequest;
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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Photo[] $photos
 */
class Item extends Model
{
    /**
     * Fillable DB fields for an item
     * record.
     *
     * @var array
     */
    protected $fillable = [
        'sku',
        'brand',
        'name',
        'specification',
        'company_id'
    ];

    /**
     * Appended Properties. Dynamic properties are
     * automatically inserted using an Accessor
     *
     * @var array
     */
    protected $appends = [
        'new',
        'mean'
    ];

    /**
     * Sets SKU as NULL when empty (avoid unique collision)
     * @param $value
     */
    public function setSkuAttribute($value) {
        if ( empty($value) ) {
            $this->attributes['sku'] = NULL;
        } else {
            $this->attributes['sku'] = $value;
        }
    }

    /**
     * Set Brand attribute as NULL, same as
     * Sku
     * 
     * @param $value
     */
    public function setBrandAttribute($value) {
        if ( empty($value) ) {
            $this->attributes['brand'] = NULL;
        } else {
            $this->attributes['brand'] = $value;
        }
    }

    /**
     * Finds an Item Instance from it's primary key
     * or creates one from the given attributes.
     *
     * @param $id
     * @param null $attributes
     * @return static
     */
    public static function findOrCreate($id, $attributes = null)
    {
        if($existingPR = static::find($id)) return $existingPR;

        if ($attributes) {
            return static::create($attributes);
        }

        return new static;
    }


    /**
     * Item has many project
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function projects()
    {
        return collect(\DB::table('projects')->whereExists(function ($query) {
            $query->select(\DB::raw(1))
                  ->from('purchase_requests')
                  ->where('item_id', '=', $this->id)
                  ->whereRaw('projects.id = purchase_requests.project_id');
        })->get());
    }

    /**
     * An item is saved to a single Company.
     *
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
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

    /**
     * An item can have many LineItems through all the
     * Purchase Requests that requested this item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function lineItems()
    {
        return $this->hasManyThrough(LineItem::class, PurchaseRequest::class);
    }

    /**
     * Determine if this item is new. If
     * there are no previous approved
     * Line Items then it's new.
     *
     * @return bool
     */
    public function getNewAttribute()
    {
        return $this->approvedLineItems()->count() < 1;
    }


    /**
     * Returns all the Line Items for this
     * item where the Purchase Order has
     * already been approved.
     * @return mixed
     */
    protected function approvedLineItems()
    {
        return $this->lineItems()->join('purchase_orders', 'line_items.purchase_order_id', '=', 'purchase_orders.id')
                    ->where('purchase_orders.submitted', 1)
                    ->where('purchase_orders.status', 'approved')
                    ->get(['line_items.*']);
    }

    /**
     * Calculated the Mean Price for the
     * item.
     *
     * @return float|null
     */
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

    /**
     * Handles an array of files from
     * a form.
     *
     * @param array $uploadedFiles
     * @return $this
     */
    public function handleFiles(array $uploadedFiles)
    {
        // if we have at least one uploadedFile
        if (!! $uploadedFiles[0]) {
            foreach ($uploadedFiles as $file) {
                if ($file && $file instanceof UploadedFile) {
                    $this->attachPhoto($file);
                }
            }
        }
        return $this;
    }

    /**
     * Makes a photo and attaches it
     * to this item.
     *
     * @param UploadedFile $file
     * @return Model
     */
    public function attachPhoto(UploadedFile $file, BuildPhoto $photoBuilder = null)
    {
        $photoBuilder = $photoBuilder ?: (new BuildPhoto($file)); // For testing - if we get a mocked class, use it

        // Build up the photo
        $photo = $photoBuilder->item($this);
        // attach it to model
        return $this->photos()->save($photo);
    }
}

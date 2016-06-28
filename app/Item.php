<?php

namespace App;

use App\Http\Requests\AddItemRequest;
use App\Http\Requests\MakePurchaseRequestRequest;
use App\Project;
use App\Utilities\BuildPhoto;
use App\Utilities\Traits\HasNotes;
use App\Utilities\Traits\RecordsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
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
 * @property string $sku
 * @property string $brand
 * @property integer $company_id
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereSku($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereBrand($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereSpecification($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Item whereCompanyId($value)
 * @mixin \Eloquent
 */
class Item extends Model
{
    
    use HasNotes, RecordsActivity;
    
    protected $maxNumberOfPhotos = 12;

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
        'company_id',
        'product_subcategory_id'
    ];

    /**
     * Appended Properties. Dynamic properties are
     * automatically inserted using an Accessor
     *
     * @var array
     */
    protected $appends = [
        'new',
        'means'
    ];

    /**
     * Every Item has to belong to a single Product Subcategory
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function subcategory()
    {
        return $this->belongsTo(ProductSubcategory::class, 'product_subcategory_id');
    }

    /**
     * Static wrapper to create an Item model from a request, as well as
     * record the activity for given User
     * 
     * @param AddItemRequest $request
     * @param User $user
     * @return static
     * @throws \Exception
     */
    public static function add(AddItemRequest $request, User $user)
    {
        // Create Item
        $item = static::create([
            'sku' => $request->input('sku'),
            'brand' => $request->input('brand'),
            'name' => $request->input('name'),
            'specification' => $request->input('specification'),
            'company_id' => $user->company_id,
            'product_subcategory_id' => $request->input('product_subcategory_id')
        ]);

        // Record activity
        $user->recordActivity('added', $item);

        return $item;
        
    }

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
        return $this->approved_line_items->count() < 1;
    }

    public function getApprovedLineItemsAttribute()
    {
        if( ! array_key_exists('approved_line_items', $this->relations)) $this->loadApprovedLineItems();
        return $this->getRelation('approved_line_items');
    }

    protected function loadApprovedLineItems()
    {
        if( ! array_key_exists('approved_line_items', $this->relations)) {
            $approvedLineItems = $this->lineItems()->join('purchase_orders', 'line_items.purchase_order_id', '=', 'purchase_orders.id')
                              ->where('purchase_orders.status', 'approved')
                              ->get(['line_items.*', 'purchase_orders.currency_id']);
            $this->setRelation('approved_line_items', $approvedLineItems);
        }
    }

    /**
     * Calculated the Mean Price for the
     * Item, using all the 'approved'
     * Purchase Orders
     *
     * @return float|null
     */
    public function getMeansAttribute()
    {
        $approvedLineItems = $this->approved_line_items;

        // array that will hold our various means
        $means = [];

        $currencyIDs = array_unique($approvedLineItems->pluck('currency_id')->toArray());

        foreach ($currencyIDs as $currencyID) {
            $currencyCode = \App\Country::currencyOnly()->find($currencyID)->code;
            $relevantLineItems = array_filter($approvedLineItems->toArray(), function ($lineItem) use ($currencyID) {
                return $lineItem["currency_id"] === $currencyID;
            });
            $numOrdered = array_sum(array_pluck($relevantLineItems, 'quantity'));


            if ($numOrdered) {
                $sumOrderedValue = 0;

                foreach (array_pluck($relevantLineItems, 'quantity', 'price') as $quantity => $price) {
                    $sumOrderedValue += ($quantity * $price);
                }
                $meanForCurrency = round($sumOrderedValue / $numOrdered, 5);
                $means[$currencyID] = [
                    $currencyCode,
                    $meanForCurrency
                ];
            }

        }
        return $means;
    }

    /**
     * Accepts either a currency_id or currency_code and tries to find
     * the relevant mean for the currency.
     *
     * @return mixed
     */
    public function getMean()
    {
        $arg = func_get_arg(0);
        
        if(is_int($arg)) {
            if(array_key_exists ($arg,$this->means)) return $this->means[$arg][1];
        } else {
            $matchedCurrency = array_filter($this->means, function ($currencyMean) use ($arg){
                return $currencyMean[0] === $arg;
            });
            if($matchedCurrency)return array_values($matchedCurrency)[0][1];
        }
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
     * Makes a photo and attaches it to this
     * item. After all is done, it returns
     * the Photo instance.
     *
     * @param UploadedFile $file
     * @return Model
     */
    public function attachPhoto(UploadedFile $file, BuildPhoto $photoBuilder = null)
    {
        if($this->photos()->count() >= $this->maxNumberOfPhotos) return response("Reached Max. number of photos per Item: " . $this->maxNumberOfPhotos, 409);
        // For testing - if we get a mocked class, use it. Otherwise
        // lets choose to 'new' up an instance of the real class.
        $photoBuilder = $photoBuilder ?: (new BuildPhoto($file));

        // Call the specific method for Item photos
        $photo = $photoBuilder->item($this);
        // Attach it the current model instance
        return $this->photos()->save($photo);
    }
    
}

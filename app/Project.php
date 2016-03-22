<?php

namespace App;

use App\Http\Requests\MakePurchaseRequestRequest;
use App\Utilities\BuildPhoto;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Project
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $name
 * @property string $location
 * @property string $description
 * @property boolean $operational
 * @property integer $company_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $teamMembers
 * @property-read \App\Company $company
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\PurchaseRequest[] $purchaseRequests
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Item[] $items
 */
class Project extends Model
{
    protected $fillable = [
        'name',
        'location',
        'description',
        'operational',
        'company_id'
    ];

    public function teamMembers()
    {
        return $this->belongsToMany(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

    /**
     * Processes a request to save an item to a project.
     *
     * @param MakePurchaseRequestRequest $request
     * @return Model
     */
    public function saveItem(MakePurchaseRequestRequest $request)
    {
        // New Item
        if (! $item = Item::find($request->input('item_id'))) {
            $item = Item::create([
                'name' => $request->input('name'),
                'specification' => $request->input('specification')
            ]);
        }

        // Project doesn't contain existing item...
        if (! $this->items->contains($item->id)) {
            $this->items()->save($item);
        }

        // Adding photos to items
            $files = $request->file('item_photos');
            // if photos attached to request
            if (!! $files[0]) {
                foreach ($files as $file) {
                    if ($file) {
                        $item->attachPhoto($file);
                    }
                }
            }

        return $item;
    }
}

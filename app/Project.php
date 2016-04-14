<?php

namespace App;

use App\Http\Requests\MakePurchaseRequestRequest;
use App\Utilities\BuildPhoto;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Each project can only belong to a single
     * company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * a Project can have many Team Members (users)
     * who are a part of the project.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teamMembers()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * A Project can have multiple Purchase Requests
     * made during it's lifetime.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class);
    }

    /**
     * Project can have many items ordered for it. Likewise,
     * an item can be ordered for multiple projects.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function items()
    {
        return collect(DB::table('items')->whereExists(function ($query) {
            $query->select(DB::raw(1))
                  ->from('purchase_requests')
                  ->where('project_id', '=', $this->id)
                  ->whereRaw('items.id = purchase_requests.item_id');
        })->get());
    }

    /**
     * Adds a user to a project's list of users.
     * 
     * @param User $user
     * @return $this
     */
    public function addTeamMember(User $user)
    {
        $this->teamMembers()->save($user);
        return $this;
    }

}

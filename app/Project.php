<?php

namespace App;

use App\Http\Requests\MakePurchaseRequestRequest;
use App\Http\Requests\StartProjectRequest;
use App\Utilities\Traits\RecordsActivity;
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
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereLocation($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereOperational($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Project whereCompanyId($value)
 * @mixin \Eloquent
 */
class Project extends Model
{

    use RecordsActivity;

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
     * Start a new Project for a given Company by a User (employee)
     *
     * @param StartProjectRequest $request
     * @param Company $company
     * @param User $user
     * @return static
     * @throws \Exception
     */
    public static function start(StartProjectRequest $request, User $user)
    {

        $attributes = array_merge($request->all(), ['company_id' => $user->company_id]);

        // Create record
        $project = static::create($attributes);
        // record activity
        $user->recordActivity('started', $project);
        // Save to related models
        $user->projects()->save($project);

        return $project;
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

    /**
     * Removes a user from list of team members.
     * 
     * @param User $user
     * @return bool
     */
    public function removeTeamMember(User $user)
    {
        $this->teamMembers()->detach([$user->id]);
        return $this->save();
    }

}

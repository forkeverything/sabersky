<?php

namespace App;

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

}

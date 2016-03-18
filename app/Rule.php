<?php

namespace App;

use App\Company;
use App\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Rule extends Model
{


    /**
     * Fillable fields for a rule
     * @var array
     */
    protected $fillable = [
        'limit',
        'rule_property_id',
        'rule_trigger_id',
        'company_id'
    ];

    protected $appends = [
        'property',
        'trigger'
    ];

    protected $with = [
        'roles'
    ];

    /**
     * Rule can belong to many roles (m2m)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Rule can belong to many PO's (m2m)
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function purchaseOrders()
    {
        return $this->belongsToMany(PurchaseOrder::class);
    }

    /**
     * All rules can only belong to one single company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Fetches the property that the rule
     * applies to
     *
     * @return mixed
     */
    public function getPropertyAttribute()
    {
        return DB::table('rule_properties')
            ->select('*')
            ->where('id', $this->rule_property_id)
            ->first();
    }

    public function getTriggerAttribute()
    {
        return DB::table('rule_triggers')
            ->select('*')
            ->where('id', $this->rule_trigger_id)
            ->first();
    }

}

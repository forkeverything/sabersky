<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Subscription
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $company_id
 * @property string $name
 * @property string $stripe_id
 * @property string $stripe_plan
 * @property integer $quantity
 * @property \Carbon\Carbon $trial_ends_at
 * @property \Carbon\Carbon $ends_at
 * @property-read \App\Company $user
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription whereCompanyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription whereStripeId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription whereStripePlan($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription whereQuantity($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Subscription whereEndsAt($value)
 * @mixin \Eloquent
 */
class Subscription extends \Laravel\Cashier\Subscription
{
    /**
     * Over-write User relationship to use Company
     */
    public function user()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

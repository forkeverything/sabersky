<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CompanyStatistics
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property integer $pr_count
 * @property integer $company_id
 * @method static \Illuminate\Database\Query\Builder|\App\CompanyStatistics whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanyStatistics whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanyStatistics whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanyStatistics wherePrCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanyStatistics whereCompanyId($value)
 * @mixin \Eloquent
 */
class CompanyStatistics extends Model
{
    protected $fillable = [
        'pr_count'
    ];


}

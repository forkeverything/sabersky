<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CompanySettings
 *
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property boolean $po_requires_bank_account
 * @property boolean $po_requires_address
 * @property integer $currency_decimal_points
 * @property integer $currency_id
 * @property integer $company_id
 * @property-read \App\Country $currencyCountry
 * @property-read mixed $currency
 * @method static \Illuminate\Database\Query\Builder|\App\CompanySettings whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanySettings whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanySettings whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanySettings wherePoRequiresBankAccount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanySettings wherePoRequiresAddress($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanySettings whereCurrencyDecimalPoints($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanySettings whereCurrencyId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\CompanySettings whereCompanyId($value)
 * @mixin \Eloquent
 */
class CompanySettings extends Model
{
    /**
     * Mass-assignable fields for a Company's
     * settings
     *
     * @var array
     */
    protected $fillable = [
        'po_requires_bank_account',
        'po_requires_address',
        'currency_decimal_points',
        'currency_id',
        'company_id'
    ];

    protected $appends = [
        'currencies'
    ];


    /**
     * A Company's Settings contains available Currencies for the Company
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currencyCountries()
    {
        return $this->belongsToMany(Country::class, 'company_currency', 'company_id', 'currency_id');
    }

    /**
     * We only want certain fields from the Country model that
     * are relevant to the currency
     *
     * Tested in Company model
     * 
     * @return mixed
     */
    public function getCurrenciesAttribute()
    {
        return $this->currencyCountries()
                    ->selectRaw('countries.id as id, countries.name as country_name, countries.currency as name, countries.currency_code as code, countries.currency_symbol as symbol')
                    ->get();
    }
}

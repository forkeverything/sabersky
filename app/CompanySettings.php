<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
        'currency_decimal_points',
        'currency_id',
        'company_id'
    ];

    protected $appends = [
        'currency'
    ];

    /**
     * A Company's Settings contains a currency that belongs to a Country
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currencyCountry()
    {
        return $this->belongsTo(Country::class, 'currency_id');
    }

    /**
     * We only want certain fields from the Country model that
     * are relevant to the currency
     * 
     * @return mixed
     */
    public function getCurrencyAttribute()
    {
        return $this->currencyCountry()
                    ->select(['id', 'name', 'currency', 'currency_code', 'currency_symbol'])
                    ->first();
    }
}

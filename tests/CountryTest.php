<?php

use App\Country;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CountryTest extends TestCase
{

    /**
     * @test
     */
    public function it_gets_currency_attributes_only()
    {
        $currency = Country::currencyOnly()->get()->random();
        $this->assertNotNull($currency->id);
        $this->assertNotNull($currency->country_name);
        $this->assertNotNull($currency->name);
        $this->assertNotNull($currency->code);
        $this->assertNotNull($currency->symbol);
    }
    
}

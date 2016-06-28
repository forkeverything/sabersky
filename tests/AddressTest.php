<?php

use App\Address;
use App\Vendor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AddressTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_sets_a_address_owner()
    {
        $address = factory(Address::class)->create();
        $address->setOwner('foo', 'bar');
        $this->assertEquals('foo', $address->owner_type);
        $this->assertEquals('bar', $address->owner_id);
    }

    /**
     * @test
     */
    public function it_gets_address_country()
    {
        $country = \App\Country::all()->random();
        $address = factory(Address::class)->create([
            'country_id' => $country->id
        ]);
        $this->assertEquals($country->name, $address->country);
    }

    /**
     * @test
     */
    public function it_sets_an_address_as_primary()
    {
        $address = factory(Address::class)->create();
        $this->assertNull($address->primary);
        $address->setAsPrimary();
        $this->assertEquals(1, $address->primary);
    }

    /**
     * @test
     */
    public function it_unsets_primary_for_all_addresses_belonging_to_same_owner()
    {
        $vendor = factory(Vendor::class)->create();
        $this->assertEmpty($vendor->addresses);
        for($i = 0; $i < 3; $i ++) {
            $address = factory(Address::class)->create();
            $vendor->addAddress($address);
            // manually set each as primary
            $address->primary = 1;
            $address->save();
        }

        $randomAddress = Vendor::find($vendor->id)->addresses->random();
        $randomAddress->unsetPrimaryForAllAddresses();

        foreach (Vendor::find($vendor->id)->addresses as $address) {
            $this->assertEquals(0, $address->primary);
        }
    }

    /**
     * @test
     */
    public function it_unsets_address_primary()
    {
        $address = factory(Address::class)->create(['primary' => 1]);
        $address->unsetPrimary();
        $this->assertEquals(0, $address->primary);
    }
}

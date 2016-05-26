<?php

use App\BankAccount;
use App\Company;
use App\PurchaseOrder;
use App\User;
use App\Vendor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;

class VendorTest extends TestCase
{

    protected static $user;

    protected static $vendor;

    public function setUp()
    {
        parent::setUp();

        static::$user = factory(User::class)->create();
        static::$vendor = factory(Vendor::class)->create();
    }

    /**
     * @test
     */
    public function it_manually_add_a_vendor_to_user_company()
    {
        $request = m::mock(\App\Http\Requests\AddNewVendorRequest::class);
        $request->shouldReceive('input')->with('name')->andReturn('Saber');
        $request->shouldReceive('input')->with('description')->andReturn('Integrated end-to-end purchasing system');

        $this->assertEmpty(User::find(static::$user->id)->company->vendors);
        $this->dontSeeInDatabase('activities', ['name' => 'added_vendor', 'user_id' => static::$user->id]);


        Vendor::add($request, static::$user);

        // Vendor created and attached to User company
        $this->assertEquals('Saber', User::find(static::$user->id)->company->vendors->first()->name);
        $this->seeInDatabase('activities', ['name' => 'added_vendor', 'user_id' => static::$user->id]);
    }

    /**
     * @test
     */
    public function it_automatically_creates_a_vendor_from_a_company()
    {
        $linkedCompany = factory(Company::class)->create();

        $this->assertEmpty(User::find(static::$user->id)->company->vendors);
        $this->dontSeeInDatabase('activities', ['name' => 'added_vendor', 'user_id' => static::$user->id]);

        Vendor::createAndLinkFromCompany(static::$user, $linkedCompany);

        // Created a Vendor - with the same name as the linked company
        $this->assertEquals($linkedCompany->name, User::find(static::$user->id)->company->vendors->first()->name);
        // Activity recorded
        $this->seeInDatabase('activities', ['name' => 'added_vendor', 'user_id' => static::$user->id]);
    }

    /**
     * @test
     */
    public function it_marks_vendor_as_verified()
    {

        $this->assertNull(static::$vendor->verified);
        static::$vendor->verify();
        $this->assertEquals(1, static::$vendor->verified);
    }

    /**
     * @test
     */
    public function it_gets_the_right_vendor_bank_accounts()
    {
        $this->assertEmpty(Vendor::find(static::$vendor->id)->bankAccounts);



        // Make 5 Active accounts
        for ($i = 0; $i < 5; $i++) {
            factory(BankAccount::class)->create(['vendor_id' => static::$vendor->id]);
        }

        // Add inactive account
        factory(BankAccount::class)->create(['vendor_id' => static::$vendor->id, 'active' => 0]);

        // Only returns active ones = 5
        $this->assertCount(5, Vendor::find(static::$vendor->id)->bankAccounts);

        // Get all: 6
        $this->assertCount(6, Vendor::find(static::$vendor->id)->allBankAccounts);
    }

    /**
     * @test
     */
    public function it_returns_amount_of_purchase_orders_made_to_vendor()
    {
        // Start: 0
        $this->assertEmpty(Vendor::find(static::$vendor->id)->purchaseOrders);

        // Make 3
        for ($i = 0; $i < 3; $i++) {
            factory(PurchaseOrder::class)->create(['vendor_id' => static::$vendor->id]);
        }

        // Find 3
        $this->assertCount(3, Vendor::find(static::$vendor->id)->purchaseOrders);
    }

    /**
     * @test
     */
    public function it_calculates_average_purchase_order_total()
    {
        // Create orders: Average = 3000
        factory(PurchaseOrder::class)->create(['vendor_id' => static::$vendor->id, 'total' => 1000]);
        factory(PurchaseOrder::class)->create(['vendor_id' => static::$vendor->id, 'total' => 7000]);
        factory(PurchaseOrder::class)->create(['vendor_id' => static::$vendor->id, 'total' => 1000]);

        $this->assertEquals(3000, Vendor::find(static::$vendor->id)->average_p_o);
    }

}

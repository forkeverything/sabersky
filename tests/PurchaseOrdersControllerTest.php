<?php

use App\Address;
use App\Permission;
use App\User;
use App\Vendor;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurchaseOrdersControllerTest extends TestCase
{

    use WithoutMiddleware, DatabaseTransactions;

    // the route we're posting to submit a PO
    protected static $routePostSubmit = '/purchase_orders/submit';

    // A random purchase request
    protected static $purchaseRequest;

    // POST data that will fail for all fields
    protected static $failingData;

    // POST data that will pass for all fields
    protected static $passingData;

    // A user to run our tests as
    protected static $user;

    /**
     * This gets run BEFORE EVERY test - this is where we
     * want to initialize our variables.
     */
    public function setUp()
    {
        parent::setUp();
        // Bypass Authorization Gate
        Gate::shouldReceive('allows')->andReturn(true);
        // Probably need a PR for our Order to fulfille
        static::$purchaseRequest = factory(\App\PurchaseRequest::class)->create();
        // Set default failing data
        static::$failingData = [
            'foo' => 'bar',
            "po_requires_address" => 1,
            "po_requires_bank_account" => 1,
            "billing_address_same_as_company" => 0,
            "shipping_address_same_as_billing" => 0,
            "line_items" => [
                [
                    'id' => static::$purchaseRequest->id,
                    'state' => 'cancelled',
                    'order_quantity' => null
                ],
                [
                    'id' => static::$purchaseRequest->id,
                    'state' => 'open',
                    "order_quantity" => (static::$purchaseRequest->quantity + 1)
                ],
            ],
            "additional_costs" => [
                []
            ]
        ];
        // Default passing data
        static::$passingData = [
            'vendor_id' => factory(Vendor::class)->create()->id,
            "po_requires_address" => 0,
            "po_requires_bank_account" => 0,
            'currency_id' => 360,
            "billing_address_same_as_company" => 1,
            "shipping_address_same_as_billing" => 1,
            "line_items" => [
                [
                    'id' => static::$purchaseRequest->id,
                    'state' => 'open',
                    'order_quantity' => 5,
                    'order_price' => 1000
                ],
                [
                    'id' => static::$purchaseRequest->id,
                    'state' => 'open',
                    "order_quantity" => (static::$purchaseRequest->quantity),
                    'order_price' => 1000
                ],
            ],
            "additional_costs" => [
                [
                    'name' => 'foo',
                    'type' => '%',
                    'amount' => 100
                ]
            ]
        ];
        // create our user & attach an Address to it's Company
        static::$user = factory(User::class)->create();
        static::$user->company->address()->save(factory(Address::class)->create());
    }

    /**
     * @test
     */
    public function it_validates_PO_submit_data()
    {
        // We don't care about the messages, we just need to know all these will contain errors
        $this->actingAs(static::$user)
            ->post(static::$routePostSubmit, static::$failingData, ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
             ->seeStatusCode(422)
             ->seeJsonStructure([
                 "vendor_id",
                 "currency_id",
                 "vendor_address_id",
                 "vendor_bank_account_id",
                 "billing_phone",
                 'billing_address_1',
                 'billing_city',
                 'billing_zip',
                 'billing_state',
                 'billing_country_id',
                 'shipping_phone',
                 'shipping_address_1',
                 'shipping_city',
                 'shipping_zip',
                 'shipping_state',
                 'shipping_country_id',
                 'line_items.0.order_price',
                 'line_items.1.order_price',
                 'line_items.0.order_quantity',
                 'line_items.0',
                 'line_items.1',
                 'additional_costs.0.name',
                 'additional_costs.0.type',
                 'additional_costs.0.amount'
             ]);

        // Act as user and post it through
        $this->actingAs(static::$user)
             ->post(static::$routePostSubmit, static::$passingData, ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
             ->seeStatusCode(200);
    }

    /**
     * @test
     */
    public function it_creates_a_new_purchase_order()
    {

        // NO PO's yet...
        $this->assertEmpty(static::$user->company->purchaseOrders);

        // Create our PO
        $this->actingAs(static::$user)
             ->post(static::$routePostSubmit, static::$passingData, ['HTTP_X-Requested-With' => 'XMLHttpRequest']);

        // Created one!
        $this->assertCount(1, User::find(static::$user->id)->company->purchaseOrders);
    }


    /**
     * @test
     */
    public function it_creates_new_billing_address()
    {

        // We want new billing address, but we want our shipping to be the same as the new billing
        static::$passingData["billing_address_same_as_company"] = 0;
        static::$passingData["billing_phone"] = '123456789';
        static::$passingData["billing_address_1"] = '17 foo street';
        static::$passingData["billing_address_2"] = 'bar building unit 5';
        static::$passingData["billing_city"] = 'baztown';
        static::$passingData["billing_zip"] = '55555';
        static::$passingData["billing_state"] = 'Western Australia';
        static::$passingData["billing_country_id"] = '36';


        // Create our PO
        $this->actingAs(static::$user)
             ->post(static::$routePostSubmit, static::$passingData, ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
             ->seeStatusCode(200);

        // We have a new billing address
        $this->assertEquals('17 foo street', User::find(static::$user->id)->company->purchaseOrders->first()->billingAddress->address_1);
        // Our shipping is still the same as billing (the new address)
        $this->assertEquals('17 foo street', User::find(static::$user->id)->company->purchaseOrders->first()->shippingAddress->address_1);
    }

    /**
     * @test
     */
    public function it_creates_a_new_different_shipping_address()
    {

        // We want a new shipping address, but we want to use the Company address for billing
        static::$passingData["shipping_address_same_as_billing"] = 0;
        static::$passingData["shipping_phone"] = '123456789';
        static::$passingData["shipping_address_1"] = '17 foo street';
        static::$passingData["shipping_address_2"] = 'bar building unit 5';
        static::$passingData["shipping_city"] = 'baztown';
        static::$passingData["shipping_zip"] = '55555';
        static::$passingData["shipping_state"] = 'Western Australia';
        static::$passingData["shipping_country_id"] = '36';

        // Create our PO
        $this->actingAs(static::$user)
             ->post(static::$routePostSubmit, static::$passingData, ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
             ->seeStatusCode(200);

        // Billing same as company!
        $this->assertEquals(static::$user->company->address->address_1, User::find(static::$user->id)->company->purchaseOrders->first()->billingAddress->address_1);
        // Shipping is new and different
        $this->assertEquals('17 foo street', User::find(static::$user->id)->company->purchaseOrders->first()->shippingAddress->address_1);
    }

    /**
     * @test
     */
    public function it_makes_the_right_amount_of_line_items()
    {
        // reset our line items
        static::$passingData["line_items"] = [];
        // Create 10 available Prs
        $purchaseRequests = factory(\App\PurchaseRequest::class, 10)->create(['state' => 'open']);

        // Add them all to line items
        foreach ($purchaseRequests as $pr) {
            array_push(static::$passingData["line_items"], [
                'id' => $pr->id,
                'state' => 'open',
                'order_quantity' => $pr->quantity,
                'order_price' => 1000,
                'order_payable' => '01/06/1991',
                'order_delivery' => '01/06/1991'
            ]);
        }

        $this->actingAs(static::$user)
             ->post(static::$routePostSubmit, static::$passingData, ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
             ->seeStatusCode(200);

        // Our passing data above has 2 Line Items for the same PR
        $this->assertCount(10, User::find(static::$user->id)->company->purchaseOrders->first()->lineItems);
    }

    /**
     * @test
     */
    public function it_adds_additional_costs()
    {
        static::$passingData["additional_costs"] = [
            [
                'name' => 'foo',
                'type' => '%',
                'amount' => 100
            ],
            [
                'name' => 'bar',
                'type' => '$',
                'amount' => 10000
            ]
        ];
        // Act as user and post it through
        $this->actingAs(static::$user)
             ->post(static::$routePostSubmit, static::$passingData, ['HTTP_X-Requested-With' => 'XMLHttpRequest'])
             ->seeStatusCode(200);
        $this->assertCount(2, User::find(static::$user->id)->company->purchaseOrders->first()->additionalCosts);

        // Quick check to see that '$' is mutated to 'fixed' string
        $this->assertEquals('fixed', User::find(static::$user->id)->company->purchaseOrders->first()->additionalCosts()->where('name', 'bar')->first()->type);
    }

}

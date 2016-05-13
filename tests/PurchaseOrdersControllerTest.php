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
    protected static $routePostSubmit = '/api/purchase_orders/submit';

    // A random purchase requests
    protected static $PR_1;
    protected static $PR_2;

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
        // Need 2 PRs
        static::$PR_1 = factory(\App\PurchaseRequest::class)->create();
        static::$PR_2 = factory(\App\PurchaseRequest::class)->create();

        // Set default failing data
        static::$failingData = [
            'foo' => 'bar',
            "po_requires_address" => 1,
            "po_requires_bank_account" => 1,
            "billing_address_same_as_company" => 0,
            "shipping_address_same_as_billing" => 0,
            "line_items" => [
                [
                    'id' => static::$PR_1->id,
                    'state' => 'cancelled',
                    'order_quantity' => null
                ],
                [
                    'id' => static::$PR_2->id,
                    'state' => 'open',
                    "order_quantity" => (static::$PR_2->quantity + 1)
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
                    'id' => static::$PR_1->id,
                    'state' => 'open',
                    'order_quantity' => 5,
                    'order_price' => 1000
                ],
                [
                    'id' => static::$PR_2->id,
                    'state' => 'open',
                    "order_quantity" => (static::$PR_2->quantity),
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
                 "vendor_address_id",
                 "vendor_bank_account_id",
                 "currency_id",
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
    

}

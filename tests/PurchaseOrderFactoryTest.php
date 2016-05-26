<?php

use App\Address;
use App\Factories\PurchaseOrderFactory;
use App\Http\Requests\SubmitPurchaseOrderRequest;
use App\PurchaseOrder;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;

class PurchaseOrderFactoryTest extends TestCase
{

    use DatabaseTransactions;

    protected static $request;

    protected static $user;

    public function setUp()
    {
        parent::setUp();

        static::$request = m::mock(SubmitPurchaseOrderRequest::class);
        static::$user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function it_creates_a_new_purchase_order()
    {

        static::$request->shouldReceive('input')->with('vendor_id')->andReturn('1');
        static::$request->shouldReceive('input')->with('vendor_address_id')->andReturn('2');
        static::$request->shouldReceive('input')->with('vendor_bank_account_id')->andReturn('3');
        static::$request->shouldReceive('input')->with('currency_id')->andReturn('4');

        $factory = new PurchaseOrderFactory(static::$request, static::$user);
        
        $this->assertNull($factory->purchaseOrder);
        $factory->createPurchaseOrder();
        $this->assertNotNull($factory->purchaseOrder);
    }

    /**
     * @test
     */
    public function it_sets_billing_address_to_company_address()
    {

        static::$request->shouldReceive('input')->with('billing_address_same_as_company')->andReturn(1);

        $companyAddress = factory(\App\Address::class)->create([
            'owner_type' => 'company',
            'owner_id' => static::$user->company_id
        ]);

        $factory = new PurchaseOrderFactory(static::$request, static::$user);
        $this->assertNull($factory->billingAddress);
        $factory->createBillingAddress();
        $this->assertEquals($companyAddress->id, $factory->billingAddress->id);
    }

    /**
     * @test
     */
    public function it_creates_a_new_billing_address()
    {


        static::$request->shouldReceive('input')->with('billing_contact_person')->andReturn('John Doe');
        static::$request->shouldReceive('input')->with("billing_address_same_as_company")->andReturn(0);
        static::$request->shouldReceive('input')->with("billing_phone")->andReturn('123456789');
        static::$request->shouldReceive('input')->with("billing_address_1")->andReturn('17 foo street');
        static::$request->shouldReceive('input')->with("billing_address_2")->andReturn('bar building unit 5');
        static::$request->shouldReceive('input')->with("billing_city")->andReturn('baztown');
        static::$request->shouldReceive('input')->with("billing_zip")->andReturn('55555');
        static::$request->shouldReceive('input')->with("billing_state")->andReturn('Western Australia');
        static::$request->shouldReceive('input')->with("billing_country_id")->andReturn('36');


        $companyAddress = factory(\App\Address::class)->create([
            'owner_type' => 'company',
            'owner_id' => static::$user->company_id
        ]);

        $factory = new PurchaseOrderFactory(static::$request, static::$user);
        $this->assertNull($factory->billingAddress);

        $factory->createBillingAddress();

        $this->assertNotEquals($companyAddress->id, $factory->billingAddress->id);
        $this->assertEquals('17 foo street', $factory->billingAddress->address_1);
    }

    /**
     * @test
     */
    public function it_sets_shipping_address_same_as_billing()
    {

        static::$request->shouldReceive('input')->with('shipping_address_same_as_billing')->andReturn(1);

        $factory = new PurchaseOrderFactory(static::$request);

        $factory->billingAddress = factory(Address::class)->create();

        $this->assertNull($factory->shippingAddress);

        $factory->createShippingAddress();

        $this->assertEquals($factory->shippingAddress->id, $factory->billingAddress->id);
    }

    /**
     * @test
     */
    public function it_creates_new_shipping_address()
    {


        static::$request->shouldReceive('input')->with('shipping_address_same_as_billing')->andReturn(0);

        static::$request->shouldReceive('input')->with('shipping_contact_person')->andReturn('John Doe');
        static::$request->shouldReceive('input')->with('shipping_phone')->andReturn('1234 5678');
        static::$request->shouldReceive('input')->with('shipping_address_1')->andReturn('123 Example St.');
        static::$request->shouldReceive('input')->with('shipping_address_2')->andReturn('Parks Building');
        static::$request->shouldReceive('input')->with('shipping_city')->andReturn('Townsville');
        static::$request->shouldReceive('input')->with('shipping_zip')->andReturn('55555');
        static::$request->shouldReceive('input')->with('shipping_state')->andReturn('FOOBARBAZTASTICO');
        static::$request->shouldReceive('input')->with('shipping_country_id')->andReturn('36');

        $factory = new PurchaseOrderFactory(static::$request);
        $factory->billingAddress = factory(Address::class)->create();

        $this->assertNull($factory->shippingAddress);
        
        $factory->createShippingAddress();
        
        $this->assertNotEquals($factory->shippingAddress->id, $factory->billingAddress->id);

        $this->assertEquals('FOOBARBAZTASTICO', $factory->shippingAddress->state);
    }

    /**
     * @test
     */
    public function it_creates_line_items()
    {


        $lineItems = [];
        $purchaseRequests = factory(\App\PurchaseRequest::class, 10)->create(['state' => 'open']);
        foreach ($purchaseRequests as $pr) {
            array_push($lineItems, [
                'id' => $pr->id,
                'state' => 'open',
                'order_quantity' => $pr->quantity,
                'order_price' => 1000,
                'order_payable' => '01/06/1991',
                'order_delivery' => '01/06/1991'
            ]);
        }

        static::$request->shouldReceive('input')->with('line_items')->andReturn($lineItems);
        $factory = new PurchaseOrderFactory(static::$request, static::$user);
        $factory->purchaseOrder = factory(PurchaseOrder::class)->create();

        $this->assertEmpty(PurchaseOrder::find($factory->purchaseOrder->id)->lineItems);

        $factory->createLineItems();

        $this->assertCount(10, PurchaseOrder::find($factory->purchaseOrder->id)->lineItems);
    }

    /**
     * @test
     */
    public function it_creates_additional_costs()
    {
        $additionalCosts = [
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


        static::$request->shouldReceive('input')->with('additional_costs')->andReturn($additionalCosts);


        $factory = new PurchaseOrderFactory(static::$request);
        $factory->purchaseOrder = factory(PurchaseOrder::class)->create();

        $this->assertEmpty(PurchaseOrder::find($factory->purchaseOrder->id)->additionalCosts);
        $factory->createAdditionalCosts();
        $this->assertCount(2, PurchaseOrder::find($factory->purchaseOrder->id)->additionalCosts);
    }







}

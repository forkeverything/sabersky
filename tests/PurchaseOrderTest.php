<?php

use App\Address;
use App\Company;
use App\Item;
use App\LineItem;
use App\Project;
use App\PurchaseOrder;
use App\Rule;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;

class PurchaseOrderTest extends TestCase
{

    use DatabaseTransactions;

    protected static $purchaseOrder;
    protected static $companyAddress;

    /**
     * Set / run these before each test
     */
    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        static::$purchaseOrder = factory(PurchaseOrder::class)->create();
        static::$purchaseOrder->company->address()->save(factory(Address::class)->create());
        static::$companyAddress = static::$purchaseOrder->company->address;
    }

    /**
     * Generates a new fake address without any owner (parent)
     * model
     *
     * @return mixed
     */
    protected function makeEmptyAddress()
    {
        return factory(Address::class)->create([
            'owner_type' => '',
            'owner_id' => 0
        ]);
    }

    /**
     * Generates a PR using the PurchaseOrder model we're testing
     *
     * @return mixed
     */
    protected function makePR($qty = null)
    {
        $qty = $qty ?: 10;
        return factory(\App\PurchaseRequest::class)->create([
            'quantity' => $qty,
            'project_id' => factory(Project::class)->create(['company_id' => static::$purchaseOrder->company->id])->id,
            'item_id' => factory(Item::class)->create(['company_id' => static::$purchaseOrder->company->id])->id,
            'user_id' => factory(User::class)->create(['company_id' => static::$purchaseOrder->company->id])->id
        ]);
    }

    /**
     * @test
     */
    public function it_attaches_billing_and_shipping_as_company()
    {
        // 1. Billing same as Company & Shipping same as Billing
        $billingAddress = $shippingAddress = static::$companyAddress;
        static::$purchaseOrder->attachBillingAndShippingAddresses($billingAddress, $shippingAddress);
        // should both point to company address
        $this->assertEquals(static::$companyAddress->id, $billingAddress->id);
        $this->assertEquals(static::$companyAddress->id, $shippingAddress->id);
        // billing_address_id != null
        $this->assertNotNull(static::$purchaseOrder->billing_address_id);
        // shipping_address_id != null
        $this->assertNotNull(static::$purchaseOrder->shipping_address_id);
    }

    /**
     * @test
     */
    public function it_attaches_billing_as_company_but_shipping_is_not()
    {
        // 2. Billing same as Company & Shipping NOT same as Billing
        $billingAddress = static::$companyAddress;
        $shippingAddress = $this->makeEmptyAddress();
        static::$purchaseOrder->attachBillingAndShippingAddresses($billingAddress, $shippingAddress);
        // billing points to company
        $this->assertEquals(static::$companyAddress->id, $billingAddress->id);
        // shipping points to different address
        $this->assertNotEquals(static::$purchaseOrder->shippingAddress->id, static::$companyAddress->id);
        // Shipping address belongs to purchaseOrder
        $this->assertEquals('purchase_order', $shippingAddress->owner_type);
    }

    /**
     * @test
     */
    public function it_sets_billing_different_to_company_and_shipping_same_as_billing()
    {
        // 3. Billing NOT same as Company & Shipping same as Billing
        $billingAddress = $this->makeEmptyAddress();
        $shippingAddress = $billingAddress;
        static::$purchaseOrder->attachBillingAndShippingAddresses($billingAddress, $shippingAddress);
        // billing does NOT point to company
        $this->assertNotEquals(static::$purchaseOrder->billingAddress->id, static::$companyAddress->id);
        // Billing Address belongs to PO
        $this->assertEquals('purchase_order', static::$purchaseOrder->billingAddress->owner_type);
        // shipping points to billing
        $this->assertEquals(static::$purchaseOrder->shippingAddress->id, static::$purchaseOrder->billingAddress->id);
    }

    /**
     * @test
     */
    public function it_sets_billing_differnt_to_company_and_shipping_different_to_billing()
    {
        // 4. Billing NOT same as Company & Shipping NOT Same as Billing
        $billingAddress = $this->makeEmptyAddress();
        $shippingAddress = $this->makeEmptyAddress();
        static::$purchaseOrder->attachBillingAndShippingAddresses($billingAddress, $shippingAddress);
        // Billing does NOT point to company
        $this->assertNotEquals(static::$purchaseOrder->billingAddress->id, static::$companyAddress->id);
        // Billing Address belongs to PO
        $this->assertEquals('purchase_order', static::$purchaseOrder->billingAddress->owner_type);
        // shippiing != billing
        $this->assertNotEquals(static::$purchaseOrder->shippingAddress->id, static::$purchaseOrder->billingAddress->id);
        // Shipping address belongs to purchaseOrder
        $this->assertEquals('purchase_order', $shippingAddress->owner_type);
    }

    /**
     * @test
     */
    public function it_updates_purchase_request_quantity_correctly()
    {
        $PR_1 = $this->makePR(10);
        $PR_2 = $this->makePR(30);

        $this->assertEquals(10, \App\PurchaseRequest::find($PR_1->id)->quantity);
        $this->assertEquals(30, \App\PurchaseRequest::find($PR_2->id)->quantity);

        // Make a line items
        factory(LineItem::class)->create([
            'quantity' => $PR_1->quantity,
            'purchase_request_id' => $PR_1->id,
            'purchase_order_id' => static::$purchaseOrder->id
        ]);
        factory(LineItem::class)->create([
            'quantity' => 10,
            'purchase_request_id' => $PR_2->id,
            'purchase_order_id' => static::$purchaseOrder->id
        ]);


        static::$purchaseOrder->updatePurchaseRequests();

        // fulfilled all 10  = 0
        $this->assertEquals(0, \App\PurchaseRequest::find($PR_1->id)->quantity);
        // fulfilled 10 out of 30 = 20 left
        $this->assertEquals(20, \App\PurchaseRequest::find($PR_2->id)->quantity);
    }


    /**
     * @test
     */
    public function it_calls_process_purchase_order_on_rule()
    {
        $company = m::mock(Company::class);
        $rule1 = m::mock(Rule::class);
        $rule2 = m::mock(Rule::class);
        $company->shouldReceive('getRules')
        ->once()
        ->andReturn([$rule1, $rule2]);
        $rule1->shouldReceive('processPurchaseOrder')->once()->with(static::$purchaseOrder);
        $rule2->shouldReceive('processPurchaseOrder')->once()->with(static::$purchaseOrder);
        static::$purchaseOrder->attachRules($company);
    }

    /**
     * @test
     */
    public function it_gets_the_correct_subtotal()
    {
        // 3 Line items - total price should be: 10.5 + 21.12 + 100 = 150
        factory(LineItem::class)->create([
            'quantity' => 3,
            'price' => 3.5,
            'purchase_order_id' => static::$purchaseOrder->id
        ]);
        factory(LineItem::class)->create([
            'quantity' => 4,
            'price' => 5.28,
            'purchase_order_id' => static::$purchaseOrder->id
        ]);
        factory(LineItem::class)->create([
            'quantity' => 2,
            'price' => 50,
            'purchase_order_id' => static::$purchaseOrder->id
        ]);

        $this->assertEquals(131.62, static::$purchaseOrder->subtotal);
    }

    /**
     * @test
     */
    public function it_calculates_correct_total()
    {
        // Create line items totalling subtotal = 100
        factory(LineItem::class, 5)->create([
            'quantity' => 2,
            'price' => 10,
            'purchase_order_id' => static::$purchaseOrder->id
        ]);

        // Create our costs / discount
            // tax = 30% = (0.3 * 100) = 30
            factory(\App\PurchaseOrderAdditionalCost::class)->create([
                'type' => '%',
                'amount' => '30',
                'purchase_order_id' => static::$purchaseOrder->id
            ]);
            // Shipping = 20 (fixed)
            factory(\App\PurchaseOrderAdditionalCost::class)->create([
                'type' => 'fixed',
                'amount' => '20',
                'purchase_order_id' => static::$purchaseOrder->id
            ]);
            // discount = -10% = - (0.1) * 100 = -10
            factory(\App\PurchaseOrderAdditionalCost::class)->create([
                'type' => '%',
                'amount' => '-10',
                'purchase_order_id' => static::$purchaseOrder->id
            ]);
            // Gift voucher = -5
            factory(\App\PurchaseOrderAdditionalCost::class)->create([
                'type' => 'fixed',
                'amount' => '-5',
                'purchase_order_id' => static::$purchaseOrder->id
            ]);

        // Total Cost should be 100 + 30 + 20 - 10 - 5= 140
        $this->assertEquals(135, static::$purchaseOrder->total);
    }

    /**
     * @test
     */
    public function it_auto_approves_the_right_purchase_order()
    {
        // Some rule
        $rule = factory(Rule::class)->create([
            'rule_property_id' => 1,
            'rule_trigger_id' => 1,
            'limit' => 100
        ]);


        // pending, rules - still pending
        $invalidPO = factory(PurchaseOrder::class)->create(['status' => 'pending']);
        // Manually attach a rule * Only for testing, usually we only want the Rule Model to do all the
        // attaching / detaching for consistency's stake
        $invalidPO->rules()->attach($rule);

        // pending, no rules  - approve
        $validPO = factory(PurchaseOrder::class)->create(['status' => 'pending']);

        $invalidPO->tryAutoApprove();
        $validPO->tryAutoApprove();

        $this->assertEquals('pending', PurchaseOrder::find($invalidPO->id)->status);
        $this->assertEquals('approved', PurchaseOrder::find($validPO->id)->status);


    }

}
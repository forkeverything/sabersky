<?php

use App\Item;
use App\LineItem;
use App\PurchaseOrder;
use App\PurchaseRequest;
use App\Rule;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;

class RuleTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function it_calls_right_function_to_process_purchase_order()
    {
        // Create our partial mock
        $rule = m::mock('\App\Rule[checkOrderTotal,checkVendor,checkSingleItem]');

        // Each mocked method should be called once
        $rule->shouldReceive('checkOrderTotal')->once();
        $rule->shouldReceive('checkVendor')->once();
        $rule->shouldReceive('checkSingleItem')->once();

        // Make a PO to be used
        $purchaseOrder = factory(PurchaseOrder::class)->create();

        $rule->processPurchaseOrder($purchaseOrder, 'order_total');
        $rule->processPurchaseOrder($purchaseOrder, 'vendor');
        $rule->processPurchaseOrder($purchaseOrder, 'single_item');
    }

    /**
     * @test
     */
    public function it_checks_trigger_name()
    {
        $rule = factory(Rule::class)->create();
        $this->assertNotEquals('foo', $rule->getTriggerName());
        $this->assertEquals($rule->trigger->name, $rule->getTriggerName());
    }

    /**
     * @test
     */
    public function it_checks_po_order_total_exceeds()
    {

        // we have 2 rules for 2 different currencies
        $rules = [
            factory(Rule::class)->create([
                'rule_property_id' => 1,
                'rule_trigger_id' => 1,
                'limit' => 100,
                'currency_id' => 840
            ]),
            factory(Rule::class)->create([
                'rule_property_id' => 1,
                'rule_trigger_id' => 1,
                'limit' => 50,
                'currency_id' => 360
            ])
        ];



        // Create PO with total = 80
        $underPO = factory(PurchaseOrder::class)->create(['currency_id' => 840]);
        factory(LineItem::class, 4)->create([
            'quantity' => 2,
            'price' => 10,
            'purchase_order_id' => $underPO->id
        ]);
        $underPO->setTotal();

        // PO that is over total, 120
        $overPO = factory(PurchaseOrder::class)->create(['currency_id' => 840]);
        factory(LineItem::class, 6)->create([
            'quantity' => 2,
            'price' => 10,
            'purchase_order_id' => $overPO->id
        ]);
        $overPO->setTotal();

        // No rules for both
        $this->assertCount(0, $underPO->rules);
        $this->assertCount(0, $overPO->rules);

        // check both purchase orders

        foreach ($rules as $rule) {
            $rule->checkOrderTotal($underPO);
            $rule->checkOrderTotal($overPO);
        }

        // Doesn't trip -> not attached
        $this->assertCount(0, PurchaseOrder::find($underPO->id)->rules);
        // Trips -> attached only 1 relevant rule
        $this->assertCount(1, PurchaseOrder::find($overPO->id)->rules);
    }

    /**
     * @test
     */
    public function it_checks_po_vendor_new()
    {
        $rule = factory(Rule::class)->create([
            'rule_property_id' => 2,
            'rule_trigger_id' => 2
        ]);

        $oldVendorPO = factory(PurchaseOrder::class)->create();
        // For the same vendor, create another PO that is already approved - this should stop it from
        // being flagged by our Rule
        $existingApprovedPO = factory(PurchaseOrder::class)->create(['status' => 'approved']);
        $oldVendorPO->vendor->purchaseOrders()->save($existingApprovedPO);

        $newVendorPO = factory(PurchaseOrder::class)->create();

        $this->assertCount(0, PurchaseOrder::find($oldVendorPO->id)->rules);
        $this->assertCount(0, PurchaseOrder::find($newVendorPO->id)->rules);

        $rule->checkVendor($oldVendorPO);
        $rule->checkVendor($newVendorPO);

        $this->assertCount(0, PurchaseOrder::find($oldVendorPO->id)->rules);
        $this->assertCount(1, PurchaseOrder::find($newVendorPO->id)->rules);
    }

    /**
     * @test
     */
    public function it_checks_single_item_exceeds()
    {
        // No single item is allowed to go over 50
        $rule = factory(Rule::class)->create([
            'rule_property_id' => 3,
            'rule_trigger_id' => 3,
            'limit' => 50
        ]);

        $underPO = factory(PurchaseOrder::class)->create();
        factory(LineItem::class, 30)->create([
            'quantity' => 2,
            'price' => 10,
            'purchase_order_id' => $underPO->id
        ]);

        // PO that is over total, 120
        $overPO = factory(PurchaseOrder::class)->create();
        factory(LineItem::class, 1)->create([
            'quantity' => 2,
            'price' => 30,
            'purchase_order_id' => $overPO->id
        ]);


        // No rules for both
        $this->assertCount(0, $underPO->rules);
        $this->assertCount(0, $overPO->rules);

        // check both purchase orders
        $rule->checkSingleItem($underPO);
        $rule->checkSingleItem($overPO);

        // Doesn't trip -> not attached
        $this->assertCount(0, PurchaseOrder::find($underPO->id)->rules);
        // Trips -> attached
        $this->assertCount(1, PurchaseOrder::find($overPO->id)->rules);
    }

    /**
     * @test
     */
    public function it_checks_single_item_new()
    {
        $rule = factory(Rule::class)->create([
            'rule_property_id' => 3,
            'rule_trigger_id' => 4
        ]);

        // New item PO
        $newItem = factory(Item::class)->create();
        $newItemPR = factory(PurchaseRequest::class)->create([
            'item_id' => $newItem->id,
            'project_id' => factory(\App\Project::class)->create([
                'company_id' => $newItem->company_id
            ])->id,
            'user_id' => factory(User::class)->create([
                'company_id' => $newItem->company_id,
            ])->id
        ]);
        $newItemPO = factory(PurchaseOrder::class)->create();
        factory(LineItem::class)->create([
            'purchase_request_id' => $newItemPR->id,
            'purchase_order_id' => $newItemPO->id
        ]);

        // Create an old item (with approved po)
        $oldItem = factory(Item::class)->create();
        $oldItemPR = factory(PurchaseRequest::class)->create([
            'item_id' => $oldItem->id,
            'project_id' => factory(\App\Project::class)->create([
                'company_id' => $oldItem->company_id
            ])->id,
            'user_id' => factory(User::class)->create([
                'company_id' => $oldItem->company_id,
            ])->id
        ]);
        $approvedPO = factory(PurchaseOrder::class)->create(['status' => 'approved']);
        factory(LineItem::class)->create([
            'purchase_request_id' => $oldItemPR->id,
            'purchase_order_id' => $approvedPO->id
        ]);
        $oldItemPO = factory(PurchaseOrder::class)->create();
        factory(LineItem::class)->create([
            'purchase_request_id' => $oldItemPR->id,
            'purchase_order_id' => $oldItemPO->id
        ]);

        // start with no rules attached
        $this->assertCount(0, PurchaseOrder::find($newItemPO->id)->rules);
        $this->assertCount(0, PurchaseOrder::find($oldItemPO->id)->rules);

        // Do our checks
        $rule->checkSingleItem($newItemPO);
        $rule->checkSingleItem($oldItemPO);

        $this->assertCount(1, PurchaseOrder::find($newItemPO->id)->rules);
        $this->assertCount(0, PurchaseOrder::find($oldItemPO->id)->rules);
    }

    /**
     * @test
     */
    public function it_checks_single_item_percentage_over_mean()
    {
        // Trip if 10% over mean
        $rule = factory(Rule::class)->create([
            'rule_property_id' => 3,
            'rule_trigger_id' => 5,
            'limit' => 10
        ]);

        // Create an item
        $item = factory(Item::class)->create();
        // Create PR for item x 40
        $PR = factory(PurchaseRequest::class)->create([
            'item_id' => $item->id,
            'project_id' => factory(\App\Project::class)->create([
                'company_id' => $item->company_id
            ])->id,
            'user_id' => factory(User::class)->create([
                'company_id' => $item->company_id,
            ])->id,
            'quantity' => 40
        ]);


        // PO_1 = $20
        $PO_1 = factory(PurchaseOrder::class)->create();
        factory(LineItem::class)->create([
            'purchase_request_id' => $PR->id,
            'purchase_order_id' => $PO_1->id,
            'quantity' => 10,
            'price' => 20
        ]);

        $rule->checkSingleItem($PO_1);
        // With no mean (ie. 0) - we're not going to attach the PO
        $this->assertCount(0, PurchaseOrder::find($PO_1->id)->rules);

        $PO_1->markApproved();
        // Mean = 20: 10% over mean is $22


        // PO_2 = $15 (safe)
        $PO_2 = factory(PurchaseOrder::class)->create();
        factory(LineItem::class)->create([
            'purchase_request_id' => $PR->id,
            'purchase_order_id' => $PO_2->id,
            'quantity' => 10,
            'price' => 15
        ]);

        $rule->checkSingleItem($PO_2);
        $this->assertCount(0, PurchaseOrder::find($PO_2->id)->rules);

        $PO_2->markApproved();
        // Mean = 17.5 (10% over = 19.25)

        // PO_3 = $19.50 (over)
        $PO_3 = factory(PurchaseOrder::class)->create();
        factory(LineItem::class)->create([
            'purchase_request_id' => $PR->id,
            'purchase_order_id' => $PO_3->id,
            'quantity' => 10,
            'price' => 19.50
        ]);


        $rule->checkSingleItem($PO_3);
        $this->assertCount(1, PurchaseOrder::find($PO_3->id)->rules);

        $PO_3->markApproved();

        // Mean = 18 (10% over = 19.8)

        // PO_4 = 19.75 (under, just!)
        $PO_4 = factory(PurchaseOrder::class)->create();
        factory(LineItem::class)->create([
            'purchase_request_id' => $PR->id,
            'purchase_order_id' => $PO_4->id,
            'quantity' => 10,
            'price' => 19.75
        ]);

        $rule->checkSingleItem($PO_4);
        $this->assertCount(0, PurchaseOrder::find($PO_4->id)->rules);
    }






}

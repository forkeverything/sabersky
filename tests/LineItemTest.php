<?php

use App\LineItem;
use App\PurchaseOrder;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LineItemTest extends TestCase
{

    protected static $user;
    protected static $lineItem;

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        static::$user = factory(User::class)->create();
        static::$lineItem = factory(LineItem::class)->create();
    }

    /**
     * @test
     */
    public function it_gets_the_right_LI_total()
    {
        $lineItem = factory(LineItem::class)->create([
            'price' => '4000',
            'quantity' => 2
        ]);

        $this->assertEquals(8000, $lineItem->total);
    }

    /**
     * @test
     */
    public function it_checks_if_total_exceeds_given_amount()
    {
        // total = 5000
        $lineItem = factory(LineItem::class)->create([
            'price' => '1000',
            'quantity' => 5
        ]);

        $this->assertTrue($lineItem->totalExceeds(4000));
        $this->assertFalse($lineItem->totalExceeds(6000));
    }

    /**
     * @test
     */
    public function it_checks_if_price_is_over_item_mean_by_given_percentage()
    {

        // Create requests & line items so that mean = USD 1,500
        $pr = factory(\App\PurchaseRequest::class)->create();

        $LI_1 = factory(LineItem::class)->create([
            'price' => '1000',
            'quantity' => 1,
            'purchase_request_id' => $pr->id
        ]);

        $LI_1->purchaseOrder->markApproved(static::$user);

        $LI_2 = factory(LineItem::class)->create([
            'price' => '2000',
            'quantity' => 1,
            'purchase_request_id' => $pr->id
        ]);

        $LI_2->purchaseOrder->markApproved(static::$user);


        // 2000 - 1500 = 500 -> 500 / 1500 = 33% over mean

        // Not over by 50%
        $this->assertFalse($LI_2->priceOverMeanPercentageIsGreaterThan(50));

        // more than 30%
        $this->assertTrue($LI_2->priceOverMeanPercentageIsGreaterThan(30));

    }

    /**
     * @test
     */
    public function it_marks_as_paid()
    {
        $this->assertEquals(0, LineItem::find(static::$lineItem->id)->paid);
        $this->dontSeeInDatabase('activities', ['name' => 'paid_lineitem', 'user_id' => static::$user->id]);

        static::$lineItem->markPaid(static::$user);

        $this->assertEquals(1, LineItem::find(static::$lineItem->id)->paid);
        $this->seeInDatabase('activities', ['name' => 'paid_lineitem', 'user_id' => static::$user->id]);
    }

    /**
     * @test
     */
    public function it_only_allows_certain_statuses_when_marking_received()
    {
        $this->assertEquals('unreceived', LineItem::find(static::$lineItem->id)->status);
        $this->setExpectedException(\Symfony\Component\HttpKernel\Exception\HttpExceptionInterface::class);
        static::$lineItem->markReceived('foo', static::$user);
    }

    /**
     * @test
     */
    public function it_marks_accepted()
    {
        $this->assertEquals('unreceived', LineItem::find(static::$lineItem->id)->status);
        $this->dontSeeInDatabase('activities', ['name' => 'received_lineitem', 'user_id' => static::$user->id]);
        static::$lineItem->markReceived('accepted', static::$user);
        $this->assertEquals('accepted', LineItem::find(static::$lineItem->id)->status);
        $this->seeInDatabase('activities', ['name' => 'received_lineitem', 'user_id' => static::$user->id]);
    }

    /**
     * @test
     */
    public function it_marks_returned()
    {
        $this->assertEquals('unreceived', LineItem::find(static::$lineItem->id)->status);
        $this->dontSeeInDatabase('activities', ['name' => 'received_lineitem', 'user_id' => static::$user->id]);
        static::$lineItem->markReceived('returned', static::$user);
        $this->assertEquals('returned', LineItem::find(static::$lineItem->id)->status);
        $this->seeInDatabase('activities', ['name' => 'received_lineitem', 'user_id' => static::$user->id]);
    }

    /**
     * @test
     */
    public function it_creates_LI_and_records_activity()
    {
        $po = factory(PurchaseOrder::class)->create();

        $this->assertEmpty(PurchaseOrder::find($po->id)->lineItems);
        $this->dontSeeInDatabase('activities', ['name' => 'added_lineitem', 'user_id' => static::$user->id]);

        LineItem::add([
            'quantity' => 10,
            'price' => 1000,
            'purchase_order_id' => $po->id,
            'purchase_request_id' => 10
        ], static::$user);

        // Made a line item
        $this->assertCount(1, PurchaseOrder::find($po->id)->lineItems);

        // recorded event
        $this->seeInDatabase('activities', ['name' => 'added_lineitem', 'user_id' => static::$user->id]);
    }

    /**
     * @test
     */
    public function it_records_as_rejected()
    {
        $this->dontSeeInDatabase('activities', ['name' => 'rejected_lineitem', 'user_id' => static::$user->id]);
        static::$lineItem->recordRejectedBy(static::$user);
        $this->seeInDatabase('activities', ['name' => 'rejected_lineitem', 'user_id' => static::$user->id]);
    }
}

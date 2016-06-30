<?php

use App\Item;
use App\Project;
use App\PurchaseRequest;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurchaseRequestTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_marks_state_as_cancelled()
    {
        $pr = factory(PurchaseRequest::class)->create([
            'state' => 'open'
        ]);

        $this->assertEquals('open', $pr->state);

        $pr->cancel();

        $this->assertEquals('cancelled', $pr->state);
    }

    /**
     * @test
     */
    public function it_records_PR_created_activity()
    {
        $user = factory(User::class)->create();

        $this->dontSeeInDatabase('activities', ['name' => 'created_purchase_request', 'user_id' => $user->id]);

        factory(PurchaseRequest::class)->create([
            'user_id' => $user->id
        ]);

        $this->seeInDatabase('activities', ['name' => 'created_purchase_request', 'user_id' => $user->id]);
    }

    /**
     * @test
     */
    public function it_gets_the_right_state()
    {
        $cases = [
            [
                'state' => 'open',
                'quantity' => 3,
                'expectedState' => 'open'
            ],
            [
                'state' => 'cancelled',
                'quantity' => 1,
                'expectedState' => 'cancelled'
            ],
            [
                'state' => 'open',
                'quantity' => 0,
                'expectedState' => 'fulfilled'
            ],
            [
                'state' => 'cancelled',
                'quantity' => 0,
                'expectedState' => 'fulfilled'
            ]
        ];

        foreach ($cases as $case) {
            $pr = factory(PurchaseRequest::class)->create([
                'state' => $case['state'],
                'quantity' => $case['quantity']
            ]);

            $this->assertEquals($case['expectedState'], $pr->state);
            $this->assertTrue($pr->hasState($case['expectedState']));
        }
    }

    /**
     * @test
     */
    public function it_makes_a_new_pr()
    {
        $item = factory(Item::class)->create();
        $project = factory(Project::class)->create([
            'company_id' => $item->company_id
        ]);
        $user = factory(User::class)->create([
            'company_id' => $item->company_id
        ]);
        $request = new \App\Http\Requests\MakePurchaseRequestRequest([
            'quantity' => 10,
            'due' => '25/06/2020',
            'item_id' => $item->id,
            'project_id' => $project->id
        ]);
        $this->assertEmpty(Project::find($project->id)->purchaseRequests);
        PurchaseRequest::make($request, $user);
        $this->assertCount(1, Project::find($project->id)->purchaseRequests);
        $this->assertEquals($user->id, Project::find($project->id)->purchaseRequests->first()->user_id);
    }

    /**
     * @test
     */
    public function it_cancels_a_pr_correctly()
    {
        $openPR = factory(PurchaseRequest::class)->create(['state' => 'open']);
        $fulfilledPR = factory(PurchaseRequest::class)->create(['quantity' => 0]);

        $openPR->cancel();
        $this->assertEquals('cancelled', PurchaseRequest::find($openPR->id)->state);

        $fulfilledPR->cancel();
        $this->assertEquals('fulfilled', PurchaseRequest::find($fulfilledPR->id)->state);
    }

    /**
     * @test
     */
    public function it_reopens_pr_correctly()
    {
        $cancelled = factory(PurchaseRequest::class)->create(['state' => 'cancelled']);
        $fulfilled = factory(PurchaseRequest::class)->create(['quantity' => 0]);

        $cancelled->reopen();
        $this->assertEquals('open', PurchaseRequest::find($cancelled->id)->state);

        $fulfilled->reopen();
        $this->assertEquals('fulfilled', PurchaseRequest::find($fulfilled->id)->state);
    }

    /**
     * @test
     */
    public function it_gets_the_right_quantities()
    {
        $pr = factory(PurchaseRequest::class)->create(['state' => 'open', 'quantity' => 30]);
        for ($i = 0; $i < 3; $i++) {
            factory(\App\LineItem::class)->create([
                'purchase_request_id' => $pr->id,
                'quantity' => 5,
                'purchase_order_id' => factory(\App\PurchaseOrder::class)->create(['status' => 'pending'])->id
            ]);
        }
        $this->assertEquals(15, PurchaseRequest::find($pr->id)->fulfilled_quantity);
        $this->assertEquals(45, PurchaseRequest::find($pr->id)->initial_quantity);
    }
    
    


    
    
    
}

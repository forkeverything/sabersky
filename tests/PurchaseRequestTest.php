<?php

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
}

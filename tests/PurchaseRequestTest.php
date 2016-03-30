<?php

use App\PurchaseRequest;
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
}

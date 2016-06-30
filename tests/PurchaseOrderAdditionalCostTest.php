<?php

use App\PurchaseOrderAdditionalCost;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurchaseOrderAdditionalCostTest extends TestCase
{
    /**
     * @test
     * @group driven
     */
    public function it_sets_correct_type_attribute()
    {
        $types = [
            '%' => '%',
            'fixed' => 'fixed',
            'foo' => 'fixed'
        ];

        foreach ($types as $type => $savedType) {
            $POAC = factory(PurchaseOrderAdditionalCost::class)->create([
                'type' => $type
            ]);

            $this->assertEquals($savedType, $POAC->type);
      }

    }
}

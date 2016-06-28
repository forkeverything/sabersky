<?php

use App\BankAccount;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class BankAccountTest extends TestCase
{
    /**
     * @test
     */
    public function it_deactivates_a_bank_account()
    {
        $bankAccount = factory(BankAccount::class)->create();
        $this->assertEquals(1, BankAccount::find($bankAccount->id)->active);
        $bankAccount->deactivate();
        $this->assertEquals(0, BankAccount::find($bankAccount->id)->active);
    }

    /**
     * @test
     */
    public function it_deletes_or_deactivates_accordingly()
    {
        // Deletes account without order
        $bankAccount = factory(BankAccount::class)->create();
        $this->assertNotNull(BankAccount::find($bankAccount->id));
        $bankAccount->deleteOrDeactivate();
        $this->assertNull(BankAccount::find($bankAccount->id));

        // Account that gets deactivated (with PO)
        $bankAccount = factory(BankAccount::class)->create();
        $purchaseOrder = factory(\App\PurchaseOrder::class)->create([
            'vendor_bank_account_id' => $bankAccount->id
        ]);
        $this->assertNotNull(BankAccount::find($bankAccount->id));
        $bankAccount->deleteOrDeactivate();
        $this->assertNotNull(BankAccount::find($bankAccount->id));
        $this->assertEquals(0, BankAccount::find($bankAccount->id)->active);
    }

    /**
     * @test
     */
    public function it_unsets_as_primary()
    {
        $bankAccount = factory(BankAccount::class)->create();
        $bankAccount->setPrimary();

        $bankAccount->unsetPrimary();
        $this->assertEquals(0, $bankAccount->primary);
    }

    /**
     * @test
     */
    public function it_sets_as_primary()
    {
        $bankAccount = factory(BankAccount::class)->create();
        $this->assertEquals(0, $bankAccount->primary);
        $bankAccount->setPrimary();
        $this->assertEquals(1, $bankAccount->primary);
    }
}

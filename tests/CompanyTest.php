<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
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

    /** @test */
    public function it_creates_an_admin_role_for_a_company()
    {
        $company = factory(App\Company::class)->create();
        $this->assertEmpty($company->roles);
        $company->createAdmin();
        $this->assertEquals('admin', $company->roles()->first()->position);
    }
    
}

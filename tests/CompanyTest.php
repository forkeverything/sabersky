<?php

use App\Company;
use App\Http\Requests\StartProjectRequest;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_registers_a_new_company()
    {
        $name = 'foo';
        $this->assertEmpty(Company::whereName('foo')->first());
        Company::register($name);
        $this->assertEquals('foo', Company::whereName('foo')->first()->name);
    }

    /** @test */
    public function it_creates_an_admin_role_for_a_company()
    {
        $company = factory(App\Company::class)->create();
        $this->assertEmpty($company->roles);
        $company->createAdmin();
        $this->assertEquals('admin', $company->roles()->first()->position);
    }
    
    /** @test */
    public function it_adds_a_user_as_an_employee()
    {
        $company = factory(Company::class)->create();
        $user = factory(User::class)->create();

        $this->assertEmpty(Company::find($company->id)->employees->all());

        $company->addEmployee($user);

        $this->assertNotEmpty(Company::find($company->id)->employees->all());
        $this->assertEquals($user->id, Company::find($company->id)->employees()->first()->id);
    }

    /** @test */
    public function it_finds_a_company_profile_using_name_or_id()
    {
        $company = factory(Company::class)->create();
        $companyId = $company->id;
        $companyName = $company->name;

        $this->assertEquals($companyName, Company::fetchPublicProfile($companyId)->name);
        $this->assertEquals($companyName, Company::fetchPublicProfile($companyName)->name);
    }



}

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

    /** @test */
    public function it_starts_a_project_for_a_company()
    {
        $companyID = factory(Company::class)->create()->id;

        $user = factory(User::class)->create([
            'company_id' => $companyID
        ]);

        $startRequest = (new StartProjectRequest([
            'name' => 'foo',
            'location' => 'bar',
            'description' => 'baz'
        ]));

        $this->assertEmpty(Company::find($companyID)->projects->all());
        $this->assertEmpty(User::find($user->id)->projects->all());

        Company::find($companyID)->startProject($startRequest,  $user);

        $parentModels = [
            User::find($user->id),
            Company::find($companyID)
        ];

        foreach ($parentModels as $parentModel) {
            $this->assertNotEmpty($parentModel->projects->all());
            $this->assertEquals('foo', $parentModel->projects->first()->name);
            $this->assertEquals('bar', $parentModel->projects->first()->location);
            $this->assertEquals('baz', $parentModel->projects->first()->description);
        }
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

}

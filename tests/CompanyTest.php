<?php

use App\Company;
use App\Http\Requests\StartProjectRequest;
use App\Role;
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

    /**
     * @test
     */
    public function it_gets_active_staff()
    {
        $faker = \Faker\Factory::create();
        $company = factory(Company::class)->create();
        $numActive = 20;
        for ($i = 0; $i < 20; $i++) {
            $active = $faker->boolean(35);
            $staff = factory(User::class)->create();
            $company->addEmployee($staff);
            if(! $active) {
                $numActive -= 1;
                $staff->active = 0;
                $staff->save();
            }
        }
        $this->assertCount(20, $company->employees);
        $this->assertEquals($numActive, $company->activeStaff->count());
    }

    /**
     * @test
     */
    public function it_gets_the_main_subscription()
    {
       $subscription = factory(\App\Subscription::class)->create();

        $this->assertEquals(Company::find($subscription->company_id)->subscription->id, $subscription->id);
    }

    /**
     * @test
     */
    public function it_gets_all_roles_except_admin()
    {
        $company = factory(Company::class)->create();
        $company->createAdmin();
        for($i = 0; $i < 5; $i ++) {
            factory(Role::class)->create([
                'company_id' => $company->id
            ]);
        }
        $this->assertCount(6, Company::find($company->id)->roles);
        $this->assertCount(5, Company::find($company->id)->getRolesNotAdmin());
    }

    /**
     * @test
     */
    public function it_fetches_public_profile_info()
    {
        $company = factory(Company::class)->create();

        // Fetch via ID
        $profile = Company::fetchPublicProfile($company->id);
        $this->assertEquals($profile->name, $company->name);

        // Fetch via Name
        $profile = Company::fetchPublicProfile($company->name);
        $this->assertEquals($profile->name, $company->name);
    }

    /**
     * @test
     */
    public function it_gets_a_counries_currencies()
    {
        $company = factory(Company::class)->create();
        $this->assertCount(1, $company->settings->currencies);

        $numPOCurrencies = 0;
        for ($i = 0; $i < 10; $i++) {
            $currencyId = \App\Country::all()->random()->id;
            if($currencyId !== 840) {
                $numPOCurrencies += 1;
                factory(\App\PurchaseOrder::class)->create([
                    'currency_id' => $currencyId,
                    'company_id' => $company->id
                ]);
            }
        }

        $this->assertEquals(1 + $numPOCurrencies, $company->currencies->count());

    }



}

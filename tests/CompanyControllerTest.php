<?php

use App\Company;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CompanyControllerTest extends TestCase
{
    /** @test */
    public function it_redirects_to_correct_page_for_company_registration()
    {

        // User w/o company
        $user = factory(User::class)->create([
            'company_id' => null,
            'role_id' => null
        ]);

        // Visit company - should see company
        $this->actingAs(User::find($user->id))
            ->visit('/company');
        $this->seePageIs('/company');

        // Give the user a company
        $user->company_id = factory(Company::class)->create()->id;
        $user->save();

        // Visit company gets redirected
        $this->actingAs(User::find($user->id))
            ->visit('/company');
        $this->seePageIs('/dashboard');

    }
}

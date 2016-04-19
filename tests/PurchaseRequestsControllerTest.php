<?php

use App\Company;
use App\Item;
use App\Project;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PurchaseRequestsControllerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @test
     */
    public function it_authorizes_a_user_to_view_single_PR()
    {
        // We have a company
        $company = factory(Company::class)->create();
        // A project
        $project = factory(Project::class)->create([
            'company_id' => $company->id
        ]);

        // A PR for project for an item
        $pr = factory(\App\PurchaseRequest::class)->create([
            'project_id' => $project->id,
            'user_id' => factory(User::class)->create([
                'company_id' => $company->id
            ])->id,
            'item_id' => factory(Item::class)->create([
                'company_id' => $company->id
            ])->id
        ]);

        // A User that is a part of the company but NOT a part of the Project
        $user = factory(User::class)->create([
            'company_id' => $company->id
        ]);

        // Playing catch - not authorized!
        try {
            $this->actingAs($user)
                 ->visit('/purchase_requests/' . $pr->id)
                 ->assertResponseStatus(403);
        } catch(\Exception $e) {
            $this->assertContains ("Received status code [403]",$e->getMessage());
        }

        // Add to Project and work some magic
        $project->addTeamMember($user);

        // No exception - authorized!
        $this->actingAs(User::find($user->id))
             ->visit('/purchase_requests/' . $pr->id)
             ->assertResponseOk();
    }
}

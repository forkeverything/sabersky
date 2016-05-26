<?php

use App\Company;
use App\Http\Requests\StartProjectRequest;
use App\Item;
use App\Project;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Mockery as m;

class ProjectTest extends TestCase
{

    use DatabaseTransactions;

    /** @test */
    public function it_adds_a_team_member_to_project()
    {
        // A team member is actually a User Model
        $project = factory(Project::class)->create();

        $this->assertEmpty(Project::find($project->id)->teamMembers->all());

        $user = factory(User::class)->create();

        $project->addTeamMember($user);

        $this->assertNotEmpty(Project::find($project->id)->teamMembers->all());
        $this->assertEquals($user->id, Project::find($project->id)->teamMembers()->first()->id);
    }

    /**
     * @test
     */
    public function it_starts_a_project_for_a_user_and_company()
    {
        $request = m::mock(StartProjectRequest::class);
        $request->shouldReceive('all')
            ->once()
            ->andReturn([
                'name' => 'foo',
                'location' => 'bartown',
                'description' => 'brown foxes, fences and stuff'
            ]);
        $company = factory(Company::class)->create();
        $user = factory(User::class)->create([
            'company_id' => $company->id
        ]);

        $this->assertEmpty(Company::find($company->id)->projects);
        $this->assertEmpty(User::find($user->id)->projects);

        $this->dontSeeInDatabase('activities', ['name' => 'started_project', 'user_id' => $user->id]);

        Project::start($request, $user);

        // Projects get created & it belongs to User's company
        $this->assertEquals('foo', Company::find($company->id)->projects->first()->name);
        // User joins the project too
        $this->assertEquals('foo', User::find($user->id)->projects->first()->name);

        // Started event gets recorded - 'started' by User
        $this->seeInDatabase('activities', ['name' => 'started_project', 'user_id' => $user->id]);
    }
    
}

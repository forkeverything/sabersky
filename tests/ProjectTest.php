<?php

use App\Item;
use App\Project;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

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

    /** @test */
    public function it_attaches_an_item_to_the_project()
    {
        $project = factory(Project::class)->create();
        $item = factory(Item::class)->create();

        $this->assertEmpty(Project::find($project->id)->items->all());

        $project->saveItem($item);

        $this->assertEquals($item->id, Project::find($project->id)->items->first()->id);
    }
}

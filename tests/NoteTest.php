<?php

use App\Note;
use App\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NoteTest extends TestCase
{
    use DatabaseTransactions;
    
    /**
     * Test for auto-recording Activities using Eloquent Model Events: 'created'
     *
     * @test
     */
    public function it_records_created_activity()
    {
        $user = factory(User::class)->create();

        $this->dontSeeInDatabase('activities', ['name' => 'created_note', 'user_id' => $user->id]);

        Note::create([
            'content' => 'foobarz',
            'user_id' => $user->id
        ]);

        $this->seeInDatabase('activities', ['name' => 'created_note', 'user_id' => $user->id]);
    }

    /**
     * Test for auto-recording Activities using Eloquent Model Events: 'updated'
     *
     * @test
     */
    public function it_records_updated_activity()
    {
        $user = factory(User::class)->create();
        $note = factory(Note::class)->create(['user_id' => $user->id]);

        $this->dontSeeInDatabase('activities', ['name' => 'updated_note', 'user_id' => $user->id]);

        $note->update(['content' => 'updated this!']);

        // Notes aren't actually editeable but we'll force it here to test our trait

        $this->seeInDatabase('activities', ['name' => 'updated_note', 'user_id' => $user->id]);
    }

    /**
     * Test for auto-recording Activities using Eloquent Model Events: 'deleted'
     *
     * @test
     */
    public function it_records_deleted_activity()
    {
        $user = factory(User::class)->create();
        $note = factory(Note::class)->create(['user_id' => $user->id]);

        $this->dontSeeInDatabase('activities', ['name' => 'deleted_note', 'user_id' => $user->id]);

        $note->delete();

        $this->seeInDatabase('activities', ['name' => 'deleted_note', 'user_id' => $user->id]);
    }


}

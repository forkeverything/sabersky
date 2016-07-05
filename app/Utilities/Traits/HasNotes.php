<?php


namespace App\Utilities\Traits;


use App\Events\NoteAdded;
use App\Note;
use App\User;
use Illuminate\Support\Facades\Event;

trait HasNotes
{

    /**
     * Model -> Note polymorphic relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function notes()
    {
        return $this->morphMany(Note::class, 'subject');
    }

    /**
     * Create a Note and return it
     *
     * @param $content
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addNote($content, User $user)
    {
         $note = $this->notes()->create([
            'content' => $content,
            'user_id' => $user->id
        ]);

        $note->load('poster');

        Event::fire(new NoteAdded($note));

        return $note;
    }
    
}
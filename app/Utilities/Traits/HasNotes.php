<?php


namespace App\Utilities\Traits;


use App\Note;
use App\User;

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
        return $this->notes()->create([
            'content' => $content,
            'user_id' => $user->id
        ]);
    }
    
}
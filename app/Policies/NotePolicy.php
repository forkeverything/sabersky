<?php

namespace App\Policies;

use App\Note;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NotePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user is allowed to delete a Note
     * 
     * @param User $user
     * @param Note $note
     * @return bool
     */
    public function delete(User $user, Note $note)
    {
        // Admins can delete any notes
        if ($user->hasRole('admin')) return true;
        return $user->id === $note->user_id;
    }
}

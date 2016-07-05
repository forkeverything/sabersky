<?php

namespace App\Events;

use App\Events\Event;
use App\Note;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NoteAdded extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * Note model included in data...
     *
     * @var Note
     */
    public $note;

    /**
     * Channels to broadcast on...
     *
     * @var
     */
    protected $channels;

    /**
     * Create a new event instance.
     *
     * @param Note $note
     */
    public function __construct(Note $note)
    {
        $this->note = $note;
        
        // Send to everyone from same company as the person who posted it
        $this->channels = $note->poster->company->employees->pluck('id')->map(function ($id) {
            return 'private-user.' . $id;
        })->toArray();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return $this->channels;
    }
}

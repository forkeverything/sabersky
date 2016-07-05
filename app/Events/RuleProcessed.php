<?php

namespace App\Events;

use App\Events\Event;
use App\Rule;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RuleProcessed extends Event implements ShouldBroadcast
{
    use SerializesModels;

    /**
     * @var Rule
     */
    public $rule;

    /**
     * The approval of the rule - approved or rejected
     * @var
     */
    public $approval;

    /**
     * Channels to broadcast on
     *
     * @var
     */
    protected $channels;

    /**
     * Create a new event instance.
     *
     * @param Rule $rule
     * @param $approval
     */
    public function __construct(Rule $rule, $approval)
    {
        $this->rule = $rule;
        $this->approval = $approval;
        $this->channels = $this->rule->company->employees->pluck('id')->map(function ($id) {
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

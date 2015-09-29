<?php

namespace Judge\Events\Compile;

use Illuminate\Queue\SerializesModels;
use Judge\Events\Event;
use Judge\Problems\Solution;
use Judge\User;

class Running extends Event
{
    use SerializesModels;

    /**
     * @type \Judge\User
     */
    public $user;
    /**
     * @type \Judge\Problems\Solution
     */
    public $solution;

    /**
     * Create a new event instance.
     *
     * @param \Judge\User              $user
     * @param \Judge\Problems\Solution $solution
     */
    public function __construct(User $user, Solution $solution)
    {
        $this->user = $user;
        $this->solution = $solution;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ["user.{$this->user->id}"];
    }
}

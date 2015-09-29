<?php

namespace Judge\Events\Test;

use Judge\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Judge\User;

class Failed extends Event
{
    use SerializesModels;

    /**
     * @type \Judge\User
     */
    private $user;

    /**
     * Create a new event instance.
     *
     * @param \Judge\User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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

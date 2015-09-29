<?php

namespace Judge\Events\Compile;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Judge\Events\Event;
use Judge\User;

class Failed extends Event implements ShouldBroadcast
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

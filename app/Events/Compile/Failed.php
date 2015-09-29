<?php

namespace Judge\Events\Compile;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Judge\Events\Event;
use Judge\Problems\Solution;
use Judge\User;

class Failed extends Event implements ShouldBroadcast
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
     * @type array
     */
    public $errors;
    /**
     * @type int
     */
    public $status;

    /**
     * Create a new event instance.
     *
     * @param \Judge\User              $user
     * @param \Judge\Problems\Solution $solution
     * @param array                    $errors
     * @param int                      $status
     */
    public function __construct(User $user, Solution $solution, array $errors, $status)
    {
        $this->user = $user;
        $this->solution = $solution;
        $this->errors = $errors;
        $this->status = $status;
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

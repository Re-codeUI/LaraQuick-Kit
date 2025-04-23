<?php

namespace MagicBox\LaraQuickKit\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UserRegistered implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Tentukan channel broadcasting
     */
    public function broadcastOn()
    {
        return ['user-registered'];
    }

    /**
     * Tentukan nama event yang akan disiarkan
     */
    public function broadcastAs()
    {
        return 'UserRegisteredEvent';
    }
}

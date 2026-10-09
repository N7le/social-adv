<?php

namespace App\Events;

use App\Models\Post;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostInteraction
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $actor;

    public Post $post;

    public string $action;

    /**
     * Create a new event instance.
     */
    public function __construct(User $actor, Post $post, string $action)
    {
        $this->actor = $actor;
        $this->post = $post;
        $this->action = $action;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}

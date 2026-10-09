<?php

namespace App\Listeners;

use App\Events\PostInteraction;
use App\Events\PostNotificationCreated;
use Illuminate\Support\Str;

class SendPostNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PostInteraction $event): void
    {
        $owner = $event->post->user;
        if ($owner->is($event->actor)) {
            return;
        }

        $data = [
            'actor_id' => $event->actor->id,
            'post_id' => $event->post->id,
            'action' => $event->action,
            'message' => "{$event->actor->name} {$event->action} ".($event->action == 'liked' ? 'your post' : ' on your post'),
        ];

        $notification = $owner->notifications()->create([
            'id' => Str::uuid(),
            'data' => $data,
            'type' => 'post.'.$event->action,
        ]);

        try {
            event(new PostNotificationCreated($notification));
        } catch (\Exception $e) {
            report($e);
        }

    }
}

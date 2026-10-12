<?php

namespace App\Listeners;

use App\Events\MessageCreated;
use App\Events\MessageSent;

class SendMessageBroadcast
{
    /**
     * Broadcast the new message to the other conversation participant.
     */
    public function handle(MessageSent $event): void
    {
        $recipient = $event->message->conversation->otherParticipant($event->message->sender);

        try {
            event(new MessageCreated($event->message, $recipient));
        } catch (\Exception $e) {
            report($e);
        }
    }
}

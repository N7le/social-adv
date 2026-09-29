<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['sender_id', 'receiver_id', 'status'])]
class FriendRequest extends Model
{

    public function cancelRequest(User $sender, User $reciever): bool
    {
        if ($this->status === 'pending' && $this->sender_id === $sender->id && $this->receiver_id === $reciever->id) {
            $this->status = 'cancelled';
            return $this->save();
        }

        return false;
    }

    // Relationships
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}

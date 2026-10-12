<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Determine whether the user can send a friend request to the given user.
     */
    public function sendFriendRequest(User $user, User $target): bool
    {
        if ($user->id === $target->id || $user->isFriendsWith($target) || $user->isRequestSentTo($target) || $user->isRequestReceivedFrom($target)) {
            return false;
        }

        if ($target->request_audience === 'friends_of_friends') {
            return $user->hasMutual($target);
        }

        return true;
    }

    /**
     * Determine whether the user can send a direct message to the given user.
     */
    public function sendMessage(User $user, User $target): bool
    {
        return $user->id !== $target->id && $user->isFriendsWith($target);
    }
}

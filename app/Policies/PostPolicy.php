<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    /**
     * Determine whether the user can view the post.
     */
    public function view(?User $user, Post $post): bool
    {
        if ($post->privacy === 'public') {
            return true;
        }

        return $user?->is($post->user) || $user?->isFriendsWith($post->user) ?? false;
    }

    /**
     * Determine whether the user can like the post.
     */
    public function like(User $user, Post $post): bool
    {
        return $this->view($user, $post);
    }

    public function share(User $user, Post $post): bool
    {
        return $this->view($user, $post);
    }

    /**
     * Determine whether the user can comment on the post.
     */
    public function comment(User $user, Post $post): bool
    {

        return $this->view($user, $post);
    }
}

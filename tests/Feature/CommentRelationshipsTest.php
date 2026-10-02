<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class CommentRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_comments_can_be_replied_to_at_multiple_levels(): void
    {
        $post = Post::factory()->create();
        $user = User::factory()->create();
        $rootComment = Comment::factory()->for($post)->for($user)->create();
        $reply = Comment::factory()->for($post)->for($user)->create([
            'comment_id' => $rootComment->id,
        ]);
        $nestedReply = Comment::factory()->for($post)->for($user)->create([
            'comment_id' => $reply->id,
        ]);

        $this->assertSame($post->id, $nestedReply->post->id);
        $this->assertSame($user->id, $nestedReply->user->id);
        $this->assertSame($reply->id, $nestedReply->parent->id);
        $this->assertSame($rootComment->id, $nestedReply->parent->parent->id);
        $this->assertSame($reply->id, $rootComment->replies()->firstOrFail()->id);
        $this->assertSame($nestedReply->id, $reply->replies()->firstOrFail()->id);
        $this->assertCount(3, $post->comments);
        $this->assertCount(3, $user->comments);
    }

    public function test_users_can_comment_on_public_posts_but_not_private_posts_they_cannot_view(): void
    {
        $user = User::factory()->create();
        $publicPost = Post::factory()->create(['privacy' => 'public']);
        $privatePost = Post::factory()->create(['privacy' => 'private']);

        $this->assertTrue(Gate::forUser($user)->allows('comment', $publicPost));
        $this->assertFalse(Gate::forUser($user)->allows('comment', $privatePost));
    }
}

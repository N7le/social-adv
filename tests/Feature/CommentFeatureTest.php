<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class CommentFeatureTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_a_user_can_create_a_comment_on_a_visible_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'public']);

        Livewire::actingAs($user)
            ->test('comment-section', ['postId' => $post->id])
            ->set('body', 'A useful comment.')
            ->call('createComment')
            ->assertHasNoErrors()
            ->assertSee('A useful comment.')
            ->assertSee('1 comment')
            ->assertSet('body', '')
            ->assertSet('replyToId', null);

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'comment_id' => null,
            'body' => 'A useful comment.',
        ]);
    }

    public function test_a_user_can_reply_to_a_comment_and_a_reply(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'public']);
        $comment = Comment::factory()->for($post)->create(['body' => 'First comment']);
        $reply = Comment::factory()->for($post)->create([
            'comment_id' => $comment->id,
            'body' => 'First reply',
        ]);

        Livewire::actingAs($user)
            ->test('comment-section', ['postId' => $post->id])
            ->assertSee('First comment')
            ->assertSee('First reply')
            ->call('startReply', $reply->id)
            ->assertSet('replyToId', $reply->id)
            ->assertSee('Replying to '.$reply->user->name)
            ->set('body', 'Reply to a reply')
            ->call('createComment')
            ->assertHasNoErrors()
            ->assertSee('Reply to a reply')
            ->assertSee('3 comments');

        $this->assertDatabaseHas('comments', [
            'post_id' => $post->id,
            'user_id' => $user->id,
            'comment_id' => $reply->id,
            'body' => 'Reply to a reply',
        ]);
    }

    public function test_comment_body_is_required_and_limited_to_2000_characters(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'public']);

        Livewire::actingAs($user)
            ->test('comment-section', ['postId' => $post->id])
            ->set('body', '')
            ->call('createComment')
            ->assertHasErrors(['body' => 'required'])
            ->set('body', str_repeat('a', 2001))
            ->call('createComment')
            ->assertHasErrors(['body' => 'max']);

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_a_user_cannot_comment_on_a_post_they_cannot_view(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'private']);

        Livewire::actingAs($user)
            ->test('comment-section', ['postId' => $post->id])
            ->set('body', 'Unauthorized comment')
            ->call('createComment')
            ->assertForbidden();

        $this->assertDatabaseCount('comments', 0);
    }

    public function test_a_comment_cannot_be_replied_to_from_a_different_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'public']);
        $otherPost = Post::factory()->create(['privacy' => 'public']);
        $foreignComment = Comment::factory()->for($otherPost)->create();

        Livewire::actingAs($user)
            ->test('comment-section', ['postId' => $post->id])
            ->call('startReply', $foreignComment->id)
            ->assertNotFound();

        $this->assertDatabaseCount('comments', 1);
    }
}

<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostLikeTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_a_user_can_like_and_unlike_a_visible_post(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'public']);

        $component = Livewire::actingAs($user)
            ->test('like-button', ['post' => $post])
            ->call('toggleLike')
            ->assertSee('Liked');

        $this->assertDatabaseHas('likes', ['user_id' => $user->id, 'post_id' => $post->id]);

        $component->call('toggleLike')->assertDontSee('Liked');

        $this->assertDatabaseMissing('likes', ['user_id' => $user->id, 'post_id' => $post->id]);
    }

    public function test_toggling_a_like_signals_the_like_count_to_refresh(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'public']);

        Livewire::actingAs($user)
            ->test('like-button', ['post' => $post])
            ->call('toggleLike')
            ->assertDispatched('likes-updated.'.$post->id);
    }

    public function test_the_like_count_rerenders_when_signalled(): void
    {
        $post = Post::factory()->create(['privacy' => 'public']);

        $component = Livewire::test('like-count', ['post' => $post])->assertSee('0 likes');

        $post->likes()->attach(User::factory()->count(2)->create());

        $component->assertSee('0 likes')
            ->dispatch('likes-updated.'.$post->id)
            ->assertSee('2 likes');
    }

    public function test_the_like_count_uses_the_loaded_count_until_signalled(): void
    {
        $post = Post::factory()->create(['privacy' => 'public']);
        $post->likes()->attach(User::factory()->create());
        $post = Post::with('user')->findOrFail($post->id);
        $post->likes()->attach(User::factory()->create());

        $component = Livewire::test('like-count', ['post' => $post]);

        $component->assertSee('1 like')
            ->assertDontSee('2 likes')
            ->dispatch('likes-updated.'.$post->id)
            ->assertSee('2 likes');
    }

    public function test_a_user_can_only_like_a_post_once(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'public']);
        $post->likes()->attach($user);

        $this->expectException(UniqueConstraintViolationException::class);

        $post->likes()->attach($user);
    }

    public function test_a_user_cannot_like_a_post_they_cannot_view(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'private']);

        Livewire::actingAs($user)
            ->test('like-button', ['post' => $post])
            ->call('toggleLike')
            ->assertForbidden();

        $this->assertDatabaseCount('likes', 0);
    }

    public function test_a_friend_can_like_a_private_post(): void
    {
        $author = User::factory()->create();
        $friend = User::factory()->create();
        $author->friendRequestsSent()->create(['receiver_id' => $friend->id, 'status' => 'accepted']);
        $post = Post::factory()->for($author)->create(['privacy' => 'private']);

        Livewire::actingAs($friend)
            ->test('like-button', ['post' => $post])
            ->call('toggleLike')
            ->assertSee('Liked');

        $this->assertSame(1, $post->likes()->count());
    }
}

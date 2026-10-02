<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PostShareTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_a_user_can_share_a_public_post_without_creating_duplicate_shares(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'public']);

        $component = Livewire::actingAs($user)
            ->test('share-button', ['post' => $post])
            ->assertSee(route('home').'#post-'.$post->id)
            ->call('sharePost')
            ->assertSee('Shared');

        $this->assertDatabaseHas('post_shares', ['user_id' => $user->id, 'post_id' => $post->id]);

        $component->call('sharePost')->assertSee('Shared');

        $this->assertSame(1, $post->shares()->count());
    }

    public function test_sharing_a_post_signals_the_share_count_to_refresh(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'public']);

        Livewire::actingAs($user)
            ->test('share-button', ['post' => $post])
            ->call('sharePost')
            ->assertDispatched('shares-updated.'.$post->id);
    }

    public function test_the_share_count_rerenders_when_signalled(): void
    {
        $post = Post::factory()->create(['privacy' => 'public']);

        $component = Livewire::test('share-count', ['post' => $post])->assertSee('0 shares');
        $post->shares()->attach(User::factory()->count(2)->create());

        $component->dispatch('shares-updated.'.$post->id)->assertSee('2 shares');
    }

    public function test_a_user_cannot_share_a_private_post_they_cannot_view(): void
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['privacy' => 'private']);

        Livewire::actingAs($user)
            ->test('share-button', ['post' => $post])
            ->call('sharePost')
            ->assertForbidden();

        $this->assertDatabaseCount('post_shares', 0);
    }
}

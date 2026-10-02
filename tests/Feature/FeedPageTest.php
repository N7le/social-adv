<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class FeedPageTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_a_guest_sees_public_posts_newest_first_with_author_links(): void
    {
        $author = User::factory()->create(['name' => 'Feed Author', 'username' => 'feed.author']);
        Post::factory()->for($author)->create([
            'body' => 'Older feed post',
            'privacy' => 'public',
            'created_at' => now()->subDay(),
        ]);
        Post::factory()->for($author)->create([
            'body' => 'Newer feed post',
            'privacy' => 'public',
        ]);
        Post::factory()->for($author)->create(['body' => 'Hidden private post', 'privacy' => 'private']);
        Post::factory()->for($author)->create(['body' => 'Deleted post', 'deleted_at' => now()]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['Newer feed post', 'Older feed post'])
            ->assertSee('Feed Author')
            ->assertSee(route('profile.show', ['username' => 'feed.author']))
            ->assertSee('No comments yet.')
            ->assertDontSee('Hidden private post')
            ->assertDontSee('Deleted post')
            ->assertDontSee('Send comment');
    }

    public function test_a_signed_in_viewer_sees_their_own_and_friends_private_posts_only(): void
    {
        $viewer = User::factory()->create();
        $friend = User::factory()->create();
        $stranger = User::factory()->create();
        $viewer->friendRequestsSent()->create(['receiver_id' => $friend->id, 'status' => 'accepted']);
        $ownPost = Post::factory()->for($viewer)->create(['body' => 'My private post', 'privacy' => 'private']);
        $friendPost = Post::factory()->for($friend)->create(['body' => 'Friend private post', 'privacy' => 'private']);
        Post::factory()->for($stranger)->create(['body' => 'Stranger private post', 'privacy' => 'private']);

        $this->actingAs($viewer)->get(route('home'))
            ->assertOk()
            ->assertSee('My private post')
            ->assertSee('Friend private post')
            ->assertDontSee('Stranger private post')
            ->assertSee('Like')
            ->assertSee('Send comment')
            ->assertSee('id="comment-post-'.$ownPost->id.'"', false)
            ->assertSee('id="comment-post-'.$friendPost->id.'"', false);
    }

    public function test_the_feed_has_an_empty_state(): void
    {
        $this->get(route('home'))->assertOk()->assertSee('No posts yet.');
    }

    public function test_both_pages_display_post_images_and_videos_and_escape_body_text(): void
    {
        $author = User::factory()->create();
        Post::factory()->for($author)->create([
            'body' => '<script>alert("post")</script>',
            'privacy' => 'public',
            'media_path' => 'posts/photo.jpg',
            'media_type' => 'image/jpeg',
        ]);
        Post::factory()->for($author)->create([
            'privacy' => 'public',
            'media_path' => 'posts/video.mp4',
            'media_type' => 'video/mp4',
        ]);

        foreach ([route('home'), route('profile.show', ['username' => $author->username])] as $page) {
            $this->get($page)
                ->assertOk()
                ->assertSee(url('/file/posts/photo.jpg'))
                ->assertSee(url('/file/posts/video.mp4'))
                ->assertSee('<video', false)
                ->assertSee('<script>alert("post")</script>')
                ->assertDontSee('<script>alert("post")</script>', false);
        }
    }
}

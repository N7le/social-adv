<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ProfilePageTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_a_profile_displays_the_user_and_all_of_their_posts_newest_first(): void
    {
        $profileUser = User::factory()->create([
            'name' => 'Profile Person',
            'username' => 'profile.person',
            'bio' => 'A profile biography.',
            'work' => 'Product designer',
            'education' => 'Design School',
            'city' => 'Manchester',
            'website' => 'https://example.com',
        ]);
        Post::factory()->for($profileUser)->create([
            'body' => 'Older public post',
            'privacy' => 'public',
            'created_at' => '2026-09-01 12:00:00',
        ]);
        Post::factory()->for($profileUser)->create([
            'body' => 'Newer private post',
            'privacy' => 'private',
            'created_at' => '2026-09-02 12:00:00',
        ]);

        $this->get(route('profile.show', ['username' => 'profile.person']))
            ->assertOk()
            ->assertSee('Profile Person')
            ->assertSee('@profile.person')
            ->assertSee('A profile biography.')
            ->assertSeeInOrder(['Newer private post', 'Older public post'])
            ->assertSee('203 likes')
            ->assertSee('31 comments')
            ->assertSee('6 shares')
            ->assertSee('Like')
            ->assertSee('Comment')
            ->assertSee('Share');
    }

    public function test_an_owner_sees_profile_editing_controls(): void
    {
        $profileUser = User::factory()->create(['username' => 'profile.owner']);

        $this->actingAs($profileUser)
            ->get(route('profile.show', ['username' => 'profile.owner']))
            ->assertSee('Edit Profile')
            ->assertSee('Edit cover')
            ->assertSee('Edit details');
    }

    public function test_another_user_does_not_see_profile_editing_controls(): void
    {
        $profileUser = User::factory()->create(['username' => 'profile.person']);
        $viewer = User::factory()->create();

        $this->actingAs($viewer)
            ->get(route('profile.show', ['username' => 'profile.person']))
            ->assertDontSee('Edit Profile')
            ->assertDontSee('Edit cover')
            ->assertDontSee('Edit details');
    }

    public function test_the_profile_shortcut_redirects_to_the_authenticated_users_profile(): void
    {
        $user = User::factory()->create(['username' => 'signed.in']);

        $this->actingAs($user)
            ->get(route('profile'))
            ->assertRedirect(route('profile.show', ['username' => 'signed.in']));
    }

    public function test_an_unknown_username_returns_not_found(): void
    {
        $this->get('/profile/missing-user')->assertNotFound();
    }
}

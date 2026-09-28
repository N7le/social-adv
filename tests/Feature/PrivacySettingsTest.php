<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PrivacySettingsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_settings_page_shows_privacy_settings(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('settings'))
            ->assertOk()
            ->assertSeeLivewire('privacy-settings');
    }

    public function test_a_user_can_save_and_reload_privacy_preferences(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('privacy-settings')
            ->assertSet('posts_audience', 'public')
            ->assertSet('request_audience', 'everyone')
            ->set('posts_audience', 'private')
            ->set('request_audience', 'friends_of_friends')
            ->call('save')
            ->assertHasNoErrors()
            ->assertSee('Your privacy settings have been updated.');

        $this->assertSame('private', $user->refresh()->posts_audience);
        $this->assertSame('friends_of_friends', $user->request_audience);

        Livewire::actingAs($user)
            ->test('privacy-settings')
            ->assertSet('posts_audience', 'private')
            ->assertSet('request_audience', 'friends_of_friends');
    }

    public function test_invalid_privacy_preferences_are_not_saved(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('privacy-settings')
            ->set('posts_audience', 'friends')
            ->set('request_audience', 'nobody')
            ->call('save')
            ->assertHasErrors(['posts_audience', 'request_audience']);

        $this->assertSame('public', $user->refresh()->posts_audience);
        $this->assertSame('everyone', $user->request_audience);
    }

    public function test_saved_audience_is_selected_when_composing_a_post(): void
    {
        $user = User::factory()->create(['posts_audience' => 'private']);

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('value="private" selected', false);
    }

    public function test_a_user_can_create_a_friends_only_post(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('posts.store'), ['body' => 'Friends only', 'privacy' => 'private'])
            ->assertRedirect();

        $this->assertDatabaseHas('posts', [
            'user_id' => $user->id,
            'body' => 'Friends only',
            'privacy' => 'private',
        ]);
    }

    public function test_a_post_cannot_use_friends_as_a_stored_privacy_value(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('posts.store'), ['body' => 'Friends only', 'privacy' => 'friends'])
            ->assertSessionHasErrors('privacy');

        $this->assertDatabaseMissing('posts', ['user_id' => $user->id, 'body' => 'Friends only']);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ProfileSettingsTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_settings_page_shows_the_profile_settings_component(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('settings'))
            ->assertOk()
            ->assertSeeLivewire('profile-settings');
    }

    public function test_a_user_can_update_their_profile_settings(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile-settings')
            ->set('name', 'New Name')
            ->set('username', 'new.username')
            ->set('bio', 'A short bio.')
            ->set('work', 'Designer')
            ->set('education', 'Some School')
            ->set('city', 'Manchester')
            ->set('website', 'https://example.com')
            ->set('birthday', '1993-04-22')
            ->call('save')
            ->assertHasNoErrors();

        $user->refresh();

        $this->assertSame('New Name', $user->name);
        $this->assertSame('new.username', $user->username);
        $this->assertSame('A short bio.', $user->bio);
        $this->assertSame('Designer', $user->work);
        $this->assertSame('Some School', $user->education);
        $this->assertSame('Manchester', $user->city);
        $this->assertSame('https://example.com', $user->website);
        $this->assertSame('1993-04-22', $user->birthday->toDateString());
    }

    public function test_a_user_can_keep_their_own_username(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile-settings')
            ->set('name', 'Changed')
            ->call('save')
            ->assertHasNoErrors();

        $this->assertSame('Changed', $user->refresh()->name);
    }

    public function test_profile_settings_are_validated(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Livewire::actingAs($user)
            ->test('profile-settings')
            ->set('name', '')
            ->set('username', $otherUser->username)
            ->set('bio', str_repeat('a', 161))
            ->set('website', 'not-a-url')
            ->set('birthday', now()->addDay()->toDateString())
            ->call('save')
            ->assertHasErrors(['name', 'username', 'bio', 'website', 'birthday']);
    }
}

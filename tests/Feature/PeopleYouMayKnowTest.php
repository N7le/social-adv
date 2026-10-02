<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class PeopleYouMayKnowTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_authenticated_feed_renders_suggested_people_and_mutual_friend_counts(): void
    {
        $viewer = User::factory()->create();
        $mutualFriend = User::factory()->create();
        $suggestedUser = User::factory()->create(['name' => 'Suggested Person']);

        $viewer->friendRequestsSent()->create([
            'receiver_id' => $mutualFriend->id,
            'status' => 'accepted',
        ]);
        $mutualFriend->friendRequestsSent()->create([
            'receiver_id' => $suggestedUser->id,
            'status' => 'accepted',
        ]);

        $this->actingAs($viewer)
            ->get(route('home'))
            ->assertSee('Suggested Person')
            ->assertSee('1 mutual friend');
    }

    public function test_feed_does_not_render_the_suggestions_panel_when_there_are_no_suggestions(): void
    {
        $viewer = User::factory()->create();

        $this->actingAs($viewer)
            ->get(route('home'))
            ->assertDontSee('People you may know');
    }

    public function test_people_you_may_know_excludes_users_with_pending_requests(): void
    {
        $viewer = User::factory()->create();
        $mutualFriend = User::factory()->create();
        $outgoingPendingUser = User::factory()->create();
        $incomingPendingUser = User::factory()->create();

        $viewer->friendRequestsSent()->create([
            'receiver_id' => $mutualFriend->id,
            'status' => 'accepted',
        ]);
        $mutualFriend->friendRequestsSent()->createMany([
            ['receiver_id' => $outgoingPendingUser->id, 'status' => 'accepted'],
            ['receiver_id' => $incomingPendingUser->id, 'status' => 'accepted'],
        ]);
        $viewer->friendRequestsSent()->create([
            'receiver_id' => $outgoingPendingUser->id,
            'status' => 'pending',
        ]);
        $incomingPendingUser->friendRequestsSent()->create([
            'receiver_id' => $viewer->id,
            'status' => 'pending',
        ]);

        $suggestions = $viewer->peopleYouMayKnow();

        $this->assertFalse($suggestions->contains('id', $outgoingPendingUser->id));
        $this->assertFalse($suggestions->contains('id', $incomingPendingUser->id));
    }

    public function test_suggestion_component_sends_a_friend_request(): void
    {
        $viewer = User::factory()->create();
        $suggestedUser = User::factory()->create();

        $this->actingAs($viewer);

        Livewire::test('add-friend-simple', ['target' => $suggestedUser])
            ->call('addFriend')
            ->assertSee('Cancel friend request to '.$suggestedUser->name);

        $this->assertDatabaseHas('friend_requests', [
            'sender_id' => $viewer->id,
            'receiver_id' => $suggestedUser->id,
            'status' => 'pending',
        ]);
    }

    public function test_suggestion_component_cancels_the_viewers_pending_friend_request(): void
    {
        $viewer = User::factory()->create();
        $suggestedUser = User::factory()->create();
        $viewer->friendRequestsSent()->create([
            'receiver_id' => $suggestedUser->id,
            'status' => 'pending',
        ]);

        $this->actingAs($viewer);

        Livewire::test('add-friend-simple', ['target' => $suggestedUser])
            ->assertSee('Cancel friend request to')
            ->call('cancelRequest')
            ->assertSee('Add '.$suggestedUser->name.' as a friend');

        $this->assertDatabaseMissing('friend_requests', [
            'sender_id' => $viewer->id,
            'receiver_id' => $suggestedUser->id,
            'status' => 'pending',
        ]);
    }

    public function test_suggestion_component_does_not_create_a_duplicate_pending_request(): void
    {
        $viewer = User::factory()->create();
        $suggestedUser = User::factory()->create();
        $viewer->friendRequestsSent()->create([
            'receiver_id' => $suggestedUser->id,
            'status' => 'pending',
        ]);

        $this->actingAs($viewer);

        Livewire::test('add-friend-simple', ['target' => $suggestedUser])
            ->call('addFriend');

        $this->assertSame(1, $viewer->friendRequestsSent()->where('receiver_id', $suggestedUser->id)->count());
    }

    public function test_suggestion_component_cannot_send_a_request_without_permission(): void
    {
        $viewer = User::factory()->create();
        $suggestedUser = User::factory()->create(['request_audience' => 'friends_of_friends']);

        $this->actingAs($viewer);

        Livewire::test('add-friend-simple', ['target' => $suggestedUser])
            ->call('addFriend')
            ->assertForbidden();

        $this->assertDatabaseMissing('friend_requests', [
            'sender_id' => $viewer->id,
            'receiver_id' => $suggestedUser->id,
        ]);
    }
}

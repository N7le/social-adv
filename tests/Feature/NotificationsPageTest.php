<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationsPageTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function createNotification(User $owner, User $actor, string $action, array $overrides = []): void
    {
        $owner->notifications()->create(array_merge([
            'id' => Str::uuid(),
            'type' => 'post.'.$action,
            'data' => [
                'actor_id' => $actor->id,
                'post_id' => 1,
                'action' => $action,
                'message' => "{$actor->name} {$action} your post",
            ],
        ], $overrides));
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('notifications'))->assertRedirect(route('login'));
    }

    public function test_user_sees_their_post_notifications_grouped_by_read_state(): void
    {
        $user = User::factory()->create();
        $liker = User::factory()->create(['name' => 'Lina Liker', 'username' => 'lina.liker']);
        $commenter = User::factory()->create(['name' => 'Carl Commenter', 'username' => 'carl.commenter']);
        $this->createNotification($user, $liker, 'liked');
        $this->createNotification($user, $commenter, 'commented', ['read_at' => now()]);

        $this->actingAs($user)->get(route('notifications'))
            ->assertOk()
            ->assertSee('New')
            ->assertSee('Earlier')
            ->assertSee('Lina Liker')
            ->assertSee('liked your post.')
            ->assertSee('Carl Commenter')
            ->assertSee('commented on your post.')
            ->assertSee(route('profile.show', ['username' => 'lina.liker']));
    }

    public function test_friend_request_notifications_are_not_shown(): void
    {
        $user = User::factory()->create();
        $sender = User::factory()->create(['name' => 'Frank Friend']);
        $user->notifications()->create([
            'id' => Str::uuid(),
            'type' => 'friend.request',
            'data' => [
                'actor_id' => $sender->id,
                'message' => "{$sender->name} sent you a friend request",
            ],
        ]);

        $this->actingAs($user)->get(route('notifications'))
            ->assertOk()
            ->assertDontSee('Frank Friend')
            ->assertSee('No notifications yet.');
    }

    public function test_notifications_from_other_users_are_not_shown(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $actor = User::factory()->create(['name' => 'Olly Outsider']);
        $this->createNotification($other, $actor, 'liked');

        $this->actingAs($user)->get(route('notifications'))
            ->assertOk()
            ->assertDontSee('Olly Outsider')
            ->assertSee('No notifications yet.');
    }

    public function test_unread_filter_only_shows_unread_notifications(): void
    {
        $user = User::factory()->create();
        $liker = User::factory()->create(['name' => 'Lina Liker']);
        $commenter = User::factory()->create(['name' => 'Carl Commenter']);
        $this->createNotification($user, $liker, 'liked');
        $this->createNotification($user, $commenter, 'commented', ['read_at' => now()]);

        $this->actingAs($user)->get(route('notifications', ['filter' => 'unread']))
            ->assertOk()
            ->assertSee('Lina Liker')
            ->assertDontSee('Carl Commenter');
    }

    public function test_mark_all_as_read_marks_unread_notifications(): void
    {
        $user = User::factory()->create();
        $liker = User::factory()->create();
        $this->createNotification($user, $liker, 'liked');

        $this->actingAs($user)->post(route('notifications.readAll'))
            ->assertRedirect(route('notifications'));

        $this->assertSame(0, $user->unreadNotifications()->count());
    }
}

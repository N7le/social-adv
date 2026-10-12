<?php

namespace Tests\Feature;

use App\Events\MessageCreated;
use App\Events\MessageSent;
use App\Listeners\SendMessageBroadcast;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;

class MessagingTest extends TestCase
{
    use LazilyRefreshDatabase;

    private function createFriends(): array
    {
        $user = User::factory()->create();
        $friend = User::factory()->create();
        $user->friendRequestsSent()->create(['receiver_id' => $friend->id, 'status' => 'accepted']);

        return [$user, $friend];
    }

    public function test_messages_page_requires_authentication(): void
    {
        $this->get(route('messages'))->assertRedirect(route('login'));
    }

    public function test_a_friend_can_start_a_conversation(): void
    {
        [$user, $friend] = $this->createFriends();

        Event::fake([MessageSent::class]);

        $this->actingAs($user);

        Livewire::test('chat')
            ->set('newMessageRecipientId', $friend->id)
            ->set('newMessageBody', 'Hello friend!')
            ->call('startConversation')
            ->assertHasNoErrors()
            ->assertSee('Hello friend!');

        $conversation = Conversation::between($user, $friend);

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => 'Hello friend!',
        ]);

        Event::assertDispatched(MessageSent::class, fn (MessageSent $event): bool => $event->message->body === 'Hello friend!');
    }

    public function test_sending_a_message_appends_to_the_selected_conversation(): void
    {
        [$user, $friend] = $this->createFriends();
        $conversation = Conversation::between($user, $friend);

        Event::fake([MessageSent::class]);

        $this->actingAs($user);

        Livewire::test('chat')
            ->call('selectConversation', $conversation->id)
            ->call('sendMessage', 'How are you?');

        $this->assertDatabaseHas('messages', [
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'body' => 'How are you?',
        ]);

        Event::assertDispatched(MessageSent::class);
    }

    public function test_a_non_friend_cannot_start_a_conversation(): void
    {
        $user = User::factory()->create();
        $stranger = User::factory()->create();

        $this->actingAs($user);

        Livewire::test('chat')
            ->set('newMessageRecipientId', $stranger->id)
            ->set('newMessageBody', 'Hi!')
            ->call('startConversation')
            ->assertForbidden();

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_a_user_cannot_message_themselves(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        Livewire::test('chat')
            ->set('newMessageRecipientId', $user->id)
            ->set('newMessageBody', 'Hi me!')
            ->call('startConversation')
            ->assertForbidden();
    }

    public function test_sending_a_message_to_an_unfriended_user_is_forbidden(): void
    {
        $user = User::factory()->create();
        $exFriend = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'user_one_id' => min($user->id, $exFriend->id),
            'user_two_id' => max($user->id, $exFriend->id),
        ]);

        $this->actingAs($user);

        Livewire::test('chat')
            ->call('selectConversation', $conversation->id)
            ->call('sendMessage', 'Hey again')
            ->assertForbidden();

        $this->assertDatabaseCount('messages', 0);
    }

    public function test_the_composer_is_disabled_in_a_conversation_with_a_non_friend(): void
    {
        $user = User::factory()->create();
        $exFriend = User::factory()->create();
        $conversation = Conversation::factory()->create([
            'user_one_id' => min($user->id, $exFriend->id),
            'user_two_id' => max($user->id, $exFriend->id),
        ]);

        $this->actingAs($user);

        Livewire::test('chat')
            ->call('selectConversation', $conversation->id)
            ->assertSee('You can only message friends')
            ->assertDontSee('Write a message');
    }

    public function test_a_user_cannot_open_someone_elses_conversation(): void
    {
        $viewer = User::factory()->create();
        $conversation = Conversation::factory()->create();

        $this->actingAs($viewer);

        Livewire::test('chat')
            ->call('selectConversation', $conversation->id)
            ->assertForbidden();
    }

    public function test_conversations_list_only_shows_own_conversations(): void
    {
        $viewer = User::factory()->create();
        $other = User::factory()->create(['name' => 'Hidden Participant']);
        Conversation::factory()->create(['user_one_id' => $other->id]);

        $this->actingAs($viewer);

        Livewire::test('chat')
            ->assertDontSee('Hidden Participant')
            ->assertSee('No conversations yet');
    }

    public function test_between_reuses_the_conversation_regardless_of_argument_order(): void
    {
        [$user, $friend] = $this->createFriends();

        $first = Conversation::between($user, $friend);
        $second = Conversation::between($friend, $user);

        $this->assertTrue($first->is($second));
        $this->assertDatabaseCount('conversations', 1);
    }

    public function test_opening_a_conversation_marks_incoming_messages_as_read(): void
    {
        [$user, $friend] = $this->createFriends();
        $conversation = Conversation::between($user, $friend);
        $incoming = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $friend->id,
        ]);
        $outgoing = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
        ]);

        $this->actingAs($user);

        Livewire::test('chat')->call('selectConversation', $conversation->id);

        $this->assertNotNull($incoming->fresh()->read_at);
        $this->assertNull($outgoing->fresh()->read_at);
    }

    public function test_the_listener_broadcasts_the_message_to_the_recipient(): void
    {
        [$user, $friend] = $this->createFriends();
        $conversation = Conversation::between($user, $friend);
        $message = Message::factory()->create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
        ]);

        Event::fake([MessageCreated::class]);

        (new SendMessageBroadcast)->handle(new MessageSent($message));

        Event::assertDispatched(MessageCreated::class, function (MessageCreated $event) use ($friend, $message): bool {
            return $event->recipient->is($friend)
                && $event->message->is($message)
                && $event->broadcastAs() === 'message.created'
                && $event->broadcastOn()[0]->name === 'private-users.'.$friend->id
                && $event->broadcastWith()['body'] === $message->body;
        });
    }
}

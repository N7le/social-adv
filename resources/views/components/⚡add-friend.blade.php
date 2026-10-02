<?php

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component {
    public User $target;

    public string $state = '';

    public function mount(User $target): void
    {
        $this->target = $target;
        $this->updateState();
    }

    #[Computed]
    public function canSendRequest(): bool
    {
        return Gate::allows('sendFriendRequest', $this->target);
    }

    public function addFriend(): void
    {
        Gate::authorize('sendFriendRequest', $this->target);

        FriendRequest::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->target->id,
        ]);

        $this->updateState();
    }

    public function unfriend(): void
    {
        Auth::user()->getFriendRequest($this->target)->delete();

        $this->updateState();
    }

    public function cancelRequest(): void
    {
        Auth::user()->friendRequestsSent()->where('receiver_id', $this->target->id)->where('status', 'pending')->delete();

        $this->updateState();
    }

    public function acceptRequest(): void
    {
        Auth::user()
            ->friendRequestsReceived()
            ->where('sender_id', $this->target->id)
            ->where('status', 'pending')
            ->update(['status' => 'accepted']);

        $this->updateState();
    }

    public function declineRequest(): void
    {
        Auth::user()->getFriendRequest($this->target)->delete();
        $this->updateState();
    }

    private function updateState(): void
    {
        $user = Auth::user();
        if ($user->isFriendsWith($this->target)) {
            $this->state = 'friends';
        } elseif ($user->isRequestSentTo($this->target)) {
            $this->state = 'pending-sent';
        } elseif ($user->isRequestReceivedFrom($this->target)) {
            $this->state = 'pending-received';
        } else {
            $this->state = 'nothing';
        }
    }
};
?>
<div>
    @switch($state)
        @case('nothing')
            @if ($this->canSendRequest)
                <button class="btn btn-primary" type="button" wire:click="addFriend">
                    <i class="bi bi-person-plus me-1" aria-hidden="true"></i>Add Friend
                </button>
            @endif
        @break

        @case('friends')
            <button class="btn btn-primary" type="button" wire:click="unfriend">
                <i class="bi bi-person-dash me-1" aria-hidden="true"></i>Unfriend
            </button>
        @break

        @case('pending-sent')
            <button class="btn btn-primary" type="button" wire:click="cancelRequest">
                <i class="bi bi-person-x me-1" aria-hidden="true"></i>Cancel Request
            </button>
        @break

        @case('pending-received')
            <button class="btn btn-primary" type="button" wire:click="acceptRequest">
                <i class="bi bi-person-check me-1" aria-hidden="true"></i>Accept
            </button>
            <button class="btn btn-primary" type="button" wire:click="declineRequest">
                <i class="bi bi-person-check me-1" aria-hidden="true"></i>Decline
            </button>
        @break
    @endswitch
</div>

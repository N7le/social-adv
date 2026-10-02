<?php

use App\Models\FriendRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

new class extends Component
{
    public User $target;

    public bool $requestSent = false;

    public function mount(User $target): void
    {
        $this->target = $target;
    }

    #[Computed]
    public function requestSent(): bool
    {
        return Auth::user()?->isRequestSentTo($this->target) ?? false;
    }

    #[Computed]
    public function canSendRequest(): bool
    {
        return Auth::check() && Gate::allows('sendFriendRequest', $this->target);
    }

    public function addFriend(): void
    {
        if ($this->requestSent) {
            return;
        }

        Gate::authorize('sendFriendRequest', $this->target);

        FriendRequest::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->target->id,
        ]);

        $this->requestSent = true;
    }

    public function cancelRequest(): void
    {
        $user = Auth::user();
        abort_unless($user instanceof User, 403);

        $user->friendRequestsSent()
            ->where('receiver_id', $this->target->id)
            ->where('status', 'pending')
            ->delete();

        $this->requestSent = false;
    }
};
?>

<div>
    @if ($this->requestSent)
        <button class="btn btn-sm btn-outline-primary" type="button" wire:click="cancelRequest" wire:loading.attr="disabled" wire:target="cancelRequest" aria-label="Cancel friend request to {{ $target->name }}">
            <i class="bi bi-person-x" wire:loading.remove wire:target="cancelRequest" aria-hidden="true"></i>
            <span class="spinner-border spinner-border-sm" wire:loading wire:target="cancelRequest" aria-hidden="true"></span>
        </button>
    @elseif ($this->canSendRequest)
        <button class="btn btn-sm btn-outline-primary" type="button" wire:click="addFriend" wire:loading.attr="disabled" wire:target="addFriend" aria-label="Add {{ $target->name }} as a friend">
            <i class="bi bi-person-plus" wire:loading.remove wire:target="addFriend" aria-hidden="true"></i>
            <span class="spinner-border spinner-border-sm" wire:loading wire:target="addFriend" aria-hidden="true"></span>
        </button>
    @endif
</div>
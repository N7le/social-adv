<?php

use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Post $post;

    #[Computed]
    public function isLiked(): bool
    {
        return $this->post->likes()->whereKey(Auth::id())->exists();
    }

    public function toggleLike(): void
    {
        if ($this->isLiked) {
            $this->post->likes()->detach(Auth::id());
        } else {
            Gate::authorize('like', $this->post);

            $this->post->likes()->syncWithoutDetaching([Auth::id()]);
        }

        unset($this->isLiked);

        $this->dispatch('likes-updated.'.$this->post->id);
    }
};
?>

<button
    @class(['btn flex-fill', 'text-primary fw-semibold' => $this->isLiked])
    type="button"
    wire:click="toggleLike"
    wire:loading.attr="disabled"
    aria-pressed="{{ $this->isLiked ? 'true' : 'false' }}"
>
    <i @class(['bi me-1', 'bi-hand-thumbs-up-fill' => $this->isLiked, 'bi-hand-thumbs-up' => ! $this->isLiked]) aria-hidden="true"></i>{{ $this->isLiked ? 'Liked' : 'Like' }}
</button>
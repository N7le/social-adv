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
    public function isShared(): bool
    {
        return $this->post->shares()->whereKey(Auth::id())->exists();
    }

    public function sharePost(): void
    {
        if (! $this->isShared) {
            Gate::authorize('share', $this->post);

            $this->post->shares()->syncWithoutDetaching([Auth::id()]);

            unset($this->isShared);

            $this->dispatch('shares-updated.'.$this->post->id);
        }
    }
};
?>

<div class="flex-fill">
    <button
        @class(['btn w-100', 'text-primary fw-semibold' => $this->isShared])
        type="button"
        wire:click="sharePost"
        wire:loading.attr="disabled"
        aria-pressed="{{ $this->isShared ? 'true' : 'false' }}"
        data-copy-share-url="{{ route('home').'#post-'.$post->id }}"
    >
        <i @class(['bi me-1', 'bi-share-fill' => $this->isShared, 'bi-share' => ! $this->isShared]) aria-hidden="true"></i>{{ $this->isShared ? 'Shared' : 'Share' }}
    </button>
    <span class="visually-hidden" data-share-status role="status" aria-live="polite"></span>
</div>
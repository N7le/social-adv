<?php

use App\Models\Post;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    #[Locked]
    public Post $post;

    #[Locked]
    public int $sharesCount = 0;

    public function mount(Post $post): void
    {
        $this->post = $post;
        $this->sharesCount = $post->shares()->count();
    }

    #[On('shares-updated.{post.id}')]
    public function refreshCount(): void
    {
        $this->sharesCount = $this->post->shares()->count();
    }
};
?>

<span>{{ $sharesCount }} {{ Str::plural('share', $sharesCount) }}</span>